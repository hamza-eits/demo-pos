<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Material;
use App\Models\CustomField;
use App\Models\CashRegister;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use App\Models\InvoiceDetail;
use App\Models\InvoiceMaster;
use App\Models\Startup\Brand;
use App\Models\InvoicePayment;
use App\Models\ProductVariation;
use App\Models\Startup\Category;
use App\Models\Accounting\Expense;
use Illuminate\Support\Facades\DB;
use App\Models\Accounting\ChartOfAccount;

class PosReportsController extends Controller
{
    

    public function salesSummaryRequest()
    {
        return view('pos_reports.sales_summary.request');    

    }

    public function salesSummaryShow(Request $request)
    {
        
        $startDate = $request->input('startDate', date('Y-m-d'));
        $endDate = $request->input('endDate', date('Y-m-d'));


        $query = InvoiceMaster::ofTypeInvoice()
        
        // Filter by start date
        ->when($request->startDate, function ($query, $startDate) {
            return $query->whereDate('date', '>=', $startDate);
        })
        // Filter by end date
        ->when($request->endDate, function ($query, $endDate) {
            return $query->whereDate('date', '<=', $endDate);
        })
        ->orderBy('id','desc')
        ->orderBy('date','desc');


        $data = $query->get();

        $countInvoiceRecords = $data->unique('invoice_master_id')->count();
        $countCashRecords = $data->where('mode', 'cash')->count();
        $countCardRecords = $data->where('mode', 'card')->count();
        $countBankRecords = $data->where('mode', 'bank')->count();


        $cashSale = $this->getCategorySaleAmount('cash',$startDate,$endDate);
        $cardSale = $this->getCategorySaleAmount('card',$startDate,$endDate);
        $bankSale = $this->getCategorySaleAmount('bank',$startDate,$endDate);

        return view('pos_reports.sales_summary.show', 
        compact('data',
        'cashSale','cardSale','bankSale',
        'countCashRecords','countCardRecords','countBankRecords','countInvoiceRecords'));

        

    }

    public function paymentSourceSummaryRequest()
    {
        return view('pos_reports.payment_source_summary.request');    

    }

    public function paymentSourceSummaryShow(Request $request)
    {
        
        $startDate = $request->input('startDate', date('Y-m-d'));
        $endDate = $request->input('endDate', date('Y-m-d'));


        $query = InvoicePayment::query()
        
        // Filter by start date
        ->when($request->startDate, function ($query, $startDate) {
            return $query->where('date', '>=', $startDate);
        })
        // Filter by end date
        ->when($request->endDate, function ($query, $endDate) {
            return $query->where('date', '<=', $endDate);
        })
        ->orderBy('id','desc')
        ->orderBy('date','desc');


        $data = $query->get();

        $countInvoiceRecords = $data->unique('invoice_master_id')->count();
        $countCashRecords = $data->where('mode', 'cash')->count();
        $countCardRecords = $data->where('mode', 'card')->count();
        $countBankRecords = $data->where('mode', 'bank')->count();


        $cashSale = $this->getCategorySaleAmount('cash',$startDate,$endDate);
        $cardSale = $this->getCategorySaleAmount('card',$startDate,$endDate);
        $bankSale = $this->getCategorySaleAmount('bank',$startDate,$endDate);

        return view('pos_reports.payment_source_summary.show', 
        compact('data',
        'cashSale','cardSale','bankSale',
        'countCashRecords','countCardRecords','countBankRecords','countInvoiceRecords'));

        

    }

    private function getCategorySaleAmount($category,$startDate,$endDate)
    {
        $coa_ids = ChartOfAccount::where('category',$category)
        ->where('level',4)
        ->pluck('id');

        $saleAmount = InvoicePayment::whereBetween('date',[$startDate,$endDate])
        ->whereIn('chart_of_account_id', $coa_ids)
        ->sum('amount');

        return $saleAmount;
    }

    public function taxSummaryRequest()
    {
        return view('pos_reports.tax_summary.request');    

    }

    public function taxSummaryShow(Request $request)
    {

        $startDate = $request->input('startDate',date('Y-m-d'));
        $endDate = $request->input('endDate',date('Y-m-d'));


        $query = InvoiceMaster::ofTypeInvoice()
        ->where('status', 'completed')

        // Filter by start date
        ->when($request->startDate, function ($query, $startDate) {
            return $query->where('date', '>=', $startDate);
        })
        // Filter by end date
        ->when($request->endDate, function ($query, $endDate) {
            return $query->where('date', '<=', $endDate);
        })
        ->orderBy('date','desc')
        ->orderBy('id','desc');

        $data = $query->get();

        $taxInput = $query->where('tax_type','inclusive')->sum('tax_amount');
        $taxOutput = $query->where('tax_type','exclusive')->sum('tax_amount');

      
        
        return view('pos_reports.tax_summary.show', compact('data','taxInput','taxOutput'));

    }


