<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * CategoryService
 *
 * Service class for handling category-related business logic.
 */
class CategoryService
{
    /**
     * Get paginated categories with filters.
     *
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedCategories(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Category::with('parent')
            ->where('is_active', true);

        // Search functionality
        if (isset($filters['search']) && !empty($filters['search'])) {
            $search = $filters['search'];
            $query->where('name', 'LIKE', "%{$search}%");
        }

        // Sort
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        return $query->paginate($perPage);
    }

    /**
     * Get all active categories.
     *
     * @return Collection
     */
    public function getAllCategories(): Collection
    {
        return Category::where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    /**
     * Get category by ID.
     *
     * @param int $id
     * @return Category|null
     */
    public function getCategoryById(int $id): ?Category
    {
        return Category::where('is_active', true)->find($id);
    }

    /**
     * Create a new category.
     *
     * @param array<string, mixed> $data
     * @return Category
     */
    public function createCategory(array $data): Category
    {
        $data['is_active'] = true;
        return Category::create($data);
    }

    /**
     * Update an existing category.
     *
     * @param int $id
     * @param array<string, mixed> $data
     * @return Category
     */
    public function updateCategory(int $id, array $data): Category
    {
        $category = Category::findOrFail($id);
        $category->update($data);
        return $category->fresh();
    }

    /**
     * Delete (soft delete) a category.
     *
     * @param int $id
     * @return bool
     */
    public function deleteCategory(int $id): bool
    {
        $category = Category::findOrFail($id);
        $category->is_active = false;
        $category->is_deleted = true;
        return $category->save();
    }
}
