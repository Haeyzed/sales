<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Brand;
use App\Models\Category;
use App\Models\CustomField;
use App\Models\Product;
use App\Models\ProductWarehouse;
use App\Models\Tax;
use App\Models\Unit;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * ProductService
 *
 * Service class for handling product-related business logic.
 */
class ProductService
{
    /**
     * Get paginated products with filters.
     *
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedProducts(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Product::with(['category', 'brand', 'unit'])
            ->where('is_active', true);

        // Filter by warehouse if provided
        if (isset($filters['warehouse_id']) && $filters['warehouse_id'] > 0) {
            $warehouseId = (int) $filters['warehouse_id'];
            $query->whereHas('warehouses', function ($q) use ($warehouseId): void {
                $q->where('warehouse_id', $warehouseId);
            });
        }

        // Search functionality
        if (isset($filters['search']) && !empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('code', 'LIKE', "%{$search}%")
                    ->orWhereHas('category', function ($q) use ($search): void {
                        $q->where('name', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('brand', function ($q) use ($search): void {
                        $q->where('title', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Sort
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        return $query->paginate($perPage);
    }

    /**
     * Get product by ID with relationships.
     *
     * @param int $id
     * @return Product|null
     */
    public function getProductById(int $id): ?Product
    {
        return Product::with(['category', 'brand', 'unit', 'tax', 'warehouses'])
            ->where('is_active', true)
            ->find($id);
    }

    /**
     * Create a new product.
     *
     * @param array<string, mixed> $data
     * @return Product
     */
    public function createProduct(array $data): Product
    {
        // Handle warranty and guarantee
        if (!isset($data['warranty']) || empty($data['warranty'])) {
            $data['warranty'] = null;
            $data['warranty_type'] = null;
        }
        if (!isset($data['guarantee']) || empty($data['guarantee'])) {
            $data['guarantee'] = null;
            $data['guarantee_type'] = null;
        }

        // Handle variant options
        if (isset($data['is_variant']) && $data['is_variant']) {
            $data['variant_option'] = json_encode(array_unique($data['variant_option'] ?? []));
            $data['variant_value'] = json_encode(array_unique($data['variant_value'] ?? []));
        } else {
            $data['variant_option'] = null;
            $data['variant_value'] = null;
            $data['is_variant'] = null;
        }

        // Handle combo products
        if (isset($data['type']) && $data['type'] === 'combo') {
            $data['product_list'] = implode(',', $data['product_id'] ?? []);
            $data['variant_list'] = implode(',', $data['variant_id'] ?? []);
            $data['qty_list'] = implode(',', $data['product_qty'] ?? []);
            $data['price_list'] = implode(',', $data['unit_price'] ?? []);
        }

        // Handle digital/service products
        if (isset($data['type']) && in_array($data['type'], ['digital', 'service'])) {
            $data['cost'] = 0;
            $data['unit_id'] = 0;
            $data['purchase_unit_id'] = 0;
            $data['sale_unit_id'] = 0;
        }

        // Handle boolean fields
        $data['featured'] = isset($data['featured']) ? (bool) $data['featured'] : false;
        $data['is_embeded'] = isset($data['is_embeded']) ? (bool) $data['is_embeded'] : false;
        $data['is_batch'] = isset($data['is_batch']) ? (bool) $data['is_batch'] : false;
        $data['is_diffPrice'] = isset($data['is_diffPrice']) ? (bool) $data['is_diffPrice'] : false;
        $data['is_imei'] = isset($data['is_imei']) ? (bool) $data['is_imei'] : false;
        $data['promotion'] = isset($data['promotion']) ? (bool) $data['promotion'] : null;

        // Handle promotion dates
        if (isset($data['promotion']) && $data['promotion']) {
            if (isset($data['starting_date'])) {
                $data['starting_date'] = date('Y-m-d', strtotime($data['starting_date']));
            }
            if (isset($data['last_date'])) {
                $data['last_date'] = date('Y-m-d', strtotime($data['last_date']));
            }
        } else {
            $data['starting_date'] = null;
            $data['last_date'] = null;
            $data['promotion_price'] = null;
        }

        // Set default barcode symbology if not provided
        if (!isset($data['barcode_symbology']) || empty($data['barcode_symbology'])) {
            $data['barcode_symbology'] = 'C128';
        }

        // Set default tax method if not provided
        if (!isset($data['tax_method'])) {
            $data['tax_method'] = 1; // 1 = Exclusive, 2 = Inclusive
        }

        // Clean product details
        if (isset($data['product_details'])) {
            $data['product_details'] = str_replace('"', '@', $data['product_details']);
        }

        $data['is_active'] = true;
        $data['name'] = preg_replace('/[\n\r]/', '<br>', htmlspecialchars(trim($data['name'] ?? ''), ENT_QUOTES));

        // Handle image
        if (!isset($data['image']) || empty($data['image'])) {
            $data['image'] = 'zummXD2dvAtI.png';
        }

        $product = Product::create($data);

        // Handle custom fields
        $this->saveCustomFields($product->id, $data);

        // Handle initial stock
        if (isset($data['is_initial_stock']) && !isset($data['is_variant']) && !isset($data['is_batch'])) {
            $this->handleInitialStock($product, $data);
        }

        // Handle different prices for different warehouses
        if (isset($data['is_diffPrice']) && $data['is_diffPrice'] && isset($data['warehouse_id']) && isset($data['diff_price'])) {
            $this->handleDifferentPrices($product, $data);
        }

        return $product;
    }

