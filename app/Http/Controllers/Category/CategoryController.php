<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Bütün və ya tək kateqoriyanı gətir
     */
    public function get(?int $id = null): JsonResponse
    {
        if ($id) {
            $category = Category::with('children', 'parent')->find($id);

            if (!$category) {
                return response()->json([
                    'message' => 'Category not found'
                ], 404);
            }

            return response()->json([
                'message' => 'Category fetched successfully!',
                'data' => $category
            ], 200);
        }

        $categories = Category::with('children', 'parent')->get();

        return response()->json([
            'message' => 'All categories fetched successfully!',
            'data' => $categories
        ], 200);
    }

    /**
     * Yeni kateqoriya yarat
     */
    public function create(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'name' => ['required', 'string', 'unique:categories,name'],
            'slug' => ['required', 'string', 'min:8'],
            'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
        ]);

        $result = Category::create([
            'name' => $credentials['name'],
            'slug' => $credentials['slug'],
            'parent_id' => $credentials['parent_id'] ?? null,
        ]);

        return response()->json([
            'message' => 'Category created successfully!',
            'data' => $result
        ], 201);
    }

    /**
     * Mövcud kateqoriyanı yenilə
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }

     
        $credentials = $request->validate([
            'name' => ['required', 'string', 'unique:categories,name,' . $id],
            'slug' => ['required', 'string', 'min:8'],
            'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
        ]);

        // Update record
        $category->update([
            'name' => $credentials['name'],
            'slug' => $credentials['slug'],
            'parent_id' => $credentials['parent_id'] ?? null,
        ]);

        return response()->json([
            'message' => 'Category updated successfully!',
            'data' => $category
        ], 200);
    }

    /**
     * Kateqoriyanı sil
     */
    public function delete(int $id): JsonResponse
    {
        $category = Category::with('children')->find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }

       
        if ($category->children->count() > 0) {
            $category->children()->delete();
        }

        // Əsas kateqoriyanı sil
        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully!'
        ], 200);
    }
}
