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
     * Get paginated products with filters for DataTable.
     *
     * @param int $length
     * @param int $start
     * @param int|null $orderColumn
     * @param string $orderDir
     * @param string|null $search
     * @param int $warehouseId
     * @return array<string, mixed>
     */
    public function getPaginatedProducts(int $length, int $start, ?int $orderColumn, string $orderDir, ?string $search, int $warehouseId): array
    {
        $columns = [
            2 => 'name',
            3 => 'code',
            4 => 'brand_id',
            5 => 'category_id',
            6 => 'qty',
            7 => 'unit_id',
            8 => 'price',
            9 => 'cost',
            10 => 'stock_worth'
        ];

        $totalData = Product::where('is_active', true)->count();
        $totalFiltered = $totalData;

        $limit = $length == -1 ? $totalData : $length;
        $order = isset($columns[$orderColumn]) ? 'products.' . $columns[$orderColumn] : 'products.name';
        $dir = $orderDir ?: 'asc';

        // Fetch custom fields for table
        $customFields = \App\Models\CustomField::where([
            ['belongs_to', 'product'],
            ['is_table', true]
        ])->pluck('name');
        $fieldNames = [];
        foreach ($customFields as $fieldName) {
            $fieldNames[] = str_replace(' ', '_', strtolower($fieldName));
        }

        if (empty($search)) {
            $products = Product::with('category', 'brand', 'unit')
                ->where('is_active', true)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $q = Product::select('products.*')
                ->with('category', 'brand', 'unit')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->leftJoin('product_purchases', 'product_purchases.product_id', '=', 'products.id')
                ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
                ->leftJoin('product_variants', 'products.id', '=', 'product_variants.product_id')
                ->where([
                    ['products.name', 'LIKE', "%{$search}%"],
                    ['products.is_active', true]
                ])
                ->orWhere([
                    ['products.code', 'LIKE', "%{$search}%"],
                    ['products.is_active', true]
                ])
                ->orWhere([
                    ['product_variants.item_code', 'LIKE', "%{$search}%"],
                    ['products.is_active', true]
                ])
                ->orWhere([
                    ['categories.name', 'LIKE', "%{$search}%"],
                    ['categories.is_active', true],
                    ['products.is_active', true]
                ])
                ->orWhere([
                    ['brands.title', 'LIKE', "%{$search}%"],
                    ['brands.is_active', true],
                    ['products.is_active', true]
                ])
                ->orWhere([
                    ['product_purchases.imei_number', 'LIKE', "%{$search}%"],
                    ['products.is_active', true]
                ]);

            // Searching with custom field
            foreach ($fieldNames as $fieldName) {
                $q = $q->orWhere('products.' . $fieldName, 'LIKE', "%{$search}%");
            }

            $q = $q->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir);

            $products = $q->groupBy('products.id')->get();
            $totalFiltered = $q->groupBy('products.id')->count();
        }

        $data = [];
        foreach ($products as $key => $product) {
            $nestedData = [];
            $nestedData['id'] = $product->id;
            $nestedData['key'] = $key;

            // Handle image
            $productImage = explode(',', $product->image ?? '');
            $productImage = htmlspecialchars($productImage[0] ?? '');
            if ($productImage && $productImage != 'zummXD2dvAtI.png') {
                if (file_exists(public_path('images/product/small/' . $productImage))) {
                    $nestedData['image'] = asset('images/product/small/' . $productImage);
                } else {
                    $nestedData['image'] = asset('images/product/' . $productImage);
                }
            } else {
                $nestedData['image'] = asset('images/zummXD2dvAtI.png');
            }

            $nestedData['name'] = $product->name;
            $nestedData['code'] = $product->code;
            $nestedData['brand'] = $product->brand?->title ?? 'N/A';
            $nestedData['category'] = $product->category?->name ?? 'N/A';

            // Calculate quantity based on warehouse
            if ($warehouseId > 0 && $product->type == 'standard') {
                $nestedData['qty'] = ProductWarehouse::where([
                    ['product_id', $product->id],
                    ['warehouse_id', $warehouseId]
                ])->sum('qty');
            } elseif ($product->type == 'standard') {
                $nestedData['qty'] = ProductWarehouse::where('product_id', $product->id)->sum('qty');
            } else {
                $nestedData['qty'] = $product->qty;
            }

            $nestedData['unit'] = $product->unit?->unit_name ?? 'N/A';
            $nestedData['price'] = (float) $product->price;
            $nestedData['cost'] = (float) $product->cost;

            // Stock worth calculation
            $nestedData['stockWorth'] = number_format($nestedData['qty'] * $product->price, 2) . ' / ' . number_format($nestedData['qty'] * $product->cost, 2);

            // Fetching custom fields data
            foreach ($fieldNames as $fieldName) {
                $nestedData[$fieldName] = $product->$fieldName ?? '';
            }

            $data[] = $nestedData;
        }

        return [
            'products' => $data,
            'totalData' => $totalData,
            'totalFiltered' => $totalFiltered,
        ];
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
     * Get products without variant for dropdown/select.
     *
     * @return Collection
     */
    public function getProductsWithoutVariant(): Collection
    {
        return Product::where('is_active', true)
            ->where('type', 'standard')
            ->where(function ($query) {
                $query->whereNull('is_variant')
                    ->orWhere('is_variant', false);
            })
            ->select('id', 'name', 'code')
            ->get();
    }

    /**
     * Get products with variant for dropdown/select.
     *
     * @return Collection
     */
    public function getProductsWithVariant(): Collection
    {
        return Product::join('product_variants', 'products.id', '=', 'product_variants.product_id')
            ->where('products.is_active', true)
            ->where('products.type', 'standard')
            ->whereNotNull('products.is_variant')
            ->select('products.id', 'products.name', 'product_variants.item_code as code', 'product_variants.qty')
            ->orderBy('product_variants.position')
            ->get();
    }

    /**
     * Get data for product creation/edit form.
     *
     * @param Product|null $product
     * @return array<string, mixed>
     */
    public function getFormData(?Product $product = null): array
    {
        $formData = [
            'brands' => Brand::where('is_active', true)->get(),
            'categories' => Category::where('is_active', true)->get(),
            'units' => Unit::where('is_active', true)->get(),
            'taxes' => Tax::where('is_active', true)->get(),
            'warehouses' => Warehouse::where('is_active', true)->get(),
            'productsWithoutVariant' => $this->getProductsWithoutVariant(),
            'productsWithVariant' => $this->getProductsWithVariant(),
            'customFields' => CustomField::where('belongs_to', 'product')->get(),
            'numberOfProducts' => Product::where('is_active', true)->count(),
        ];

        if ($product) {
            $formData['product'] = $product;
        }

        return $formData;
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

