<?php

namespace App\Http\Controllers\Accounting;
use URL;


use File;

use Excel;
use Image;
use Session;
use DateTime;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use PDF;
// for excel export
use Illuminate\Http\Request;
use App\Models\InvoiceMaster;
// end for excel export
use App\Models\Startup\Party;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use App\Models\Accounting\Expense;

use App\Models\Accounting\Journal;
use App\Models\Accounting\Voucher;
use Illuminate\Support\Facades\DB;
use App\Models\Accounting\VoucherType;
// use Barryvdh\DomPDF\Facade\PDF as PDF;

use App\Models\Accounting\ChartOfAccount;
use App\Http\Controllers\Controller; //import base controller

class AccountReportsController extends Controller
{
   
    private function validateDateRange(Request $request)
    {
        $request->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date',
        ], [
            'startDate.required' => 'Start date is required.',
            'endDate.required' => 'End date is required.',
            'startDate.date' => 'Start date must be a valid date.',
            'endDate.date' => 'End date must be a valid date.',
        ]);
    }

    public function request()
    {

        $voucherTypes = VoucherType::all();
        $currentAssetAccounts = ChartOfAccount::whereIn('category',['cash','bank','card'])->get();
        $chartOfAccounts = ChartOfAccount::where('level',4)->get();
        $topLevelAccounts = ChartOfAccount::where('level',1)->get();
        $customers = Party::where('party_type','customer')->get();
        $suppliers = Party::where('party_type','supplier')->get();
        
        return view('accounting.account_reports.request', 
        compact('voucherTypes','currentAssetAccounts','chartOfAccounts','topLevelAccounts','customers','suppliers'));
    }

    public function voucherPDF(Request $request)
    {
        $this->validateDateRange($request);

        $query = Voucher::query()
                ->when($request->voucher_type_code, function ($query, $voucherTypeCode) {
                    dd($voucherTypeCode);
                    return $query->where('code', $voucherTypeCode);

                })
                ->when($request->startDate, function ($query, $startDate) {
                    return $query->whereDate('date', '>=', $startDate);
                })
                ->when($request->endDate, function ($query, $endDate) {
                    return $query->whereDate('date', '<=', $endDate);
                })
                ->orderByDesc('date')
                ->orderByDesc('id')
                ->with('details');  //relationship
                


                $vouchers = $query->get();      
        
$pdf = PDF::loadView('accounting.account_reports.voucher_pdf', compact('vouchers'))
          ->setPaper('a5', 'landscape'); // or 'landscape'           
        return $pdf->stream();
            
        return view('accounting.account_reports.voucher_pdf',  compact('voucher'));
    }

    public function cashbookPDF(Request $request)
    {
        $this->validateDateRange($request);

        // Initialize the accounts array with the provided cashbook account ID
        $levelFourAccounts = [$request->current_coa_id_cashbook];

        // Check if the provided account ID is "0" to fetch level 4 cash accounts
        if ($request->current_coa_id_cashbook === "0") {
            $levelFourAccounts = ChartOfAccount::havingCategory()
                ->where('level', 4)
                ->pluck('id')  // Get an array of account IDs
                ->toArray();   // Convert the result to a plain array
        }


        $broughtForward = DB::table('journals')
            ->select(DB::raw('SUM(COALESCE(debit, 0) - COALESCE(credit, 0)) AS amount'))
            ->where('date', '<', $request->startDate)
            ->whereIn('chart_of_account_id',$levelFourAccounts)
            ->get();


        $query = Journal::whereIn('chart_of_account_id',$levelFourAccounts)

                ->when($request->startDate, function ($query, $startDate) {
                    return $query->where('date', '>=', $startDate);
                })
                ->when($request->endDate, function ($query, $endDate) {
                    return $query->where('date', '<=', $endDate);
                })
                ->orderBy('id')
                ->orderBy('date')
                ->with(['customer','supplier']);  //relationship
                
            $journals = $query->get(); 
        
        $pdf = PDF::loadView('accounting.account_reports.cashbook_pdf', compact('journals','broughtForward'));
        $pdf->setpaper('A4', 'landscape');

        return $pdf->stream();
    }



    public function gernalLedgerPDF(Request $request)
    {

        $this->validateDateRange($request);

        $coaName = null;
        if($request->coa_id != "0"){
            $coaName = ChartOfAccount::find($request->coa_id)->name;
        }
        
        $broughtForward = DB::table('journals')
            ->when($request->coa_id, function ($query, $coaId) {
                if($coaId != "0"){
                    return $query->where('chart_of_account_id', $coaId);

                }
            })
            ->when($request->startDate, function ($query, $startDate) {
                return $query->where('date', '<', $startDate);
            })
            ->select(DB::raw('SUM(COALESCE(debit, 0) - COALESCE(credit, 0)) AS amount'))
            ->get();

           

        $query = Journal::query()
                ->when($request->coa_id, function ($query, $coaId) {
                    if($coaId != "0"){
                        return $query->where('chart_of_account_id', $coaId);
                    }
                })
                ->when($request->startDate, function ($query, $startDate) {
                    return $query->where('date', '>=', $startDate);
                })
                ->when($request->endDate, function ($query, $endDate) {
                    return $query->where('date', '<=', $endDate);
                })
                ->orderBy('id')
                ->orderBy('date')
                ->with(['customer','supplier']);  //relationship
                


        $journals = $query->get(); 
        
        $pdf = PDF::loadView('accounting.account_reports.gernal_ledger_pdf', compact('coaName','journals','broughtForward'));
        $pdf->setpaper('A4', 'landscape');

        return $pdf->stream();
    }



    public function daybookPDF(Request $request)
    {
        $this->validateDateRange($request);


        // Initialize the accounts array with the provided daybook account ID
        $levelFourAccounts = [$request->current_coa_id_daybook];

        // Check if the provided account ID is "0" to fetch level 4 cash accounts
        if ($request->current_coa_id_daybook == "0") {
            $levelFourAccounts = ChartOfAccount::whereIn('category', ['cash','bank','card'])
                ->where('level', 4)
                ->pluck('id')  // Get an array of account IDs
                ->toArray();   // Convert the result to a plain array
        }
        
        $invoices = InvoiceMaster::whereBetween('date',[$request->startDate,$request->endDate])
        ->whereIn('type', ['PI','INV'])
        ->get();

        $query = Journal::query()
                ->when($request->startDate, function ($query, $startDate) {
                    return $query->where('date', '>=', $startDate);
                })
                ->when($request->endDate, function ($query, $endDate) {
                    return $query->where('date', '<=', $endDate);
                })
                
                ->whereIn('chart_of_account_id',$levelFourAccounts)
                ->orderBy('id')
                ->orderBy('date')
                ->with(['customer','supplier']);  //relationship
                


            $journals = $query->get(); 


            return view('accounting.account_reports.daybook_pdf', compact('journals','invoices'));
        
//         $pdf = PDF::loadView('accounting.account_reports.daybook_pdf', compact('journals','invoices'));
//  $pdf->setpaper('A4', 'landscape');
//         return $pdf->stream();
    }

    public function trialBalancePDF(Request $request)
    {
        $this->validateDateRange($request);
        $query = Journal::query()
        // Filter by top-level chart of accounts
        ->when($request->top_level_coa_id, function ($query, $topLevelAccounts) {
            if ($topLevelAccounts != "0") {

                $account = ChartOfAccount::find($topLevelAccounts);
                $levelFourAccounts = ChartOfAccount::select('id')
                    ->where('type', $account->type)
                    ->where('level', 4)
                    ->pluck('id')  // This will return the 'id' values as an array
                    ->toArray();  // Convert the collection to an array

                return $query->whereIn('chart_of_account_id',$levelFourAccounts);
            } 
        })
        // Filter by start date
        ->when($request->startDate, function ($query, $startDate) {
            return $query->where('date', '>=', $startDate);
        })
        // Filter by end date
        ->when($request->endDate, function ($query, $endDate) {
            return $query->where('date', '<=', $endDate);
        })
        // Group by chart_of_account_id and get the sum of debit
        ->select('chart_of_account_id', 
            DB::raw('SUM(debit) as total_debit'),
            DB::raw('SUM(credit) as total_credit'))
        ->groupBy('chart_of_account_id')
        ->having(DB::raw('SUM(COALESCE(debit, 0)) - SUM(COALESCE(credit, 0))'), '!=', 0);
        // Execute the query
                
        $journals = $query->get(); 

        $pdf = PDF::loadView('accounting.account_reports.trial_balance_pdf', compact('journals'));
        // $pdf->setpaper('A4', 'landscape');
        return $pdf->stream();

    }


    public function customerBalancePDF(Request $request)
    {
        $this->validateDateRange($request);
        $type = $request->balance_report_type_customer;

        $query = Journal::query()

        ->when($request->customer_id, function ($query, $customer_id){
            if($customer_id != "0"){
                return $query->where('customer_id', $customer_id);
            }
        })
         // Filter by start date
         ->when($request->startDate, function ($query, $startDate) {
            return $query->where('date', '>=', $startDate);
        })
        // Filter by end date
        ->when($request->endDate, function ($query, $endDate) {
            return $query->where('date', '<=', $endDate);
        })
      
        ->where('chart_of_account_id', config('coa.ar_customers'))
        ->select('customer_id',
        DB::raw('sum(if(ISNULL(debit),0,debit)) as total_debit'),
        DB::raw('sum(if(ISNULL(credit),0,credit)) as total_credit'))
        
        ->groupBy('customer_id');
        $journals = $query->get(); 

        $debitors = $journals->filter(function ($journal) {
            return $journal->total_debit > $journal->total_credit;
        });
        
        $creditors = $journals->filter(function ($journal) {
            return $journal->total_debit < $journal->total_credit;
        });
        

        // $pdf = PDF::loadView('accounting.account_reports.customer_balance_pdf', compact('journals'));
        // Post-processing to separate debitors and creditors
        $debitors = $journals->filter(function ($journal) {
            return $journal->total_debit > $journal->total_credit;
        });
        
        $creditors = $journals->filter(function ($journal) {
            return $journal->total_debit < $journal->total_credit;
        });
        $pdf = NULL;
        if($type == 'debitor'){
            $journals = $debitors;
            $pdf = PDF::loadView('accounting.account_reports.customer_balance.seprate', compact('journals'));
        }
        elseif($type == 'creditor'){
            $journals = $creditors;
            $pdf = PDF::loadView('accounting.account_reports.customer_balance.seprate', compact('journals'));
        }
        else{
            $pdf = PDF::loadView('accounting.account_reports.customer_balance.both', compact('debitors','creditors'));  
        }
        // $pdf->setpaper('A4', 'landscape');
        return $pdf->stream();
        
    }

    public function supplierBalancePDF(Request $request)
    {
        $this->validateDateRange($request);
        $type = $request->balance_report_type_supplier;

        $query = Journal::query()
            ->when($request->supplier_id, function ($query, $supplier_id) {
                if ($supplier_id != "0") {
                    return $query->where('supplier_id', $supplier_id);
                }
            })
            ->when($request->startDate, function ($query, $startDate) {
                return $query->where('date', '>=', $startDate);
            })
            ->when($request->endDate, function ($query, $endDate) {
                return $query->where('date', '<=', $endDate);
            })
            ->where('chart_of_account_id', config('coa.ap_suppliers'))
            ->select('supplier_id',
                DB::raw('sum(if(ISNULL(debit),0,debit)) as total_debit'),
                DB::raw('sum(if(ISNULL(credit),0,credit)) as total_credit')
            )
            ->groupBy('supplier_id');
        
        // Execute the query to get results
        $journals = $query->get();
        
        // Post-processing to separate debitors and creditors
        $debitors = $journals->filter(function ($journal) {
            return $journal->total_debit > $journal->total_credit;
        });
        
        $creditors = $journals->filter(function ($journal) {
            return $journal->total_debit < $journal->total_credit;
        });
        $pdf = NULL;
        if($type == 'debitor'){
            $journals = $debitors;
            $pdf = PDF::loadView('accounting.account_reports.supplier_balance.seprate', compact('journals'));
        }
        elseif($type == 'creditor'){
            $journals = $creditors;
            $pdf = PDF::loadView('accounting.account_reports.supplier_balance.seprate', compact('journals'));
        }
        else{
            $pdf = PDF::loadView('accounting.account_reports.supplier_balance.both', compact('debitors','creditors'));  
        }
        // $pdf->setpaper('A4', 'landscape');
        return $pdf->stream();
        
    }


    public function expensePDF(Request $request)
    {
        $this->validateDateRange($request);
        $query = Expense::query()

        // Filter by start date
        ->when($request->startDate, function ($query, $startDate) {
            return $query->where('date', '>=', $startDate);
        })
        // Filter by end date
        ->when($request->endDate, function ($query, $endDate) {
            return $query->where('date', '<=', $endDate);
        });

        $expenses = $query->get(); 

        $pdf = PDF::loadView('accounting.account_reports.expense_pdf', compact('expenses'));
        return $pdf->stream();


    }

    public function customerLedgerPDF(Request $request)
    {
        $this->validateDateRange($request);

        $request->validate([
            'customer_id_1' => 'required',
            'startDate' => 'required',
            'endDate' => 'required',
        ],
        [
            'customer_id_1.required' =>'customer is required'
        ]);
        $customer_id = $request->customer_id_1;
        $startDate = $request->startDate;
        $endDate = $request->endDate;

        $broughtForward = DB::table('journals')
        ->where('customer_id', $customer_id)
        ->where('date', '<', $startDate)
        ->where('chart_of_account_id', config('coa.ar_customers'))
        ->select(DB::raw('SUM(COALESCE(debit, 0) - COALESCE(credit, 0)) AS amount'))
        ->get();
      
       

        $journals = Journal::where('customer_id', $customer_id)
        ->where('date', '>=', $startDate)
        ->where('date', '<=', $endDate)
        ->where('chart_of_account_id', config('coa.ar_customers'))
        ->orderBy('date','asc')
        ->get();

        $pdf = PDF::loadView('accounting.account_reports.customer_ledger_pdf', compact('journals','broughtForward'));
        // $pdf->setpaper('A4', 'landscape');
        return $pdf->stream();
    }



    public function supplierLedgerPDF(Request $request)
    {

        $this->validateDateRange($request);

        $request->validate([
            'supplier_id_1' => 'required',
            'startDate' => 'required',
            'endDate' => 'required',
        ],
        [
            'supplier_id_1.required' =>'Supplier is required'
        ]);

        $supplier_id = $request->supplier_id_1;
        $startDate = $request->startDate;
        $endDate = $request->endDate;

        $broughtForward = DB::table('journals')
        ->where('supplier_id', $supplier_id)
        ->where('date', '<', $startDate)
        ->where('chart_of_account_id', config('coa.ap_suppliers'))
        ->select(DB::raw('SUM(COALESCE(debit, 0) - COALESCE(credit, 0)) AS amount'))
        ->get();


        $journals = Journal::where('supplier_id', $supplier_id)
        ->where('date', '>=', $startDate)
        ->where('date', '<=', $endDate)
        ->where('chart_of_account_id', config('coa.ap_suppliers'))
        ->orderBy('date','asc')
        ->get();
       


        $pdf = PDF::loadView('accounting.account_reports.supplier_ledger_pdf', compact('journals','broughtForward'));
        $pdf->setpaper('A4', 'landscape');
        return $pdf->stream();
    }



    
}
