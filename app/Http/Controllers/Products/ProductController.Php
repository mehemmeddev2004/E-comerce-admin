<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    // Product-ları və ya tək product-u götür
    public function get(?int $id = null)
    {
        if ($id) {
            $product = Product::with('variants', 'specs.values', 'category')->find($id);

            if (!$product) {
                return response()->json([
                    'message' => 'Product not found'
                ], 404);
            }

            return response()->json([
                'message' => 'Product fetched successfully!',
                'data' => $product
            ], 200);
        }

        $products = Product::with('variants', 'specs.values', 'category')->get();

        return response()->json([
            'message' => 'All products fetched successfully!',
            'data' => $products
        ], 200);
    }

    // Yeni product yarat
    public function create(Request $request)
    {
        $credentials = $request->validate([
            'slug' => ['required', 'string', 'unique:products,slug'],
            'name' => ['required', 'array'],
            'name.az' => ['nullable', 'string'],
            'name.en' => ['nullable', 'string'],
            'name.ru' => ['nullable', 'string'],

            'description' => ['nullable', 'array'],
            'description.az' => ['nullable', 'string'],
            'description.en' => ['nullable', 'string'],
            'description.ru' => ['nullable', 'string'],

            'price' => ['required', 'numeric'],
            'stock' => ['required', 'integer'],
            'brand' => ['required', 'string'],
            'discount' => ['nullable', 'numeric'],
            'isActive' => ['nullable', 'boolean'],
            'isNew' => ['nullable', 'boolean'],
            'categoryId' => ['nullable', 'integer', 'exists:categories,id'],

            'img' => ['nullable', 'string'],
            'images' => ['nullable', 'array'],
            'images.*' => ['string'],

            'sizes' => ['nullable', 'array'],
            'sizes.*.id' => ['nullable', 'string'],
            'sizes.*.name' => ['nullable', 'string'],
            'sizes.*.value' => ['nullable', 'string'],

            'colors' => ['nullable', 'array'],
            'colors.*.id' => ['nullable', 'string'],
            'colors.*.name' => ['nullable', 'string'],
            'colors.*.value' => ['nullable', 'string'],

            'specs' => ['nullable', 'array'],
            'specs.*.key' => ['required_with:specs', 'string'],
            'specs.*.name' => ['required_with:specs', 'string'],
            'specs.*.values' => ['nullable', 'array'],
            'specs.*.values.*.key' => ['required_with:specs.*.values', 'string'],
            'specs.*.values.*.value' => ['required_with:specs.*.values', 'string'],

            'variants' => ['nullable', 'array'],
            'variants.*.slug' => ['required_with:variants', 'string'],
            'variants.*.price' => ['required_with:variants', 'numeric'],
            'variants.*.stock' => ['required_with:variants', 'integer'],
            'variants.*.discount' => ['nullable', 'numeric'],
            'variants.*.images' => ['nullable', 'array'],
            'variants.*.images.*' => ['string'],
            'variants.*.specs' => ['nullable', 'array'],
            'variants.*.specs.*.key' => ['required_with:variants.*.specs', 'string'],
            'variants.*.specs.*.value' => ['required_with:variants.*.specs', 'string'],
        ]);

        // Əsas product-u yarat
        $product = Product::create([
            'slug' => $credentials['slug'],
            'name' => $credentials['name'],
            'description' => $credentials['description'] ?? null,
            'price' => $credentials['price'],
            'stock' => $credentials['stock'],
            'brand' => $credentials['brand'],
            'discount' => $credentials['discount'] ?? null,
            'is_active' => $credentials['isActive'] ?? true,
            'is_new' => $credentials['isNew'] ?? false,
            'category_id' => $credentials['categoryId'] ?? null,
            'image_url' => $credentials['img'] ?? null,
            'images' => $credentials['images'] ?? null,
        ]);

        // Specs əlavə et
        if (!empty($credentials['specs'])) {
            foreach ($credentials['specs'] as $spec) {
                $s = $product->specs()->create([
                    'key' => $spec['key'],
                    'name' => $spec['name']
                ]);

                if (!empty($spec['values'])) {
                    foreach ($spec['values'] as $val) {
                        $s->values()->create([
                            'key' => $val['key'],
                            'value' => $val['value']
                        ]);
                    }
                }
            }
        }

        // Variants əlavə et
        if (!empty($credentials['variants'])) {
            foreach ($credentials['variants'] as $variant) {
                $v = $product->variants()->create([
                    'slug' => $variant['slug'],
                    'price' => $variant['price'],
                    'stock' => $variant['stock'],
                    'discount' => $variant['discount'] ?? null,
                    'images' => $variant['images'] ?? null,
                ]);

                if (!empty($variant['specs'])) {
                    foreach ($variant['specs'] as $vspec) {
                        $v->specs()->create([
                            'key' => $vspec['key'],
                            'value' => $vspec['value']
                        ]);
                    }
                }
            }
        }

        return response()->json([
            'message' => 'Product created successfully!',
            'data' => $product->load('variants', 'specs.values', 'category')
        ], 201);
    }

    // Product-u update et
    public function update(Request $request, int $id)
    {
        $product = Product::with('variants', 'specs.values')->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $credentials = $request->validate([
            'slug' => ['sometimes', 'string', 'unique:products,slug,' . $id],
            'name' => ['sometimes', 'array'],
            'name.az' => ['nullable', 'string'],
            'name.en' => ['nullable', 'string'],
            'name.ru' => ['nullable', 'string'],

            'description' => ['nullable', 'array'],
            'description.az' => ['nullable', 'string'],
            'description.en' => ['nullable', 'string'],
            'description.ru' => ['nullable', 'string'],

            'price' => ['sometimes', 'numeric'],
            'stock' => ['sometimes', 'integer'],
            'brand' => ['sometimes', 'string'],
            'discount' => ['nullable', 'numeric'],
            'isActive' => ['nullable', 'boolean'],
            'isNew' => ['nullable', 'boolean'],
            'categoryId' => ['nullable', 'integer', 'exists:categories,id'],
        ]);

        // Map field names
        $updateData = [];
        if (isset($credentials['name'])) $updateData['name'] = $credentials['name'];
        if (isset($credentials['description'])) $updateData['description'] = $credentials['description'];
        if (isset($credentials['slug'])) $updateData['slug'] = $credentials['slug'];
        if (isset($credentials['price'])) $updateData['price'] = $credentials['price'];
        if (isset($credentials['stock'])) $updateData['stock'] = $credentials['stock'];
        if (isset($credentials['brand'])) $updateData['brand'] = $credentials['brand'];
        if (isset($credentials['discount'])) $updateData['discount'] = $credentials['discount'];
        if (isset($credentials['isActive'])) $updateData['is_active'] = $credentials['isActive'];
        if (isset($credentials['isNew'])) $updateData['is_new'] = $credentials['isNew'];
        if (isset($credentials['categoryId'])) $updateData['category_id'] = $credentials['categoryId'];

        $product->update($updateData);

        return response()->json([
            'message' => 'Product updated successfully!',
            'data' => $product->load('variants', 'specs.values', 'category')
        ], 200);
    }

    
    public function delete(int $id)
    {
        $product = Product::with('variants', 'specs.values')->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Variantlar və specs-u da sil
        $product->variants()->delete();
        foreach ($product->specs as $spec) {
            $spec->values()->delete();
        }
        $product->specs()->delete();

        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully!'
        ], 200);
    }
}
