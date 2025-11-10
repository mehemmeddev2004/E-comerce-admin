<?php

use App\Http\Controllers\Profile\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        $profile = \App\Models\Profile::where('user_id', auth()->id())->first();
        
        return Inertia::render('dashboard', [
            'profile' => $profile,
        ]);
    })->name('dashboard');

    // Categories
    Route::get('categories', [\App\Http\Controllers\Category\WebCategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/create', [\App\Http\Controllers\Category\WebCategoryController::class, 'create'])->name('categories.create');
    Route::post('categories', [\App\Http\Controllers\Category\WebCategoryController::class, 'store'])->name('categories.store');
    Route::get('categories/{category}/edit', [\App\Http\Controllers\Category\WebCategoryController::class, 'edit'])->name('categories.edit');
    Route::put('categories/{category}', [\App\Http\Controllers\Category\WebCategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/{category}', [\App\Http\Controllers\Category\WebCategoryController::class, 'destroy'])->name('categories.destroy');

    // Products
    Route::get('products', [\App\Http\Controllers\Products\WebProductController::class, 'index'])->name('products.index');
    Route::get('products/create', [\App\Http\Controllers\Products\WebProductController::class, 'create'])->name('products.create');
    Route::post('products', [\App\Http\Controllers\Products\WebProductController::class, 'store'])->name('products.store');
    Route::get('products/{product}/edit', [\App\Http\Controllers\Products\WebProductController::class, 'edit'])->name('products.edit');
    Route::put('products/{product}', [\App\Http\Controllers\Products\WebProductController::class, 'update'])->name('products.update');
    Route::delete('products/{product}', [\App\Http\Controllers\Products\WebProductController::class, 'destroy'])->name('products.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile/{id?}', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'create']);
    Route::put('/profile/{id?}', [ProfileController::class, 'update']);
    Route::delete('/profile/{id?}', [ProfileController::class, 'delete']);
});

require __DIR__.'/auth.php';
require __DIR__.'/settings.php';
