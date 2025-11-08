<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

/**
 * CategoryController
 *
 * Handles category-related HTTP requests.
 */
class CategoryController extends Controller
{
    /**
     * The category service instance.
     *
     * @var CategoryService
     */
    private CategoryService $categoryService;

    /**
     * Create a new controller instance.
     *
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of categories.
     *
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('products/categories/index');
    }

    /**
     * Get categories data for DataTable.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function categoryData(Request $request): JsonResponse
    {
        $filters = [
            'search' => $request->input('search.value'),
            'sort_by' => $request->input('order.0.column', 'created_at'),
            'sort_order' => $request->input('order.0.dir', 'desc'),
        ];

        $perPage = $request->input('length', 15);
        $categories = $this->categoryService->getPaginatedCategories($filters, $perPage);

        $data = [];
        foreach ($categories->items() as $key => $category) {
            $data[] = [
                'id' => $category->id,
                'key' => $key,
                'name' => $category->name,
                'parent_id' => $category->parent?->name ?? 'N/A',
                'number_of_product' => $category->products()->where('is_active', true)->count(),
                'stock_qty' => $category->products()->where('is_active', true)->sum('qty'),
            ];
        }

        return response()->json([
            'draw' => (int) $request->input('draw', 1),
            'recordsTotal' => $categories->total(),
            'recordsFiltered' => $categories->total(),
            'data' => $data,
        ]);
    }

    /**
     * Show the form for creating a new category.
     *
     * @return Response
     */
    public function create(): Response
    {
        $categories = $this->categoryService->getAllCategories();
        return Inertia::render('products/categories/create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created category.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories')->where(function ($query) {
                return $query->where('is_active', true);
            })],
            'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $category = $this->categoryService->createCategory($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing the specified category.
     *
     * @param int $id
     * @return Response
     */
    public function edit(int $id): Response
    {
        $category = $this->categoryService->getCategoryById($id);
        if (!$category) {
            abort(404, 'Category not found');
        }

        $categories = $this->categoryService->getAllCategories();
        return Inertia::render('products/categories/edit', [
            'category' => $category,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified category.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories')->where(function ($query) use ($id) {
                return $query->where('is_active', true)->where('id', '!=', $id);
            })],
            'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $category = $this->categoryService->updateCategory($id, $validated);

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->categoryService->deleteCategory($id);
        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
