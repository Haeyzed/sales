<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Adjustment;
use App\Models\Product;
use App\Models\ProductAdjustment;
use App\Models\ProductVariant;
use App\Models\ProductWarehouse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * AdjustmentService
 *
 * Service class for handling stock adjustment-related business logic.
 */
class AdjustmentService
{
    /**
     * Get paginated adjustments with filters.
     *
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedAdjustments(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Adjustment::with(['warehouse', 'user']);

        // Filter by warehouse if provided
        if (isset($filters['warehouse_id']) && $filters['warehouse_id'] > 0) {
            $query->where('warehouse_id', (int) $filters['warehouse_id']);
        }

        // Search functionality
        if (isset($filters['search']) && !empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search): void {
                $q->where('reference_no', 'LIKE', "%{$search}%")
                    ->orWhere('note', 'LIKE', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        return $query->paginate($perPage);
    }

    /**
     * Get adjustment by ID with relationships.
     *
     * @param int $id
     * @return Adjustment|null
     */
    public function getAdjustmentById(int $id): ?Adjustment
    {
        return Adjustment::with(['warehouse', 'user', 'productAdjustments.product'])
            ->find($id);
    }

    /**
     * Create a new adjustment.
     *
     * @param array<string, mixed> $data
     * @return Adjustment
     */
    public function createAdjustment(array $data): Adjustment
    {
        $data['reference_no'] = 'adr-' . date('Ymd') . '-' . date('his');
        $data['user_id'] = auth()->id();

        $adjustment = Adjustment::create($data);

        // Handle product adjustments
        if (isset($data['product_id']) && is_array($data['product_id'])) {
            $this->processProductAdjustments($adjustment, $data);
        }

        return $adjustment;
    }

    /**
     * Update an existing adjustment.
     *
     * @param int $id
     * @param array<string, mixed> $data
     * @return Adjustment
     */
    public function updateAdjustment(int $id, array $data): Adjustment
    {
        $adjustment = Adjustment::findOrFail($id);

        // Reverse previous adjustments
        $this->reverseProductAdjustments($adjustment);

        // Update adjustment
        $adjustment->update($data);

        // Apply new adjustments
        if (isset($data['product_id']) && is_array($data['product_id'])) {
            $this->processProductAdjustments($adjustment, $data);
        }

        return $adjustment->fresh();
    }

    /**
     * Delete an adjustment.
     *
     * @param int $id
     * @return bool
     */
    public function deleteAdjustment(int $id): bool
    {
        $adjustment = Adjustment::findOrFail($id);

        // Reverse adjustments before deleting
        $this->reverseProductAdjustments($adjustment);

        return $adjustment->delete();
    }

