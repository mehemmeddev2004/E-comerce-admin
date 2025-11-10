<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\Product;
use Inertia\Inertia;
use Illuminate\Support\Str;

class WebProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index()
    {
        $products = Product::with('category')->latest()->get()->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => is_array($product->name) ? ($product->name['az'] ?? $product->name['en'] ?? '') : $product->name,
                'slug' => $product->slug,
                'brand' => $product->brand,
                'price' => $product->price,
                'stock' => $product->stock,
                'discount' => $product->discount,
                'is_active' => $product->is_active,
                'is_new' => $product->is_new,
                'image_url' => $product->image_url,
                'category' => $product->category,
            ];
        });

        // Log products for debugging
        \Log::info('Products data:', ['products' => $products->toArray()]);

        return Inertia::render('products/index', [
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = \App\Models\Category::all();
        
        return Inertia::render('products/create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store product from web form.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'is_new' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'], // 2MB max
        ]);

        // Generate slug if not provided
        $slug = $data['slug'] ?? Str::slug($data['name']);
        
        // Ensure slug is unique
        $originalSlug = $slug;
        $counter = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Handle image upload
        $imageUrl = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $slug . '.' . $image->getClientOriginalExtension();
            $image->storeAs('products', $imageName, 'public');
            $imageUrl = '/storage/products/' . $imageName;
        }

        $product = Product::create([
            'name' => json_encode([
                'az' => $data['name'],
                'en' => $data['name'],
                'ru' => $data['name'],
            ]),
            'slug' => $slug,
            'brand' => $data['brand'],
            'price' => $data['price'],
            'stock' => $data['stock'] ?? 0,
            'discount' => $data['discount'] ?? 0,
            'category_id' => $data['category_id'] ?? null,
            'description' => isset($data['description']) ? json_encode([
                'az' => $data['description'],
                'en' => $data['description'],
                'ru' => $data['description'],
            ]) : null,
            'is_active' => $data['is_active'] ?? true,
            'is_new' => $data['is_new'] ?? false,
            'image_url' => $imageUrl,
        ]);

        return Redirect::route('products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return Redirect::route('products.index')->with('success', 'Product deleted successfully.');
    }
}