    public function xReportRequest(){
        return view('pos_reports.x_report.request');
    }

    public function xReportShow(Request $request){

        $startDate = $request->input('startDate',date('Y-m-d'));
        // $endDate = $request->input('endDate',date('Y-m-d'));
        
        $invoicePayments = InvoicePayment::where('date',$startDate)->get();
        
        $data =[
            [
                'name' => 'Cash Sale',
                'amount' => $invoicePayments->where('mode','cash')->sum('amount'),
            ],
            [
                'name' => 'Card Sale',
                'amount' => $invoicePayments->where('mode','card')->sum('amount'),
            ],
            [
                'name' => 'Bank Sale',
                'amount' => $invoicePayments->where('mode','bank')->sum('amount'),
            ],
        ];

        return view('pos_reports.x_report.show', compact('data','startDate'));
    }


    public function zReportRequest(){
        return view('pos_reports.z_report.request');
    }
    
    public function zReportShow(Request $request){


        $startDate = $request->input('startDate',date('Y-m-d'));
        $endDate = $request->input('endDate',date('Y-m-d'));
    
        $invoicePayments = InvoicePayment::whereDate('date','>=',$startDate)
        ->whereDate('date','<=',$endDate)
        ->get();

        // $cash_register_ids = $invoicePayments->unique('cash_register_id')->pluck('cash_register_id');
        
        $cashRegisterHistory = CashRegister::whereDate('opened_at','>=',$startDate)
        ->whereDate('opened_at','<=',$endDate)
        ->get();
        // $cashInDrawer = $cashRegisterHistory->sum('closing_cash') - $cashRegisterHistory->sum('opening_cash');
        $openingCash =  $cashRegisterHistory->sum('opening_cash');
        
        $expenseAmount = Expense::whereDate('date','>=',$startDate)
        ->whereDate('date','<=',$endDate)
        ->sum('grand_total');

        $cashSale = $invoicePayments->where('mode','cash')->sum('amount');

        $closingCash = ($openingCash + $cashSale) - $expenseAmount;
        
        $data = [
            
            [
                'name' => 'Cash Sale',
                'amount' => $cashSale,
            ],
            [
                'name' => 'Card Sale',
                'amount' => $invoicePayments->where('mode','card')->sum('amount'),
            ],
            [
                'name' => 'Bank Sale',
                'amount' => $invoicePayments->where('mode','bank')->sum('amount'),
            ],
            [
                'name' => 'Total Sales',
                'amount' => $invoicePayments->sum('amount'),
            ],
            [
                'name' => 'Total Expense',
                'amount' => $expenseAmount,
            ],
            [
                'name' => 'Total Cash Debit',
                'amount' => $cashSale + $openingCash,
            ],
            [
                'name' => 'Total Cash Credit',
                'amount' => $expenseAmount,
            ],
            [
                'name' => 'Closing Cash (DR - CR)',
                'amount' => $closingCash,
            ],
        ];
        
        $company = \App\Models\Startup\Company::first();

        return view('pos_reports.z_report.show', compact('data','startDate','endDate','cashRegisterHistory','company'));
    }


    public function itemWiseSalesSummaryRequest(){
        
        $variations = ProductVariation::all();
       

        return view('pos_reports.item_wise_sales_summary.request', compact('variations'));
    }

    public function itemWiseSalesSummaryShow(Request $request){
        
        $startDate  = $request->input('startDate', date('Y-m-d'));
        $endDate    = $request->input('endDate', date('Y-m-d'));


        $data = DB::table('invoice_details')
            
            ->when($request->filled('product_variation_id'), fn($q) => $q->where('product_variation_id', $request->product_variation_id))
            
            ->where('type', 'INV')
            ->whereBetween(DB::raw('DATE(date)'), [$startDate, $endDate])
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get();
        
        // ->select('product_variation_id','type','variation_barcode','invoice_no','date','quantity','unit_price','subtotal','discount_amount','grand_total')
        // return response()->json($data);


        return view('pos_reports.item_wise_sales_summary.show',compact('data','startDate','endDate'));
    }