    /**
     * Process product adjustments.
     *
     * @param Adjustment $adjustment
     * @param array<string, mixed> $data
     * @return void
     */
    private function processProductAdjustments(Adjustment $adjustment, array $data): void
    {
        $productIds = $data['product_id'] ?? [];
        $productCodes = $data['product_code'] ?? [];
        $qtys = $data['qty'] ?? [];
        $unitCosts = $data['unit_cost'] ?? [];
        $actions = $data['action'] ?? [];
        $warehouseId = $adjustment->warehouse_id;

        foreach ($productIds as $key => $productId) {
            $product = Product::find($productId);
            if (!$product) {
                continue;
            }

            $qty = (float) ($qtys[$key] ?? 0);
            $action = $actions[$key] ?? '+';
            $unitCost = (float) ($unitCosts[$key] ?? 0);
            $variantId = null;

            // Handle variant products
            if ($product->is_variant) {
                $productCode = $productCodes[$key] ?? '';
                $productVariant = ProductVariant::where('product_id', $productId)
                    ->where('item_code', $productCode)
                    ->first();

                if ($productVariant) {
                    $variantId = $productVariant->variant_id;
                    $productWarehouse = ProductWarehouse::where('product_id', $productId)
                        ->where('variant_id', $variantId)
                        ->where('warehouse_id', $warehouseId)
                        ->first();

                    if ($action === '-') {
                        $productVariant->qty -= $qty;
                        if ($productWarehouse) {
                            $productWarehouse->qty -= $qty;
                        }
                    } elseif ($action === '+') {
                        $productVariant->qty += $qty;
                        if ($productWarehouse) {
                            $productWarehouse->qty += $qty;
                        }
                    }

                    $productVariant->save();
                    if ($productWarehouse) {
                        $productWarehouse->save();
                    }
                }
            } else {
                $productWarehouse = ProductWarehouse::where('product_id', $productId)
                    ->where('warehouse_id', $warehouseId)
                    ->whereNull('variant_id')
                    ->first();
            }

            // Update product and warehouse quantities
            if ($action === '-') {
                $product->qty -= $qty;
                if ($productWarehouse) {
                    $productWarehouse->qty -= $qty;
                }
            } elseif ($action === '+') {
                $product->qty += $qty;
                if ($productWarehouse) {
                    $productWarehouse->qty += $qty;
                }
            }

            $product->save();
            if ($productWarehouse) {
                $productWarehouse->save();
            }

            // Create product adjustment record
            ProductAdjustment::create([
                'product_id' => $productId,
                'variant_id' => $variantId,
                'adjustment_id' => $adjustment->id,
                'qty' => $qty,
                'unit_cost' => $unitCost,
                'action' => $action,
            ]);
        }
    }

    /**
     * Reverse product adjustments.
     *
     * @param Adjustment $adjustment
     * @return void
     */
    private function reverseProductAdjustments(Adjustment $adjustment): void
    {
        $productAdjustments = ProductAdjustment::where('adjustment_id', $adjustment->id)->get();

        foreach ($productAdjustments as $productAdjustment) {
            $product = Product::find($productAdjustment->product_id);
            if (!$product) {
                continue;
            }

            $warehouseId = $adjustment->warehouse_id;
            $action = $productAdjustment->action;
            $qty = $productAdjustment->qty;
            $reversedAction = $action === '+' ? '-' : '+';

            // Handle variant products
            if ($productAdjustment->variant_id) {
                $productVariant = ProductVariant::where('product_id', $product->id)
                    ->where('variant_id', $productAdjustment->variant_id)
                    ->first();

                if ($productVariant) {
                    if ($reversedAction === '-') {
                        $productVariant->qty -= $qty;
                    } else {
                        $productVariant->qty += $qty;
                    }
                    $productVariant->save();
                }

                $productWarehouse = ProductWarehouse::where('product_id', $product->id)
                    ->where('variant_id', $productAdjustment->variant_id)
                    ->where('warehouse_id', $warehouseId)
                    ->first();
            } else {
                $productWarehouse = ProductWarehouse::where('product_id', $product->id)
                    ->where('warehouse_id', $warehouseId)
                    ->whereNull('variant_id')
                    ->first();
            }

            // Reverse product and warehouse quantities
            if ($reversedAction === '-') {
                $product->qty -= $qty;
                if ($productWarehouse) {
                    $productWarehouse->qty -= $qty;
                }
            } else {
                $product->qty += $qty;
                if ($productWarehouse) {
                    $productWarehouse->qty += $qty;
                }
            }

            $product->save();
            if ($productWarehouse) {
                $productWarehouse->save();
            }
        }
    }

