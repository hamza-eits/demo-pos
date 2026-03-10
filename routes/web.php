<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboard;
use App\Http\Controllers\TestController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\ArtisanController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\VariationController;
use App\Http\Controllers\CustomFieldController;
use App\Http\Controllers\ProductGroupController;
use App\Http\Controllers\ProductModelController;
use App\Http\Controllers\PurchaseInvoiceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/blank', function () {
    
    return view('blank');
});
Route::get('/', function () {
    return redirect('login');
});
Route::get('/artisan', ArtisanController::class);

Route::get('/test', TestController::class);

// Super Admin and Admin Access
Route::middleware(['auth','role:super-admin|admin'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('product/export-csv', [ProductController::class, 'exportCsv'])->name('product.export-csv');

    Route::get('/recipes/{id}/detail-with-stock', [RecipeController::class, 'getRecipeDetailWithStock'])->name('getRecipeDetailWithStock');
    Route::get('recipe/create-version/{id}', [RecipeController::class, 'createVersion'])->name('recipe.createVersion');
    
    
    
    Route::post('purchase-invoice/lock/{id}', [PurchaseInvoiceController::class, 'lock'])
        ->name('purchase-invoice.lock');    
    Route::get('purchase-invoice/barcode-pdf/{id}/{download?}/{product_variation_id?}/{is_single?}', 
    [PurchaseInvoiceController::class, 'barcodePdf'])->name('purchase-invoice.barcode-pdf');
    
    Route::get('admin-dashboard', AdminDashboard::class)->name('admin-dashboard');

    Route::resource('purchase-invoice', PurchaseInvoiceController::class)->except(['destroy']);
    Route::resource('variation', VariationController::class)->except(['destroy']);
    Route::resource('product-model', ProductModelController::class)->except(['destroy']);
    Route::resource('custom-field', CustomFieldController::class)->except(['destroy']);
    Route::resource('product-group', ProductGroupController::class)->except(['destroy']);
    Route::resource('material', MaterialController::class)->except(['destroy']);
    Route::resource('recipe', RecipeController::class)->except(['destroy']);
    Route::resource('product', ProductController::class)->except(['destroy']);
});



// Only Super Admin Access
Route::middleware(['auth','role:super-admin'])->group(function () {
    Route::delete('purchase-invoice/{purchase_invoice}', [PurchaseInvoiceController::class, 'destroy'])
        ->name('purchase-invoice.destroy');

    Route::delete('variation/{variation}', [VariationController::class, 'destroy'])
        ->name('variation.destroy');

    Route::delete('product-model/{product_model}', [ProductModelController::class, 'destroy'])
        ->name('product-model.destroy');

    Route::delete('custom-field/{custom_field}', [CustomFieldController::class, 'destroy'])
        ->name('custom-field.destroy');

    Route::delete('product-group/{product_group}', [ProductGroupController::class, 'destroy'])
        ->name('product-group.destroy');

    Route::delete('material/{material}', [MaterialController::class, 'destroy'])
        ->name('material.destroy');

    Route::delete('recipe/{recipe}', [RecipeController::class, 'destroy'])
        ->name('recipe.destroy');

    Route::delete('product/{product}', [ProductController::class, 'destroy'])
        ->name('product.destroy');
});











require __DIR__ . '/auth.php';
require __DIR__ . '/startup.php';
require __DIR__ . '/accounting.php';
require __DIR__ . '/pos.php';
