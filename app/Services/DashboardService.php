<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Expense;
use App\Models\Income;
use App\Models\Payroll;
use App\Models\Product;
use App\Models\ProductPurchase;
use App\Models\ProductSale;
use App\Models\Purchase;
use App\Models\ReturnPurchase;
use App\Models\Returns;
use App\Models\Sale;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * DashboardService
 *
 * Service class for handling dashboard-related business logic and statistics.
 */
class DashboardService
{
    /**
     * Get dashboard statistics for the current month.
     *
     * @param User|null $user The user to filter by (null for all users)
     * @param bool $staffAccessOwn Whether staff can only see their own data
     * @return array<string, float>
     */
    public function getMonthlyStatistics(?User $user = null, bool $staffAccessOwn = false): array
    {
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t');

        return $this->getStatisticsForDateRange($startDate, $endDate, $user, $staffAccessOwn);
    }

    /**
     * Get dashboard statistics for a specific date range.
     *
     * @param string $startDate The start date (Y-m-d format)
     * @param string $endDate The end date (Y-m-d format)
     * @param User|null $user The user to filter by (null for all users)
     * @param bool $staffAccessOwn Whether staff can only see their own data
     * @param int|null $warehouseId The warehouse ID to filter by (null for all warehouses)
     * @return array<string, float>
     */
    public function getStatisticsForDateRange(
        string $startDate,
        string $endDate,
        ?User $user = null,
        bool $staffAccessOwn = false,
        ?int $warehouseId = null
    ): array {
        $query = $this->buildBaseQuery($user, $staffAccessOwn, $warehouseId);

        $productSaleData = $this->getProductSaleData($startDate, $endDate, $query);
        $productCost = $this->calculateAverageCOGS($productSaleData);

        $revenue = $this->getRevenue($startDate, $endDate, $query);
        $return = $this->getReturnAmount($startDate, $endDate, $query);
        $purchaseReturn = $this->getPurchaseReturnAmount($startDate, $endDate, $query);
        $expense = $this->getExpenseAmount($startDate, $endDate, $query);
        $income = $this->getIncomeAmount($startDate, $endDate, $query);
        $purchase = $this->getPurchaseAmount($startDate, $endDate, $query);

        $revenue = $revenue - $return + $income;
        $profit = $revenue + $purchaseReturn - $productCost - $expense;

        return [
            'revenue' => (float) $revenue,
            'purchase' => (float) $purchase,
            'expense' => (float) $expense,
            'return' => (float) $return,
            'purchaseReturn' => (float) $purchaseReturn,
            'profit' => (float) $profit,
        ];
    }

    /**
     * Get cash flow data for the last 6 months.
     *
     * @param User|null $user The user to filter by (null for all users)
     * @param bool $staffAccessOwn Whether staff can only see their own data
     * @return array<string, array<int, string>>
     */
    public function getCashFlowData(?User $user = null, bool $staffAccessOwn = false): array
    {
        $paymentReceived = [];
        $paymentSent = [];
        $months = [];

        $start = strtotime(date('Y-m-01', strtotime('-6 months')));
        $end = strtotime(date('Y-m-t'));

        while ($start < $end) {
            $startDate = date('Y-m-01', $start);
            $endDate = date('Y-m-t', $start);

            $query = $this->buildBaseQuery($user, $staffAccessOwn);

            $receivedAmount = $this->getReceivedAmount($startDate, $endDate, $query);
            $sentAmount = $this->getSentAmount($startDate, $endDate, $query);
            $returnAmount = $this->getReturnAmount($startDate, $endDate, $query);
            $purchaseReturnAmount = $this->getPurchaseReturnAmount($startDate, $endDate, $query);
            $expenseAmount = $this->getExpenseAmount($startDate, $endDate, $query);
            $payrollAmount = $this->getPayrollAmount($startDate, $endDate, $query);

            $sentAmount = $sentAmount + $returnAmount + $expenseAmount + $payrollAmount;

            $paymentReceived[] = number_format((float) ($receivedAmount + $purchaseReturnAmount), 2, '.', '');
            $paymentSent[] = number_format((float) $sentAmount, 2, '.', '');
            $months[] = date('F', strtotime($startDate));

            $start = strtotime('+1 month', $start);
        }

        return [
            'paymentReceived' => $paymentReceived,
            'paymentSent' => $paymentSent,
            'months' => $months,
        ];
    }

