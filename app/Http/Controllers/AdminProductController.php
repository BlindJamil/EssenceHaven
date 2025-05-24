<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:products,name',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'volume' => 'nullable|string|max:50',
            'fragrance_notes' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'featured' => 'sometimes|boolean',
        ]);

        $validatedData['slug'] = Str::slug($request->name);
        $validatedData['featured'] = $request->boolean('featured'); // Correctly handle boolean

        if ($request->hasFile('image')) {
            // Store in public/images and save only filename or relative path from public/images
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $validatedData['image'] = $imageName; // Save only filename
        }

        Product::create($validatedData);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product): View
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:products,name,'.$product->id,
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'volume' => 'nullable|string|max:50',
            'fragrance_notes' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'featured' => 'sometimes|boolean',
        ]);

        $validatedData['slug'] = Str::slug($request->name);
        $validatedData['featured'] = $request->boolean('featured');

        if ($request->hasFile('image')) {
            // Delete old image if it exists and is not a placeholder name
            if ($product->image && file_exists(public_path('images/' . $product->image))) {
                 if ($product->image !== 'ocean-breeze.jpg' && $product->image !== 'midnight-rose.jpg' && $product->image !== 'citrus-horizon.jpg' && $product->image !== 'velvet-noir.jpg') { // Avoid deleting seed placeholders by name
                    unlink(public_path('images/' . $product->image));
                 }
            }
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $validatedData['image'] = $imageName;
        }

        $product->update($validatedData);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->image && file_exists(public_path('images/' . $product->image))) {
            // Avoid deleting seed placeholders by name, only delete if it's not one of them
            if ($product->image !== 'ocean-breeze.jpg' && $product->image !== 'midnight-rose.jpg' && $product->image !== 'citrus-horizon.jpg' && $product->image !== 'velvet-noir.jpg') {
                 unlink(public_path('images/' . $product->image));
            }
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}