<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouse;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

/**
 * ProductController
 *
 * Handles product-related HTTP requests.
 */
class ProductController extends Controller
{
    /**
     * The product service instance.
     *
     * @var ProductService
     */
    private ProductService $productService;

    /**
     * Create a new controller instance.
     *
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of products.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $warehouses = Warehouse::where('is_active', true)->get();
        $warehouseId = $request->input('warehouse_id', 0);

        return Inertia::render('products/products', [
            'warehouses' => $warehouses,
            'warehouseId' => (int) $warehouseId,
        ]);
    }

    /**
     * Get products data for DataTable.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function productData(Request $request): JsonResponse
    {
        $data = $this->productService->getPaginatedProducts(
            (int) $request->input('length', 15),
            (int) $request->input('start', 0),
            (int) $request->input('order.0.column'),
            $request->input('order.0.dir', 'asc'),
            $request->input('search.value'),
            (int) $request->input('warehouse_id', 0)
        );

        return response()->json([
            'draw' => (int) $request->input('draw', 1),
            'recordsTotal' => $data['totalData'],
            'recordsFiltered' => $data['totalFiltered'],
            'data' => $data['products'],
        ]);
    }

    /**
     * Show the form for creating a new product.
     *
     * @return Response
     */
    public function create(): Response
    {
        $formData = $this->productService->getFormData();

        return Inertia::render('products/create', $formData);
    }

    /**
     * Store a newly created product.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => [
                'nullable',
                'max:255',
                Rule::unique('products')->where(function ($query) {
                    return $query->where('is_active', true);
                }),
            ],
            'name' => [
                'required',
                'max:255',
                Rule::unique('products')->where(function ($query) {
                    return $query->where('is_active', true);
                }),
            ],
            'type' => ['required', 'in:standard,combo,digital,service'],
            'barcode_symbology' => ['nullable', 'in:C128,C39,UPCA,UPCE,EAN8,EAN13'],
            'category_id' => ['required', 'exists:categories,id'],
            'unit_id' => ['nullable', 'exists:units,id'],
            'purchase_unit_id' => ['nullable', 'exists:units,id'],
            'sale_unit_id' => ['nullable', 'exists:units,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'tax_id' => ['nullable', 'exists:taxes,id'],
            'tax_method' => ['nullable', 'in:1,2'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'wholesale_price' => ['nullable', 'numeric', 'min:0'],
            'alert_quantity' => ['nullable', 'numeric', 'min:0'],
            'daily_sale_objective' => ['nullable', 'numeric', 'min:0'],
            'product_details' => ['nullable', 'string'],
            'warranty' => ['nullable', 'integer', 'min:1'],
            'warranty_type' => ['nullable', 'in:days,months,years'],
            'guarantee' => ['nullable', 'integer', 'min:1'],
            'guarantee_type' => ['nullable', 'in:days,months,years'],
            'featured' => ['nullable', 'boolean'],
            'is_embeded' => ['nullable', 'boolean'],
            'is_batch' => ['nullable', 'boolean'],
            'is_variant' => ['nullable', 'boolean'],
            'is_diffPrice' => ['nullable', 'boolean'],
            'is_imei' => ['nullable', 'boolean'],
            'promotion' => ['nullable', 'boolean'],
            'promotion_price' => ['nullable', 'numeric', 'min:0'],
            'starting_date' => ['nullable', 'date'],
            'last_date' => ['nullable', 'date', 'after_or_equal:starting_date'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,zip,rar'],
        ]);

        $product = $this->productService->createProduct($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Show the form for editing the specified product.
     *
     * @param int $id
     * @return Response
     */
    public function edit(int $id): Response
    {
        $product = $this->productService->getProductById($id);

        if (!$product) {
            abort(404, 'Product not found');
        }

        $formData = $this->productService->getFormData();
        $formData['product'] = $product;

        return Inertia::render('products/edit', $formData);
    }

    /**
     * Update the specified product.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'code' => [
                'nullable',
                'max:255',
                Rule::unique('products')->where(function ($query) use ($id) {
                    return $query->where('is_active', true)->where('id', '!=', $id);
                }),
            ],
            'name' => [
                'required',
                'max:255',
                Rule::unique('products')->where(function ($query) use ($id) {
                    return $query->where('is_active', true)->where('id', '!=', $id);
                }),
            ],
            'type' => ['required', 'in:standard,combo,digital,service'],
            'barcode_symbology' => ['nullable', 'in:C128,C39,UPCA,UPCE,EAN8,EAN13'],
            'category_id' => ['required', 'exists:categories,id'],
            'unit_id' => ['nullable', 'exists:units,id'],
            'purchase_unit_id' => ['nullable', 'exists:units,id'],
            'sale_unit_id' => ['nullable', 'exists:units,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'tax_id' => ['nullable', 'exists:taxes,id'],
            'tax_method' => ['nullable', 'in:1,2'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'wholesale_price' => ['nullable', 'numeric', 'min:0'],
            'alert_quantity' => ['nullable', 'numeric', 'min:0'],
            'daily_sale_objective' => ['nullable', 'numeric', 'min:0'],
            'product_details' => ['nullable', 'string'],
            'warranty' => ['nullable', 'integer', 'min:1'],
            'warranty_type' => ['nullable', 'in:days,months,years'],
            'guarantee' => ['nullable', 'integer', 'min:1'],
            'guarantee_type' => ['nullable', 'in:days,months,years'],
            'featured' => ['nullable', 'boolean'],
            'is_embeded' => ['nullable', 'boolean'],
            'is_batch' => ['nullable', 'boolean'],
            'is_variant' => ['nullable', 'boolean'],
            'is_diffPrice' => ['nullable', 'boolean'],
            'is_imei' => ['nullable', 'boolean'],
            'promotion' => ['nullable', 'boolean'],
            'promotion_price' => ['nullable', 'numeric', 'min:0'],
            'starting_date' => ['nullable', 'date'],
            'last_date' => ['nullable', 'date', 'after_or_equal:starting_date'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,zip,rar'],
        ]);

        $product = $this->productService->updateProduct($id, $validated);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->productService->deleteProduct($id);

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * Generate a unique product code.
     *
     * @return string
     */
    public function generateCode(): string
    {
        do {
            $code = (string) random_int(10000000, 99999999);
        } while (Product::where('code', $code)->where('is_active', true)->exists());

        return $code;
    }
}

