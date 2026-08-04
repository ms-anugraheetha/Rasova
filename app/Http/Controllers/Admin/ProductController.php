<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductController extends Controller
{
    /**
     * Resize (if needed) and re-compress an uploaded product photo before
     * storing it — uploads come from real cameras/phones with no size
     * discipline, unlike the hand-optimized static site images, so this
     * needs to happen automatically on every upload rather than as a
     * one-off manual cleanup.
     */
    protected function processAndStoreProductImage(\Illuminate\Http\UploadedFile $file): string
    {
        $manager = new ImageManager(Driver::class);
        $image = $manager->decodePath($file->getRealPath());

        if ($image->width() > 1600) {
            $image->scale(width: 1600);
        }

        $filename = 'products/' . Str::random(40) . '.jpg';
        $encoded = $image->encode(new \Intervention\Image\Encoders\JpegEncoder(quality: 82));
        Storage::disk('public')->put($filename, (string) $encoded);

        return $filename;
    }
    public function index(Request $request)
    {
        $query = Product::query()->with(['category', 'variants']);

        if ($request->filled('search')) {
            $query->where('name', 'ilike', '%' . $request->input('search') . '%');
        }

        $products = $query->latest()->paginate(20)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_available' => 'nullable|boolean',
            'image' => 'nullable|image|max:4096',
            'weight' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $product = Product::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . Str::random(6),
            'description' => $validated['description'] ?? null,
            'is_available' => $request->boolean('is_available'),
        ]);

        if ($request->hasFile('image')) {
            $path = $this->processAndStoreProductImage($request->file('image'));
            ProductImage::create([
                'product_id' => $product->id,
                'image' => $path,
                'is_primary' => true,
            ]);
        }

        ProductVariant::create([
            'product_id' => $product->id,
            'weight' => $validated['weight'],
            'price_minor' => (int) round($validated['price'] * 100),
            'stock_quantity' => $validated['stock_quantity'],
            'is_active' => true,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product created.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $product->load(['images', 'variants']);

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_available' => 'nullable|boolean',
        ]);

        $product->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_available' => $request->boolean('is_available'),
        ]);

        return back()->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }

    public function uploadImage(Request $request, Product $product)
    {
        $validated = $request->validate([
            'image' => 'required|image|max:4096',
        ]);

        $path = $this->processAndStoreProductImage($request->file('image'));

        $isFirstImage = $product->images()->count() === 0;

        ProductImage::create([
            'product_id' => $product->id,
            'image' => $path,
            'is_primary' => $isFirstImage,
        ]);

        return back()->with('success', 'Image uploaded.');
    }

    public function deleteImage(ProductImage $image)
    {
        Storage::disk('public')->delete($image->image);
        $image->delete();

        return back()->with('success', 'Image deleted.');
    }
}