    /**
     * Get yearly sales and purchase data.
     *
     * @param User|null $user The user to filter by (null for all users)
     * @param bool $staffAccessOwn Whether staff can only see their own data
     * @return array<string, array<int, string>>
     */
    public function getYearlyData(?User $user = null, bool $staffAccessOwn = false): array
    {
        $yearlySaleAmount = [];
        $yearlyPurchaseAmount = [];

        $start = strtotime(date('Y-01-01'));
        $end = strtotime(date('Y-12-31'));

        while ($start < $end) {
            $startDate = date('Y-m-01', $start);
            $endDate = date('Y-m-t', $start);

            $query = $this->buildBaseQuery($user, $staffAccessOwn);

            $saleAmount = $this->getSaleAmount($startDate, $endDate, $query);
            $purchaseAmount = $this->getPurchaseAmount($startDate, $endDate, $query);

            $yearlySaleAmount[] = number_format((float) $saleAmount, 2, '.', '');
            $yearlyPurchaseAmount[] = number_format((float) $purchaseAmount, 2, '.', '');

            $start = strtotime('+1 month', $start);
        }

        return [
            'yearlySaleAmount' => $yearlySaleAmount,
            'yearlyPurchaseAmount' => $yearlyPurchaseAmount,
        ];
    }

    /**
     * Build base query conditions.
     *
     * @param User|null $user The user to filter by
     * @param bool $staffAccessOwn Whether staff can only see their own data
     * @param int|null $warehouseId The warehouse ID to filter by
     * @return array<string, mixed>
     */
    private function buildBaseQuery(?User $user = null, bool $staffAccessOwn = false, ?int $warehouseId = null): array
    {
        return [
            'user' => $user,
            'staffAccessOwn' => $staffAccessOwn,
            'warehouseId' => $warehouseId,
        ];
    }