    /**
     * Update an existing product.
     *
     * @param int $id
     * @param array<string, mixed> $data
     * @return Product
     */
    public function updateProduct(int $id, array $data): Product
    {
        $product = Product::findOrFail($id);

        // Handle variant options
        if (isset($data['is_variant']) && $data['is_variant']) {
            $data['variant_option'] = json_encode(array_unique($data['variant_option'] ?? []));
            $data['variant_value'] = json_encode(array_unique($data['variant_value'] ?? []));
        } else {
            $data['variant_option'] = null;
            $data['variant_value'] = null;
        }

        // Handle combo products
        if (isset($data['type']) && $data['type'] === 'combo') {
            $data['product_list'] = implode(',', $data['product_id'] ?? []);
            $data['variant_list'] = implode(',', $data['variant_id'] ?? []);
            $data['qty_list'] = implode(',', $data['product_qty'] ?? []);
            $data['price_list'] = implode(',', $data['unit_price'] ?? []);
        }

        // Clean product details
        if (isset($data['product_details'])) {
            $data['product_details'] = str_replace('"', '@', $data['product_details']);
        }

        // Handle dates
        if (isset($data['starting_date'])) {
            $data['starting_date'] = date('Y-m-d', strtotime($data['starting_date']));
        }
        if (isset($data['last_date'])) {
            $data['last_date'] = date('Y-m-d', strtotime($data['last_date']));
        }

        $data['name'] = preg_replace('/[\n\r]/', '<br>', htmlspecialchars(trim($data['name'] ?? ''), ENT_QUOTES));

        $product->update($data);

        // Handle custom fields
        $this->saveCustomFields($product->id, $data);

        return $product->fresh();
    }

    /**
     * Delete (soft delete) a product.
     *
     * @param int $id
     * @return bool
     */
    public function deleteProduct(int $id): bool
    {
        $product = Product::findOrFail($id);
        $product->is_active = false;
        $product->is_deleted = true;
        return $product->save();
    }

    /**
     * Get product quantity in warehouse.
     *
     * @param int $productId
     * @param int|null $warehouseId
     * @return float
     */
    public function getProductQuantity(int $productId, ?int $warehouseId = null): float
    {
        $product = Product::find($productId);
        if (!$product) {
            return 0.0;
        }

        if ($product->type === 'standard') {
            if ($warehouseId) {
                $productWarehouse = ProductWarehouse::where('product_id', $productId)
                    ->where('warehouse_id', $warehouseId)
                    ->first();
                return $productWarehouse ? (float) $productWarehouse->qty : 0.0;
            } else {
                return (float) ProductWarehouse::where('product_id', $productId)->sum('qty');
            }
        }

        return (float) $product->qty;
    }

    /**
     * Get products for dropdown/select.
     *
     * @param bool $withVariant
     * @return Collection
     */
    public function getProductsForSelect(bool $withVariant = false): Collection
    {
        $query = Product::where('is_active', true)
            ->select('id', 'name', 'code', 'type', 'is_variant');

        if (!$withVariant) {
            $query->where('is_variant', false);
        }

        return $query->get();
    }

    /**
     * Get data for product creation/edit form.
     *
     * @return array<string, mixed>
     */
    public function getFormData(): array
    {
        return [
            'brands' => Brand::where('is_active', true)->get(),
            'categories' => Category::where('is_active', true)->get(),
            'units' => Unit::where('is_active', true)->get(),
            'taxes' => Tax::where('is_active', true)->get(),
            'warehouses' => Warehouse::where('is_active', true)->get(),
            'productsWithoutVariant' => $this->getProductsForSelect(false),
            'productsWithVariant' => $this->getProductsForSelect(true),
            'customFields' => CustomField::where('belongs_to', 'product')->get(),
            'numberOfProducts' => Product::where('is_active', true)->count(),
        ];
    }

    /**
     * Save custom fields for a product.
     *
     * @param int $productId
     * @param array<string, mixed> $data
     * @return void
     */
    private function saveCustomFields(int $productId, array $data): void
    {
        $customFields = CustomField::where('belongs_to', 'product')->select('name', 'type')->get();
        $customFieldData = [];

        foreach ($customFields as $customField) {
            $fieldName = str_replace(' ', '_', strtolower($customField->name));
            if (isset($data[$fieldName])) {
                if (in_array($customField->type, ['checkbox', 'multi_select'])) {
                    $customFieldData[$fieldName] = is_array($data[$fieldName])
                        ? implode(',', $data[$fieldName])
                        : $data[$fieldName];
                } else {
                    $customFieldData[$fieldName] = $data[$fieldName];
                }
            }
        }

        if (count($customFieldData) > 0) {
            DB::table('products')->where('id', $productId)->update($customFieldData);
        }
    }

    /**
     * Handle initial stock for a product.
     *
     * @param Product $product
     * @param array<string, mixed> $data
     * @return void
     */
    private function handleInitialStock(Product $product, array $data): void
    {
        if (isset($data['stock_warehouse_id']) && isset($data['stock'])) {
            foreach ($data['stock_warehouse_id'] as $key => $warehouseId) {
                $stock = (float) ($data['stock'][$key] ?? 0);
                if ($stock > 0) {
                    ProductWarehouse::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'warehouse_id' => $warehouseId,
                        ],
                        [
                            'qty' => $stock,
                        ]
                    );
                }
            }
        }
    }

    /**
     * Handle different prices for different warehouses.
     *
     * @param Product $product
     * @param array<string, mixed> $data
     * @return void
     */
    private function handleDifferentPrices(Product $product, array $data): void
    {
        foreach ($data['warehouse_id'] as $key => $warehouseId) {
            $diffPrice = $data['diff_price'][$key] ?? null;
            if ($diffPrice) {
                ProductWarehouse::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'warehouse_id' => $warehouseId,
                    ],
                    [
                        'price' => (float) $diffPrice,
                        'qty' => 0,
                    ]
                );
            }
        }
    }
}

