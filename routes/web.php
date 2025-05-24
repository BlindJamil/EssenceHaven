<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminProductController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ProductController::class, 'index'])->name('shop');
Route::get('/shop/category/{category:slug}', [ProductController::class, 'category'])->name('shop.category');
Route::get('/product/{product:slug}', [ProductController::class, 'show'])->name('product.show'); // Changed to use product:slug

// Admin Product Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Redirect /admin to products index
    Route::get('/', function () {
        return redirect()->route('admin.products.index');
    })->name('dashboard'); // or simply name it 'index' if it's the main admin page

    Route::resource('products', AdminProductController::class)->except(['show']); // No separate show view for admin
});

// If you installed Breeze and want to keep its auth routes (for potential admin login later)
// Otherwise, you can remove these if no auth is needed at all.
// Make sure to remove the '/dashboard' route below if you remove auth.php.
if (file_exists(__DIR__.'/auth.php')) {
    require __DIR__.'/auth.php';

    // A simple dashboard route for authenticated users (if Breeze was installed)
    // You might want to redirect authenticated users to admin or remove this if not using Breeze auth
    Route::get('/dashboard', function () {
        // For now, let's redirect to admin products as a simple "dashboard"
        // Or, if you want a general user dashboard, keep as Breeze default or create a new view.
        // return redirect()->route('admin.products.index');
        return view('dashboard'); // Assuming Breeze created a dashboard view
    })->middleware(['auth', 'verified'])->name('dashboard');
}