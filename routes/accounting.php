<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Accounting\PnlController;
use App\Http\Controllers\Accounting\ExpenseController;
use App\Http\Controllers\Accounting\VoucherController;
use App\Http\Controllers\Accounting\TaxFilingController;
use App\Http\Controllers\Accounting\TaxReportController;
use App\Http\Controllers\Accounting\BalanceSheetController;
use App\Http\Controllers\Accounting\AccountReportsController;
use App\Http\Controllers\Accounting\ChartOfAccountController;
use App\Http\Controllers\Accounting\TaxFilingDetailController;



// Only Super Admin Access    
Route::middleware(['auth','role:super-admin'])->group(function(){
    Route::delete('expense/{expense}', [ExpenseController::class, 'destroy'])->name('expense.destroy');
    Route::delete('voucher/{voucher}', [VoucherController::class, 'destroy'])->name('voucher.destroy');
    Route::delete('chart-of-account/{chart_of_account}', [ChartOfAccountController::class, 'destroy'])->name('chart-of-account.destroy');

});


// Super Admin and Admin Access
Route::middleware(['auth','role:super-admin|admin'])->group(function(){
    Route::resource('chart-of-account', ChartOfAccountController::class)->except('destroy');
    
    Route::resource('voucher', VoucherController::class)->except('destroy');

    Route::get('create-jv', [VoucherController::class, 'createJournalVoucher'])->name('voucher.createJournalVoucher');
    Route::post('store-jv', [VoucherController::class, 'storeJournalVoucher'])->name('voucher.storeJournalVoucher');
    
    Route::get('account-reports/request', [AccountReportsController::class, 'request'])->name('account-reports.request');
    Route::post('account-reports/voucher-pdf', [AccountReportsController::class, 'voucherPDF'])->name('account-reports.voucherPDF');
    Route::post('account-reports/cashbook-pdf', [AccountReportsController::class, 'cashbookPDF'])->name('account-reports.cashbookPDF');
    Route::post('account-reports/gernal-ledger-pdf', [AccountReportsController::class, 'gernalLedgerPDF'])->name('account-reports.gernalLedgerPDF');
    Route::post('account-reports/daybook-pdf', [AccountReportsController::class, 'daybookPDF'])->name('account-reports.daybookPDF');
    Route::post('account-reports/trial-balance-pdf', [AccountReportsController::class, 'trialBalancePDF'])->name('account-reports.trialBalancePDF');
    Route::post('account-reports/customer-balance-pdf', [AccountReportsController::class, 'customerBalancePDF'])->name('account-reports.customerBalancePDF');
    Route::post('account-reports/supplier-balance-pdf', [AccountReportsController::class, 'supplierBalancePDF'])->name('account-reports.supplierBalancePDF');
    Route::post('account-reports/expense-pdf', [AccountReportsController::class, 'expensePDF'])->name('account-reports.expensePDF');
    Route::post('account-reports/customer-ledger-pdf', [AccountReportsController::class, 'customerLedgerPDF'])->name('account-reports.customerLedgerPDF');
    Route::post('account-reports/supplier-ledger-pdf', [AccountReportsController::class, 'supplierLedgerPDF'])->name('account-reports.supplierLedgerPDF');
    
    
    
    Route::get('request', [TaxReportController::class, 'request'])->name('tax-report.request');
    Route::post('show', [TaxReportController::class, 'show'])->name('tax-report.show');
    Route::post('pdf', [TaxReportController::class, 'pdf'])->name('tax-report.pdf');
    Route::post('excel-export', [TaxReportController::class, 'excelExport'])->name('tax-report.excel-export');
    
    
    Route::post('tax-filing/posting/{id}', [TaxFilingController::class , 'posting'])->name('tax-filing.posting');
    Route::resource('tax-filing', TaxFilingController::class);
    Route::resource('tax-filing-detail', TaxFilingDetailController::class);
    
    Route::get('balance-sheet/request', [BalanceSheetController::class, 'request'])->name('balance-sheet.request');
    Route::post('balance-sheet/show', [BalanceSheetController::class, 'show'])->name('balance-sheet.show');

    Route::get('pnl/request', [PnlController::class, 'request'])->name('pnl.request');
    Route::post('pnl/show', [PnlController::class, 'show'])->name('pnl.show');

});


// Auth User Access
Route::middleware('auth')->group(function(){
    Route::get('chart-of-account/get-by-category/{id}', [ChartOfAccountController::class, 'getByCategory'])->name('chart-of-account.getByCategory');
    Route::resource('expense', ExpenseController::class)->except('destroy');
});