    public function inventorySummaryRequest(){
       
        $data['products'] = Product::select('id','name')->get();
        $data['materials'] = Material::select('id','name')->get();
        $data['productModels'] = ProductModel::select('id','name')->get();
        $data['customFields'] = CustomField::select('id','name')->get();
        $data['categories'] = Category::select('id','name')->get();
        

        return view('pos_reports.inventory_summary.request', compact('data'));
    }

    
    // public function inventorySummaryShow(Request $request){

    //     $endDate    = $request->input('endDate', date('Y-m-d'));

    //     $data = [];

    //     $products = Product::query()
    //     ->inventory()

    //     ->when($request->filled('product_id'), fn($query) => $query->where('id', $request->product_id))
    //     ->when($request->filled('category_id'), fn($query) => $query->where('category_id', $request->category_id))
    //     ->when($request->filled('material_id'), fn($query) => $query->where('material_id', $request->material_id))
    //     ->when($request->filled('product_model_id'), fn($query) => $query->where('product_model_id', $request->product_model_id))
    //     ->when($request->filled('custom_field_id'), fn($query) => $query->where('custom_field_id', $request->custom_field_id))
      
    //     ->get();

    //     foreach($products as $product)
    //     {
    //         foreach($product->variations as $variation)
    //         {

    //             $soldQty  = $variation->saleInvoiceDetails()
    //                         ->whereDate('date','<=',$endDate)
    //                         ->sum('stock_quantity');

    //             $purchaseQty = $variation->purchaseInvoiceDetails()
    //                         ->whereDate('date','<=',$endDate)
    //                         ->sum('stock_quantity');
                


    //             $data[] = [
    //                 'product' => $product->name,
    //                 'description' => $product->description,
    //                 'material' => $product->material->name ?? '-',
    //                 'productModel' => $product->productModel->name ?? '-',
    //                 'customField' => $product->customField->name ?? '-',
    //                 'category' => $product->category->name ?? '-',
    //                 'brand' => $product->brand->name ?? '-',
    //                 'material' => $product->material->name ?? '-',
    //                 'variation' => $variation->name,
                    
    //                 'soldQty' => $soldQty,
    //                 'purchaseQty' => $purchaseQty,

    //                 'balance' => $purchaseQty - $soldQty,
                    
                    
    //             ];
    //         }
    //     }
    //     return view('pos_reports.inventory_summary.show',compact('data','endDate'));
    // }
     public function inventorySummaryShow(Request $request){

        $startDate  = $request->input('startDate', date('Y-m-d'));
        $endDate    = $request->input('endDate', date('Y-m-d'));



        $data = [];

        $products = Product::query()
        ->inventory()

        ->when($request->filled('product_id'), fn($query) => $query->where('id', $request->product_id))
        ->when($request->filled('category_id'), fn($query) => $query->where('category_id', $request->category_id))
        ->when($request->filled('material_id'), fn($query) => $query->where('material_id', $request->material_id))
        ->when($request->filled('product_model_id'), fn($query) => $query->where('product_model_id', $request->product_model_id))
        ->when($request->filled('custom_field_id'), fn($query) => $query->where('custom_field_id', $request->custom_field_id))
        ->whereHas('variations', function($query) use ($startDate, $endDate) {
            $query->whereHas('saleInvoiceDetails', function($q) use ($startDate, $endDate) {
                $q->whereDate('date', '>=', $startDate)
                  ->whereDate('date', '<=', $endDate);
            })->orWhereHas('purchaseInvoiceDetails', function($q) use ($startDate, $endDate) {
                $q->whereDate('date', '>=', $startDate)
                  ->whereDate('date', '<=', $endDate);
            });
        })
        ->get();

        foreach($products as $product)
        {
            foreach($product->variations as $variation)
            {

                $soldQty  = $variation->saleInvoiceDetails()
                            ->whereDate('date','<=',$endDate)
                            ->whereDate('date','>=',$startDate)
                            ->sum('stock_quantity');

                $purchaseQty = $variation->purchaseInvoiceDetails()
                            ->whereDate('date','<=',$endDate)
                            ->whereDate('date','>=',$startDate)
                            ->sum('stock_quantity');
                


                $data[] = [
                    'product' => $product->name,
                    'description' => $product->description,
                    'material' => $product->material->name ?? '-',
                    'productModel' => $product->productModel->name ?? '-',
                    'customField' => $product->customField->name ?? '-',
                    'category' => $product->category->name ?? '-',
                    'brand' => $product->brand->name ?? '-',
                    'material' => $product->material->name ?? '-',
                    'variation' => $variation->name,
                    
                    'soldQty' => $soldQty,
                    'purchaseQty' => $purchaseQty,

                    'balance' => $purchaseQty - $soldQty,
                    
                    
                ];
            }
        }
        return view('pos_reports.inventory_summary.show',compact('data','endDate','startDate'));
    }


