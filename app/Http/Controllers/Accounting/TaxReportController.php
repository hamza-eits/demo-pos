<?php

namespace App\Http\Controllers\Accounting;

use Barryvdh\DomPDF\Facade\PDF as PDF;
use Illuminate\Http\Request;
use App\Models\Accounting\Expense;
use App\Http\Controllers\Controller;
use App\Models\Inventory\InvoiceMaster;
use App\Exports\TaxFilingExport;
use Maatwebsite\Excel\Facades\Excel;

class TaxReportController extends Controller
{
    public function request()
    {
        return view('accounting.tax_report.request');
    }

    public function show(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $filingStatus = $request->filingStatus;

        $purchaseInvoices = $this->fetchInvoiceMasterRecords('PI',$startDate,$endDate,$filingStatus);

        $saleInvoices = $this->fetchInvoiceMasterRecords('SI',$startDate,$endDate,$filingStatus);

        $expenses = Expense::where('calculated_tax_amount','>',0)
        ->when($filingStatus != null, function($query) use ($filingStatus) {
            return $query->where('is_tax_filed', $filingStatus);
        })
        ->whereBetween('date', [$startDate,$endDate])
        ->get();
       

        $si = $saleInvoices->sum('total_tax_amount') ?? 0;
        $pi = $purchaseInvoices->sum('total_tax_amount') ?? 0;
        $exp = $expenses->sum('calculated_tax_amount') ?? 0;

        $taxPayable = $si - ($pi + $exp);


        return view('accounting.tax_report.show', 
            compact('purchaseInvoices','saleInvoices','expenses',
            'si','pi','exp','taxPayable','startDate','endDate'
        ));

    }
    public function pdf(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $filingStatus = $request->filingStatus;

        $purchaseInvoices = $this->fetchInvoiceMasterRecords('PI',$startDate,$endDate,$filingStatus);

        $saleInvoices = $this->fetchInvoiceMasterRecords('SI',$startDate,$endDate,$filingStatus);

        $expenses = $this->fetchExpenseRecords($startDate,$endDate,$filingStatus);
       

        $si = $saleInvoices->sum('total_tax_amount') ?? 0;
        $pi = $purchaseInvoices->sum('total_tax_amount') ?? 0;
        $exp = $expenses->sum('calculated_tax_amount') ?? 0;

        $taxPayable = $si - ($pi + $exp);

        $pdf = PDF::loadView('accounting.tax_report.pdf', 
            compact('purchaseInvoices','saleInvoices','expenses',
            'si','pi','exp','taxPayable','startDate','endDate'
        ));
           
        return $pdf->stream();
    }
    public function excelExport(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $filingStatus = $request->filingStatus;

        // Trigger export
        return Excel::download(new TaxFilingExport($startDate, $endDate,$filingStatus), 'tax_filing_report.xlsx');
    }

    public function fetchInvoiceMasterRecords($type,$startDate,$endDate,$filingStatus)
    {
        $data =  InvoiceMaster::where('type',$type)
        ->when($filingStatus != null, function($query) use ($filingStatus) {
            return $query->where('is_tax_filed', $filingStatus);
        })
        ->whereBetween('date', [$startDate,$endDate])
        ->get();

        return $data;
    }
    public function fetchExpenseRecords($startDate,$endDate,$filingStatus)
    {
        $data = Expense::where('calculated_tax_amount','>',0)
        ->when($filingStatus != null, function($query) use ($filingStatus) {
            return $query->where('is_tax_filed', $filingStatus);
        })
        ->whereBetween('date', [$startDate,$endDate])
        ->get();

        return $data;
    }
}