    /**
     * Get product sale data for date range.
     *
     * @param string $startDate
     * @param string $endDate
     * @param array<string, mixed> $query
     * @return Collection
     */
    private function getProductSaleData(string $startDate, string $endDate, array $query): Collection
    {
        $baseQuery = ProductSale::join('sales', 'product_sales.sale_id', '=', 'sales.id')
            ->select(DB::raw('
                product_sales.product_id,
                product_sales.product_batch_id,
                product_sales.sale_unit_id,
                sum(product_sales.qty) as sold_qty,
                sum(product_sales.return_qty) as return_qty,
                sum(product_sales.total) as sold_amount
            '))
            ->whereDate('sales.created_at', '>=', $startDate)
            ->whereDate('sales.created_at', '<=', $endDate);

        if ($query['user'] && $query['staffAccessOwn']) {
            $baseQuery->where('sales.user_id', $query['user']->id);
        }

        if ($query['warehouseId']) {
            $baseQuery->where('sales.warehouse_id', $query['warehouseId']);
        }

        return $baseQuery->groupBy('product_sales.product_id', 'product_sales.product_batch_id', 'product_sales.sale_unit_id')
            ->get();
    }

    /**
     * Calculate average Cost of Goods Sold (COGS).
     *
     * @param Collection $productSaleData
     * @return float
     */
    private function calculateAverageCOGS(Collection $productSaleData): float
    {
        $productCost = 0.0;
        $units = Unit::select('id', 'operator', 'operation_value')->get();

        foreach ($productSaleData as $productSale) {
            $product = Product::select('type', 'product_list', 'variant_list', 'qty_list')
                ->find($productSale->product_id);

            if ($product && $product->type === 'combo') {
                $productCost += $this->calculateComboProductCost($product, $productSale, $units);
            } else {
                $productCost += $this->calculateRegularProductCost($productSale, $units);
            }
        }

        return $productCost;
    }

    /**
     * Calculate cost for combo products.
     *
     * @param Product $product
     * @param mixed $productSale
     * @param Collection $units
     * @return float
     */
    private function calculateComboProductCost(Product $product, mixed $productSale, Collection $units): float
    {
        $productList = explode(',', $product->product_list);
        $variantList = $product->variant_list ? explode(',', $product->variant_list) : [];
        $qtyList = explode(',', $product->qty_list);

        $cost = 0.0;

        foreach ($productList as $index => $productId) {
            $variantId = $variantList[$index] ?? null;
            $productPurchaseData = $this->getProductPurchaseData((int) $productId, $variantId);

            $totalReceivedQty = 0.0;
            $totalPurchasedAmount = 0.0;
            $soldQty = ($productSale->sold_qty - $productSale->return_qty) * (float) ($qtyList[$index] ?? 1);

            foreach ($productPurchaseData as $purchase) {
                $purchaseUnit = $units->where('id', $purchase->purchase_unit_id)->first();
                if ($purchaseUnit) {
                    if ($purchaseUnit->operator === '*') {
                        $totalReceivedQty += $purchase->recieved * $purchaseUnit->operation_value;
                    } else {
                        $totalReceivedQty += $purchase->recieved / $purchaseUnit->operation_value;
                    }
                    $totalPurchasedAmount += $purchase->total;
                }
            }

            $averageCost = $totalReceivedQty > 0 ? $totalPurchasedAmount / $totalReceivedQty : 0.0;
            $cost += $soldQty * $averageCost;
        }

        return $cost;
    }

    /**
     * Calculate cost for regular products.
     *
     * @param mixed $productSale
     * @param Collection $units
     * @return float
     */
    private function calculateRegularProductCost(mixed $productSale, Collection $units): float
    {
        $productPurchaseData = $this->getProductPurchaseData(
            $productSale->product_id,
            $productSale->variant_id ?? null,
            $productSale->product_batch_id ?? null
        );

        $totalReceivedQty = 0.0;
        $totalPurchasedAmount = 0.0;

        $saleUnit = $units->where('id', $productSale->sale_unit_id)->first();
        if ($saleUnit) {
            if ($saleUnit->operator === '*') {
                $soldQty = ($productSale->sold_qty - $productSale->return_qty) * $saleUnit->operation_value;
            } else {
                $soldQty = ($productSale->sold_qty - $productSale->return_qty) / $saleUnit->operation_value;
            }
        } else {
            $soldQty = $productSale->sold_qty - $productSale->return_qty;
        }

        foreach ($productPurchaseData as $purchase) {
            $purchaseUnit = $units->where('id', $purchase->purchase_unit_id)->first();
            if ($purchaseUnit) {
                if ($purchaseUnit->operator === '*') {
                    $totalReceivedQty += $purchase->recieved * $purchaseUnit->operation_value;
                } else {
                    $totalReceivedQty += $purchase->recieved / $purchaseUnit->operation_value;
                }
                $totalPurchasedAmount += $purchase->total;
            }
        }

        $averageCost = $totalReceivedQty > 0 ? $totalPurchasedAmount / $totalReceivedQty : 0.0;

        return $soldQty * $averageCost;
    }

    /**
     * Get product purchase data.
     *
     * @param int $productId
     * @param int|null $variantId
     * @param int|null $batchId
     * @return Collection
     */
    private function getProductPurchaseData(int $productId, ?int $variantId = null, ?int $batchId = null): Collection
    {
        $query = ProductPurchase::where('product_id', $productId);

        if ($batchId) {
            $query->where('product_batch_id', $batchId);
        } elseif ($variantId) {
            $query->where('variant_id', $variantId);
        }

        return $query->select('recieved', 'purchase_unit_id', 'total')->get();
    }

    /**
     * Get revenue amount.
     *
     * @param string $startDate
     * @param string $endDate
     * @param array<string, mixed> $query
     * @return float
     */
    private function getRevenue(string $startDate, string $endDate, array $query): float
    {
        $baseQuery = Sale::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        if ($query['user'] && $query['staffAccessOwn']) {
            $baseQuery->where('user_id', $query['user']->id);
        }

        if ($query['warehouseId']) {
            $baseQuery->where('warehouse_id', $query['warehouseId']);
        }

        return (float) $baseQuery->sum(DB::raw('grand_total - shipping_cost'));
    }

    /**
     * Get return amount.
     *
     * @param string $startDate
     * @param string $endDate
     * @param array<string, mixed> $query
     * @return float
     */
    private function getReturnAmount(string $startDate, string $endDate, array $query): float
    {
        $baseQuery = Returns::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        if ($query['user'] && $query['staffAccessOwn']) {
            $baseQuery->where('user_id', $query['user']->id);
        }

        if ($query['warehouseId']) {
            $baseQuery->where('warehouse_id', $query['warehouseId']);
        }

        return (float) $baseQuery->sum('grand_total');
    }

    /**
     * Get purchase return amount.
     *
     * @param string $startDate
     * @param string $endDate
     * @param array<string, mixed> $query
     * @return float
     */
    private function getPurchaseReturnAmount(string $startDate, string $endDate, array $query): float
    {
        $baseQuery = ReturnPurchase::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        if ($query['user'] && $query['staffAccessOwn']) {
            $baseQuery->where('user_id', $query['user']->id);
        }

        if ($query['warehouseId']) {
            $baseQuery->where('warehouse_id', $query['warehouseId']);
        }

        return (float) $baseQuery->sum('grand_total');
    }

    /**
     * Get expense amount.
     *
     * @param string $startDate
     * @param string $endDate
     * @param array<string, mixed> $query
     * @return float
     */
    private function getExpenseAmount(string $startDate, string $endDate, array $query): float
    {
        $baseQuery = Expense::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        if ($query['user'] && $query['staffAccessOwn']) {
            $baseQuery->where('user_id', $query['user']->id);
        }

        return (float) $baseQuery->sum('amount');
    }

    /**
     * Get income amount.
     *
     * @param string $startDate
     * @param string $endDate
     * @param array<string, mixed> $query
     * @return float
     */
    private function getIncomeAmount(string $startDate, string $endDate, array $query): float
    {
        $baseQuery = Income::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        if ($query['user'] && $query['staffAccessOwn']) {
            $baseQuery->where('user_id', $query['user']->id);
        }

        return (float) $baseQuery->sum('amount');
    }

    /**
     * Get purchase amount.
     *
     * @param string $startDate
     * @param string $endDate
     * @param array<string, mixed> $query
     * @return float
     */
    private function getPurchaseAmount(string $startDate, string $endDate, array $query): float
    {
        $baseQuery = Purchase::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        if ($query['user'] && $query['staffAccessOwn']) {
            $baseQuery->where('user_id', $query['user']->id);
        }

        return (float) $baseQuery->sum('grand_total');
    }

    /**
     * Get sale amount.
     *
     * @param string $startDate
     * @param string $endDate
     * @param array<string, mixed> $query
     * @return float
     */
    private function getSaleAmount(string $startDate, string $endDate, array $query): float
    {
        $baseQuery = Sale::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        if ($query['user'] && $query['staffAccessOwn']) {
            $baseQuery->where('user_id', $query['user']->id);
        }

        return (float) $baseQuery->sum('grand_total');
    }

    /**
     * Get received payment amount.
     *
     * @param string $startDate
     * @param string $endDate
     * @param array<string, mixed> $query
     * @return float
     */
    private function getReceivedAmount(string $startDate, string $endDate, array $query): float
    {
        $baseQuery = DB::table('payments')
            ->whereNotNull('sale_id')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        if ($query['user'] && $query['staffAccessOwn']) {
            $baseQuery->where('user_id', $query['user']->id);
        }

        return (float) $baseQuery->sum('amount');
    }

    /**
     * Get sent payment amount.
     *
     * @param string $startDate
     * @param string $endDate
     * @param array<string, mixed> $query
     * @return float
     */
    private function getSentAmount(string $startDate, string $endDate, array $query): float
    {
        $baseQuery = DB::table('payments')
            ->whereNotNull('purchase_id')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        if ($query['user'] && $query['staffAccessOwn']) {
            $baseQuery->where('user_id', $query['user']->id);
        }

        return (float) $baseQuery->sum('amount');
    }

    /**
     * Get payroll amount.
     *
     * @param string $startDate
     * @param string $endDate
     * @param array<string, mixed> $query
     * @return float
     */
    private function getPayrollAmount(string $startDate, string $endDate, array $query): float
    {
        $baseQuery = Payroll::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        if ($query['user'] && $query['staffAccessOwn']) {
            $baseQuery->where('user_id', $query['user']->id);
        }

        return (float) $baseQuery->sum('amount');
    }
}

