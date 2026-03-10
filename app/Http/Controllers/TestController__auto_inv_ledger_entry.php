<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceDetail;
use App\Models\InvoiceMaster;
use App\Models\InvoicePayment;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ProductVariation;
use App\Models\Accounting\Journal;
use App\Models\Accounting\Voucher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Picqer\Barcode\BarcodeGeneratorPNG;

class TestController extends Controller
{
    /**
     * Handle the incoming request.
     */


    
    public function __invoke(Request $request)
    {
        dd($request->all());
        $invoiceMasters = InvoiceMaster::where('type','INV')
        ->orderBy('date','asc')
        ->orderBy('id','asc')
        ->get();
        
        


        DB::beginTransaction();
        try {

            foreach($invoiceMasters as $invoiceMaster)
            {

                DB::table('vouchers')->where('invoice_master_id', $invoiceMaster->id)->delete();
                DB::table('voucher_details')->where('invoice_master_id', $invoiceMaster->id)->delete();
                DB::table('journals')->where('invoice_master_id', $invoiceMaster->id)->delete();

                $invoiceAmount = $invoiceMaster->grand_total;
                $taxAmount = $invoiceMaster->tax_amount;
                $discountAmount = $invoiceMaster->discount_amount;
                $revenueAmount = $invoiceAmount + $discountAmount - $taxAmount; 
                $costAmount = $invoiceMaster->total_cost_amount;

                $narration = $this->makeNarration($invoiceMaster);

                $data = [
                    'invoice_master_id'  => $invoiceMaster->id,
                    'date' => $invoiceMaster->date,
                    'voucher_no' => $invoiceMaster->invoice_no,
                    'code' => 'JV',
                    'type' => 'Journal Voucher',
                    'narration' => $narration,
                    'created_by' => Auth::user()->id,
                    'created_at' => now(),
                ];

                $voucher = $this->createVoucherMaster($data, $invoiceAmount);

                //update invoice master with voucher id
                $data = array_merge($data, [
                    'voucher_id' => $voucher->id,
                    'customer_id' => $invoiceMaster->party_id,
                ]);

                // Debit AR Customer
                $this->arCustomerDebitEntry($data, $invoiceAmount);

                // Debit Sale Discount Given
                if ($discountAmount > 0) {
                    $this->saleDiscountGivenDebitEntry($data, $discountAmount);
                }

                // Credit Sales Revenue
                $this->salesRevenueCreditEntry($data, $revenueAmount); 

                // Output Tax Revenue
                if ($taxAmount > 0) {
                    $this->outputTaxCreditEntry($data, $taxAmount);
                }

                // Debit Cash/Bank/Card
                $this->cashBankCardDebitEntries($data, $invoiceMaster->id);

                // Credit AR Customer
                $this->arCustomerCreditEntry($data, $invoiceAmount);

                // Debit Cogs(Actual Cost of Proudct) Finished Products 
                $this->cogsFinishedProductsDebitEntry($data,$costAmount);

                //Credit Stock Purchased 
                $this->stockPurchasedCreditEntry($data,$costAmount);

            }

            
            DB::commit();

            return response()->json('done');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    

    public function createVoucherMaster($data,$invoiceAmount)
    {
        $voucher = Voucher::create(array_merge($data, [
            'total_amount' => $invoiceAmount,
        ]));

        return $voucher;
    }


    public function arCustomerDebitEntry($data,$invoiceAmount)
    {
        $debit = array_merge($data, [
            'chart_of_account_id' => config('coa.ar_customers'),
            'debit' => $invoiceAmount,
        ]);
        DB::table('voucher_details')->insert($debit);
        DB::table('journals')->insert($debit);
    }

    public function saleDiscountGivenDebitEntry($data, $discountAmount)
    {
        $debit = array_merge($data, [
            'chart_of_account_id' => config('coa.sales_discount_given'),
            'debit' => $discountAmount,
        ]);
        DB::table('voucher_details')->insert($debit);
        DB::table('journals')->insert($debit);
    }

    public function outputTaxCreditEntry($data,$taxAmount)
    {
        $credit = array_merge($data, [
            'chart_of_account_id' => config('coa.ap_output_vat'),
            'credit' => $taxAmount,
        ]);
        DB::table('voucher_details')->insert($credit);
        DB::table('journals')->insert($credit);
    }

    public function salesRevenueCreditEntry($data,$revenueAmount)
    {
        $credit = array_merge($data, [
            'chart_of_account_id' => config('coa.sales_revenue'),
            'credit' => $revenueAmount,
        ]);
        DB::table('voucher_details')->insert($credit);
        DB::table('journals')->insert($credit);
    }

    public function cashBankCardDebitEntries($data,$invoiceMasterID)
    {

        $invoicePayments = InvoicePayment::where('invoice_master_id',$invoiceMasterID)->get();

        foreach($invoicePayments as $invoice)
        {
            $debit = array_merge($data , [
                'chart_of_account_id' => $invoice->chart_of_account_id,
                'debit' => $invoice->amount,
            ]); 
            DB::table('voucher_details')->insert($debit);
            DB::table('journals')->insert($debit);
        }

        
    }
    public function arCustomerCreditEntry($data,$invoiceAmount)
    {
        $credit = array_merge($data, [
            'chart_of_account_id' => config('coa.ar_customers'),
            'credit' => $invoiceAmount,
        ]);
        DB::table('voucher_details')->insert($credit);
        DB::table('journals')->insert($credit);
    }

    public function cogsFinishedProductsDebitEntry($data,$cost)
    {
        $debit = array_merge($data, [
            'chart_of_account_id' => config('coa.cogs_finished_products'),
            'debit' => $cost,
        ]);
        DB::table('voucher_details')->insert($debit);
        DB::table('journals')->insert($debit);

    }

    public function stockPurchasedCreditEntry($data,$cost)
    {
        $credit = array_merge($data, [
            'chart_of_account_id' => config('coa.inventory_raw_material'),
            'credit' => $cost,
        ]);
        DB::table('voucher_details')->insert($credit);
        DB::table('journals')->insert($credit);
    }


    public function makeNarration($invoiceMaster)
    {
        $invoiceNo = $invoiceMaster->invoice_no;
        $discountAmount = $invoiceMaster->discount_amount;
        $taxAmount = $invoiceMaster->tax_amount;
        $grandTotal = $invoiceMaster->grand_total;
        
        $components = [];

        $components[] = "POS Sale";
        $components[] = "invoice no: $invoiceNo";
        $components[] = "amount: $grandTotal";

        if (!empty($invoiceMaster->reference_no)) {
            $components[] = "reference: " . $invoiceMaster->reference_no;
        }

        if ($discountAmount > 0) {
            $components[] = "discount: $discountAmount";
        }

        if ($taxAmount > 0) {
            $components[] = "vat: $taxAmount";
        }

        $narration = implode(' | ', $components); // Use a delimiter like "|" for clarity (optional)

        return $narration;
    }



  
}