    public function salesSummaryDetailRequest()
    {
        $products = Product::all();
        return view('pos_reports.inventory_summary_detail.request', compact('products'));
    }


    public function  salesSummaryDetailShow(Request $request)
    {
        
    }



     public function purchaseSummaryRequest()
    {
        return view('pos_reports.purchase_summary.request');    

    }

    public function purchaseSummaryShow(Request $request)
    {
        
        $startDate = $request->input('startDate', date('Y-m-d'));
        $endDate = $request->input('endDate', date('Y-m-d'));


        $query = InvoiceDetail::where('type','PI')
        
        // Filter by start date
        ->when($request->startDate, function ($query, $startDate) {
            return $query->whereDate('date', '>=', $startDate);
        })
        // Filter by end date
        ->when($request->endDate, function ($query, $endDate) {
            return $query->whereDate('date', '<=', $endDate);
        })
        ->orderBy('id','desc')
        ->orderBy('date','desc');


        $data = $query->get();


        return view('pos_reports.purchase_summary.show',compact('data'));

        

    }



     public function inventoryDetailSummaryRequest(){
       
        $data['products'] = Product::select('id','name')->get();
        $data['materials'] = Material::select('id','name')->get();
        $data['productModels'] = ProductModel::select('id','name')->get();
        $data['customFields'] = CustomField::select('id','name')->get();
        $data['categories'] = Category::select('id','name')->get();
        

        return view('pos_reports.inventory_detail_summary.request', compact('data'));
    }

    
    public function inventoryDetailSummaryShow(Request $request){

        $endDate  = $request->input('endDate', date('Y-m-d'));

        $data = [];

        $stockVariationList = DB::table('v_purchase_wise_product_variation')->get();
        $totalCost = 0;
        $totalPrice = 0;  

        foreach($stockVariationList as $list)
        {   

           $variation = ProductVariation::with('product')
            ->whereHas('product', function ($query) use ($request) {
                $query->when($request->filled('product_id'), fn($q) => $q->where('id', $request->product_id))
                    ->when($request->filled('category_id'), fn($q) => $q->where('category_id', $request->category_id))
                    ->when($request->filled('material_id'), fn($q) => $q->where('material_id', $request->material_id))
                    ->when($request->filled('product_model_id'), fn($q) => $q->where('product_model_id', $request->product_model_id))
                    ->when($request->filled('custom_field_id'), fn($q) => $q->where('custom_field_id', $request->custom_field_id));
            })
            ->find($list->product_variation_id);

            if($variation)
            {
                $lastPurchase = InvoiceDetail::where('type','PI')
                ->where('stock_barcode',$list->stock_barcode)
                ->orderBy('date','desc')
                ->orderBy('id','desc')
                ->first();

                $purchaseDate = Carbon::parse($lastPurchase->date);

                $aging = $purchaseDate->diffInDays($endDate, false);
                



                $data[] = [
                    'name' => $variation->name,
                    'description' => $variation->product->description,
                    'category' => $variation->product->category->name,
                    'materialType' => $variation->product->material->name,
                    'model' => $variation->product->productModel->name,
                    'customField' => $variation->product->customField->name,
                    'poNumber' => $lastPurchase->invoice_no,
                    'costlevel' => $lastPurchase->purchase_category,
                    'poDate' => $lastPurchase->date,
                    'aging' => $aging,
                    'unit' => $list->stock,
                    'cost' =>  $lastPurchase->unit_price,
                    'totalCost' => $lastPurchase->unit_price * $list->stock,
                    'price' =>  $lastPurchase->selling_price,
                    'totalPrice' => $lastPurchase->selling_price * $list->stock,
                    'qtyIn' => $list->stock_in,
                    'qtyOut' => $list->stock_out,
                    'balance' => $list->stock,

                
                    
                ];

                $totalCost += $lastPurchase->unit_price * $list->stock;
                $totalPrice += $lastPurchase->selling_price * $list->stock;
            }


            
            
        }

        // return response()->json($data);

        return view('pos_reports.inventory_detail_summary.show',compact('data','endDate','totalCost','totalPrice'));
    }


    
}
