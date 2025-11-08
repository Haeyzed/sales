<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductPurchase;
use App\Models\ProductWarehouse;
use App\Models\Purchase;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * AutoPurchase Command
 *
 * Automatically creates purchase orders for products that have fallen below alert quantity.
 */
class AutoPurchase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'purchase:auto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatic purchase if the qty exceeds alert qty';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $products = Product::where('is_active', true)
            ->whereColumn('alert_quantity', '>', 'qty')
            ->whereNull(['is_variant', 'is_batch'])
            ->get();

        if ($products->isEmpty()) {
            $this->info('No products need automatic purchase.');
            return Command::SUCCESS;
        }

        $posSetting = DB::table('pos_setting')
            ->select('warehouse_id')
            ->latest()
            ->first();

        $user = DB::table('users')
            ->select('id')
            ->where([
                ['is_active', true],
                ['role_id', 1],
            ])
            ->first();

        if (!$posSetting || !$user) {
            $this->error('POS setting or admin user not found.');
            return Command::FAILURE;
        }

        $referenceNo = 'pr-' . date('Ymd') . '-' . date('his');
        $itemCount = $products->count();
        $totalQty = 10 * $itemCount;
        $totalDiscount = 0.0;
        $totalTax = 0.0;
        $totalCost = 0.0;
        $productData = [];

        foreach ($products as $key => $product) {
            $taxData = $product->tax_id
                ? DB::table('taxes')->select('rate')->find($product->tax_id)
                : null;

            if ($taxData) {
                if ($product->tax_method == 1) {
                    $netUnitCost = (float) number_format($product->cost, 2, '.', '');
                    $tax = (float) number_format($product->cost * 10 * ($taxData->rate / 100), 2, '.', '');
                    $cost = (float) number_format(($product->cost * 10) + $tax, 2, '.', '');
                } else {
                    $netUnitCost = (float) number_format((100 / (100 + $taxData->rate)) * $product->cost, 2, '.', '');
                    $tax = (float) number_format(($product->cost - $netUnitCost) * 10, 2, '.', '');
                    $cost = (float) number_format($product->cost * 10, 2, '.', '');
                }
                $taxRate = $taxData->rate;
                $totalTax += $tax;
                $totalCost += $cost;
            } else {
                $netUnitCost = (float) number_format($product->cost, 2, '.', '');
                $taxRate = 0.0;
                $tax = 0.0;
                $cost = (float) number_format($product->cost * 10, 2, '.', '');
                $totalTax += 0.0;
                $totalCost += $cost;
            }

            $productData[] = [
                'product_id' => $product->id,
                'unit_id' => $product->unit_id,
                'net_unit_cost' => $netUnitCost,
                'tax_rate' => $taxRate,
                'tax' => $tax,
                'total' => $cost,
            ];

            // Update product warehouse
            $productWarehouse = ProductWarehouse::where([
                ['product_id', $product->id],
                ['warehouse_id', $posSetting->warehouse_id],
            ])->first();

            if ($productWarehouse) {
                $productWarehouse->qty += 10;
                $productWarehouse->save();
            } else {
                ProductWarehouse::create([
                    'product_id' => $product->id,
                    'warehouse_id' => $posSetting->warehouse_id,
                    'qty' => 10,
                ]);
            }

            // Update product quantity
            $product->qty += 10;
            $product->save();
        }

        $purchase = Purchase::create([
            'reference_no' => $referenceNo,
            'user_id' => $user->id,
            'warehouse_id' => $posSetting->warehouse_id,
            'item' => $itemCount,
            'total_qty' => $totalQty,
            'total_discount' => $totalDiscount,
            'total_tax' => $totalTax,
            'total_cost' => $totalCost,
            'order_tax' => 0,
            'grand_total' => $totalCost,
            'paid_amount' => 0,
            'status' => '1',
            'payment_status' => '1',
        ]);

        foreach ($productData as $data) {
            ProductPurchase::create([
                'purchase_id' => $purchase->id,
                'product_id' => $data['product_id'],
                'qty' => 10,
                'recieved' => 10,
                'purchase_unit_id' => $data['unit_id'],
                'net_unit_cost' => $data['net_unit_cost'],
                'discount' => 0,
                'tax_rate' => $data['tax_rate'],
                'tax' => $data['tax'],
                'total' => $data['total'],
            ]);
        }

        $this->info("Successfully created automatic purchase for {$itemCount} products.");
        return Command::SUCCESS;
    }
}

