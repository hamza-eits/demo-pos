<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PosController;
use App\Http\Controllers\PosReportsController;
use App\Http\Controllers\CashRegisterController;
use App\Http\Controllers\PosSettingController;


// Only Super Admin Access
Route::middleware(['auth','role:super-admin'])->group(function(){
    Route::delete('point-of-sale/{point_of_sale}', [PosController::class, 'destroy'])->name('point-of-sale.destroy');
    Route::resource('pos-setting', PosSettingController::class);

});

// Super Admin and Admin Access
Route::middleware(['auth','role:super-admin|admin'])->group(function(){

    Route::get('point-of-sale', [PosController::class, 'index'])->name('point-of-sale.index');

    Route::get('pos-reports/sales-summary-request', [PosReportsController::class, 'salesSummaryRequest'])->name('pos-reports.salesSummaryRequest');
    Route::post('pos-reports/sales-summary-show', [PosReportsController::class, 'salesSummaryShow'])->name('pos-reports.salesSummaryShow');


    Route::get('pos-reports/payment-source-summary-request', [PosReportsController::class, 'salesSummaryRequest'])->name('pos-reports.salesSummaryRequest');
    Route::post('pos-reports/payment-source-summary-show', [PosReportsController::class, 'salesSummaryShow'])->name('pos-reports.salesSummaryShow');

    Route::get('pos-reports/sales-summary-detail-request', [PosReportsController::class, 'paymentSourceSummaryRequest'])->name('pos-reports.paymentSourceSummaryRequest');
    Route::post('pos-reports/sales-summary-detail-show', [PosReportsController::class, 'paymentSourceSummaryShow'])->name('pos-reports.paymentSourceSummaryShow');

    Route::get('pos-reports/tax-summary-request', [PosReportsController::class, 'taxSummaryRequest'])->name('pos-reports.taxSummaryRequest');
    Route::post('pos-reports/tax-summary-show', [PosReportsController::class, 'taxSummaryShow'])->name('pos-reports.taxSummaryShow');

    Route::get('pos-reports/x-report-request', [PosReportsController::class, 'xReportRequest'])->name('pos-reports.xReportRequest');
    Route::post('pos-reports/x-report-show', [PosReportsController::class, 'xReportShow'])->name('pos-reports.xReportShow');

    Route::get('pos-reports/z-report-request', [PosReportsController::class, 'zReportRequest'])->name('pos-reports.zReportRequest');
    Route::post('pos-reports/z-report-show', [PosReportsController::class, 'zReportShow'])->name('pos-reports.zReportShow');

    Route::get('pos-reports/item-wise-sales-summary-request', [PosReportsController::class, 'itemWiseSalesSummaryRequest'])->name('pos-reports.itemWiseSalesSummaryRequest');
    Route::post('pos-reports/item-wise-sales-summary-show', [PosReportsController::class, 'itemWiseSalesSummaryShow'])->name('pos-reports.itemWiseSalesSummaryShow');


    Route::get('pos-reports/inventory-summary-request', [PosReportsController::class, 'inventorySummaryRequest'])->name('pos-reports.inventorySummaryRequest');
    Route::post('pos-reports/inventory-summary-show', [PosReportsController::class, 'inventorySummaryShow'])->name('pos-reports.inventorySummaryShow');

    Route::get('pos-reports/purchase-summary-request', [PosReportsController::class, 'purchaseSummaryRequest'])->name('pos-reports.purchaseSummaryRequest');
    Route::post('pos-reports/purchase-summary-show', [PosReportsController::class, 'purchaseSummaryShow'])->name('pos-reports.purchaseSummaryShow');

    Route::get('pos-reports/inventory-detail-summary-request', [PosReportsController::class, 'inventoryDetailSummaryRequest'])->name('pos-reports.inventoryDetailSummaryRequest');
    Route::post('pos-reports/inventory-detail-summary-show', [PosReportsController::class, 'inventoryDetailSummaryShow'])->name('pos-reports.inventoryDetailSummaryShow');
});



// Auth User Access
Route::middleware(['auth', 'check.cash.register.status'])->group(function () {
    Route::post('cash-register/close', [CashRegisterController::class, 'close'])->name('cash-register.close');
    Route::get('pos/fetch-cash-register-summary', [POSController::class, 'fetchCashRegisterSummary'])->name('pos.fetchCashRegisterSummary');
    Route::resource('point-of-sale', PosController::class)->except(['index','destroy']);
    Route::post('pos/expense', [PosController::class, 'storeExpense'])->name('pos.storeExpense');


});
// Auth User Access
Route::middleware(['auth'])->group(function(){
    Route::resource('cash-register', CashRegisterController::class);

    Route::get('pos/fetch-products-list', [PosController::class, 'fetchProducts'])->name('pos.fetchProducts');
    Route::get('pos/search-purchase-wise-product-variation', [PosController::class, 'searchPurchaseWiseProductVariation'])->name('pos.searchPurchaseWiseProductVariation');
    Route::get('pos/search-product-variation-inventroy', [PosController::class, 'searchProductVariationInventory'])->name('pos.searchProductVariationInventory');

    Route::get('pos/fetch-product-variations', [PosController::class, 'fetchProductVariations'])->name('pos.fetchProductVariations');

    Route::get('pos/fetch-today-completed-orders', [PosController::class, 'fetchTodayCompletedOrders'])->name('pos.fetchTodayCompletedOrders');
    Route::get('pos/fetch-today-draft-orders', [PosController::class, 'fetchTodayDraftOrders'])->name('pos.fetchTodayDraftOrders');

    Route::get('pos/print-invoice/{id}', [PosController::class, 'printInvoice'])->name('pos.printInvoice');
    Route::get('pos/print-kot/{id}', [PosController::class, 'printKot'])->name('pos.printKot');

    Route::get('pos/process-barcode-scan/{barcode}', [PosController::class, 'processBarcodeScan'])->name('pos.processBarcodeScan');

    Route::get('pos/search-product-variations', [PosController::class, 'searchProductVariations'])->name('pos.searchProductVariations');


});