    /**
     * Get products for a warehouse (for adjustment form).
     *
     * @param int $warehouseId
     * @return array<string, mixed>
     */
    public function getProductsForWarehouse(int $warehouseId): array
    {
        // Get products without variants
        $productsWithoutVariant = DB::table('products')
            ->join('product_warehouse', 'products.id', '=', 'product_warehouse.product_id')
            ->whereNull('products.is_variant')
            ->where([
                ['products.is_active', true],
                ['product_warehouse.warehouse_id', $warehouseId],
            ])
            ->select('product_warehouse.qty', 'products.code', 'products.name', 'product_warehouse.product_id', 'products.cost')
            ->get();

        // Get products with variants
        $productsWithVariant = DB::table('products')
            ->join('product_warehouse', 'products.id', '=', 'product_warehouse.product_id')
            ->whereNotNull('products.is_variant')
            ->where([
                ['products.is_active', true],
                ['product_warehouse.warehouse_id', $warehouseId],
            ])
            ->select('products.name', 'product_warehouse.qty', 'product_warehouse.product_id', 'product_warehouse.variant_id', 'products.cost')
            ->get();

        $productCode = [];
        $productName = [];
        $productQty = [];
        $productCost = [];

        foreach ($productsWithoutVariant as $productWarehouse) {
            $productQty[] = $productWarehouse->qty;
            $productCode[] = $productWarehouse->code;
            $productName[] = $productWarehouse->name;

            // Calculate average cost from purchases
            $productPurchaseData = DB::table('product_purchases')
                ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.id')
                ->where([
                    ['product_purchases.product_id', $productWarehouse->product_id],
                    ['purchases.warehouse_id', $warehouseId],
                ])
                ->selectRaw('SUM(product_purchases.qty) AS total_qty, SUM(product_purchases.total) AS total_cost')
                ->first();

            if ($productPurchaseData && $productPurchaseData->total_qty > 0) {
                $productCost[] = $productPurchaseData->total_cost / $productPurchaseData->total_qty;
            } else {
                $productCost[] = $productWarehouse->cost;
            }
        }

        foreach ($productsWithVariant as $productWarehouse) {
            $productVariant = ProductVariant::where('product_id', $productWarehouse->product_id)
                ->where('variant_id', $productWarehouse->variant_id)
                ->first();

            if ($productVariant) {
                $productQty[] = $productWarehouse->qty;
                $productCode[] = $productVariant->item_code;
                $productName[] = $productWarehouse->name;

                // Calculate average cost
                $productPurchaseData = DB::table('product_purchases')
                    ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.id')
                    ->where([
                        ['product_purchases.product_id', $productWarehouse->product_id],
                        ['product_purchases.variant_id', $productWarehouse->variant_id],
                        ['purchases.warehouse_id', $warehouseId],
                    ])
                    ->selectRaw('SUM(product_purchases.qty) AS total_qty, SUM(product_purchases.total) AS total_cost')
                    ->first();

                if ($productPurchaseData && $productPurchaseData->total_qty > 0) {
                    $productCost[] = $productPurchaseData->total_cost / $productPurchaseData->total_qty;
                } else {
                    $productCost[] = $productWarehouse->cost;
                }
            }
        }

        return [
            'product_code' => $productCode,
            'product_name' => $productName,
            'product_qty' => $productQty,
            'product_cost' => $productCost,
        ];
    }

    /**
     * Search for a product by code.
     *
     * @param string $code
     * @return array<string, mixed>|null
     */
    public function searchProduct(string $code): ?array
    {
        $productCode = explode('(', $code);
        $productCode[0] = rtrim($productCode[0], ' ');

        $product = Product::where('code', $productCode[0])
            ->where('is_active', true)
            ->first();

        if (!$product) {
            $product = Product::join('product_variants', 'products.id', 'product_variants.product_id')
                ->select('products.id', 'products.name', 'products.is_variant', 'product_variants.id as product_variant_id', 'product_variants.item_code')
                ->where('product_variants.item_code', $productCode[0])
                ->where('products.is_active', true)
                ->first();
        }

        if (!$product) {
            return null;
        }

        $result = [
            'name' => $product->name,
            'id' => $product->id,
            'variant_id' => $product->product_variant_id ?? null,
        ];

        if ($product->is_variant) {
            $result['code'] = $product->item_code ?? $product->code;
        } else {
            $result['code'] = $product->code;
        }

        // Get quantity info if available
        $productInfo = explode('|', $code);
        if (isset($productInfo[1])) {
            $result['qty'] = $productInfo[1];
        }

        return $result;
    }
}

