<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Startup\TaxController;
use App\Http\Controllers\Startup\UnitController;
use App\Http\Controllers\Startup\UserController;
use App\Http\Controllers\Startup\BrandController;
use App\Http\Controllers\Startup\PartyController;
use App\Http\Controllers\Startup\CategoryController;
use App\Http\Controllers\Startup\CompanyController;
use App\Http\Controllers\Startup\WarehouseController;
use App\Http\Controllers\Startup\PartyWarehouseController;


// Super Admin and Admin Access
Route::middleware(['auth','role:super-admin|admin'])->group(function(){
    Route::resource('brand', BrandController::class)->except(['destroy']);
    Route::resource('category', CategoryController::class)->except(['destroy']);
    Route::resource('tax', TaxController::class)->except(['destroy']);
    Route::resource('warehouse', WarehouseController::class)->except(['destroy']);
    Route::resource('unit', UnitController::class)->except(['destroy']);
    Route::resource('party', PartyController::class)->except(['destroy']);
    Route::resource('party-warehouse', PartyWarehouseController::class)->except(['destroy']);
    Route::resource('company-info', CompanyController::class);

});

// Only Super Admin Access
Route::middleware(['auth','role:super-admin'])->group(function(){
    Route::delete('brand/{brand}', [BrandController::class, 'destroy'])->name('brand.destroy');
    Route::delete('category/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');
    Route::delete('tax/{tax}', [TaxController::class, 'destroy'])->name('tax.destroy');
    Route::delete('warehouse/{warehouse}', [WarehouseController::class, 'destroy'])->name('warehouse.destroy'); 
    Route::delete('unit/{unit}', [UnitController::class, 'destroy'])->name('unit.destroy');
   
    Route::resource('user', UserController::class);
    
    Route::delete('party/{party}', [PartyController::class, 'destroy'])->name('party.destroy');
    Route::delete('party-warehouse/{party_warehouse}', [PartyWarehouseController::class, 'destroy'])->name('party-warehouse.destroy');

});

// Auth User Access
Route::middleware(['auth'])->group(function(){


    Route::get('user/download-sample-file', [UserController::class, 'downloadSampleFile'])->name('user.downloadSampleFile');
    Route::post('user/upload-file', [UserController::class, 'uploadFile'])->name('user.uploadFile');

    Route::get('party-index/{type?}', [PartyController::class, 'index'])->name('party-index');
    Route::get('fetch-customer-list', [PartyController::class, 'fetchCustomerList'])->name('party.fetchCustomerList');

    Route::get('party-warehouse/fetch-list/{party_id}', [PartyWarehouseController::class, 'fetchList'])->name('party-warehouse.fetchList');

});