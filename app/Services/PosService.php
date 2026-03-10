<?php

namespace App\Services;

use App\Models\Recipe;
use App\Models\Product;
use App\Models\PosSetting;
use App\Models\Startup\User;
use Illuminate\Http\Request;
use App\Models\InvoiceMaster;
use App\Models\Startup\Brand;
use App\Models\Startup\Party;
use App\Models\InvoicePayment;
use App\Models\ProductVariation;
use App\Models\Startup\Category;
use App\Models\Accounting\Expense;
use App\Models\Accounting\Voucher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Accounting\ChartOfAccount;
use Illuminate\Support\Facades\Validator;


class PosService {

    protected $type;

    public function __construct()
    {
        $this->type = 'INV';
    }
    /**
     * Get the ID of the active recipe associated with the given product variation.
     *
     * @param int $id  The product variation ID
     * @return int|null  The active recipe ID, or null if not found
     */
    private function getProductVariationRecipeId($id)
    {
        return Recipe::where('product_variation_id', $id)->where('is_active', 1)->pluck('id')->first();
    }


    public function validateRequest(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'quantity.*' => 'required|numeric|min:0.1',
            'unit_price.*' => 'required|numeric|min:0.1',
            'subtotal.*' => 'required|numeric|min:0.1',
            'subtotal_after_discount.*' => 'required|numeric|min:0.1',
            'payment_coa_id.*' => 'required',
            'payment_amount.*' => 'required|numeric|min:0.1',
        ]);



        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }
        return true; // Indicate that validation passed
    }


    public function createOrUpdateInvoiceMaster(Request $request,$id = null) 
    {
        $data = [
            'date'                  => $request->date,
            'reference_no'          => $request->reference_no,
            'status'                => $request->status,
            'party_id'              => $request->customer_id,
            'biller_id'             => Auth::user()->id,
            'waiter_id'             => $request->waiter_id,
        
            'serving_type'          => $request->serving_type,
            'table_no'              => $request->table_no,

            'total_quantity'        => $request->total_quantity,
            'total_cost_amount'     => $request->total_cost_amount,
            'subtotal_items'        => $request->subtotal_items,
            'subtotal_addons'       => $request->addon_total_amount,
            'subtotal_before_discount'=> $request->subtotal_items,

            'rider_name'              => $request->rider_name,
            'shipping_address'        => $request->shipping_address,
            'customer_phone'          => $request->customer_phone,
            'shipping_fee'            => $request->shipping_fee,
        
            'discount_type'         => $request->invoice_discount_type,
            'discount_value'        => $request->invoice_discount_value,
            'discount_amount'       => $request->invoice_discount_amount,

            'subtotal_after_discount' => $request->subtotal_items - $request->inovice_discount_amount,
        
            'tax_type'              => $request->tax_type,
            'tax_value'              => $request->tax_value,
            'tax_amount'            => $request->tax_amount,

            'grand_total'           => $request->grand_total,
        ];

        if($id)
        {
            $invoiceMaster = InvoiceMaster::find($id);
            $data['updated_by'] = Auth::user()->id;
            $invoiceMaster->update($data);
        }else{
            $newInvoiceNo = InvoiceMaster::generateInvoiceNo($this->type);
            $data['type'] = $this->type;
            $data['invoice_no'] = $newInvoiceNo;
            $data['created_by'] = Auth::user()->id;
            $invoiceMaster = InvoiceMaster::create($data);
        }


        return $invoiceMaster;
    }

    public function createInvoiceDetail(Request $request,$invoiceMaster)
    {
        $index = 0;
        for($i=0; $i < count($request->product_variation_id); $i++)
        {
            $invoice_detail = [
                'invoice_master_id'        => $invoiceMaster->id,
                'date'                     => $invoiceMaster->date,
                'invoice_no'               => $invoiceMaster->invoice_no,
                'type'                     => $invoiceMaster->type,
                'product_variation_id'     => $request->product_variation_id[$i],
                'unit_cost'                => $request->unit_cost[$i],
                'stock_barcode'            => $request->stock_barcode[$i],
                'variation_barcode'        => $request->variation_barcode[$i],
                'product_type'             => $request->product_type[$i],
                'unit_price'               => $request->unit_price[$i],
                'quantity'                 => $request->quantity[$i],
                'stock_quantity'           => $request->quantity[$i],//used for stock
                'subtotal'                 => $request->subtotal[$i],
                'discount_unit_price'      => $request->discount_unit_price[$i],
                'discount_amount'          => $request->discount_amount[$i],
                'discount_type'            => $request->discount_type[$i],
                'discount_value'           => $request->discount_value[$i],
                'subtotal_after_discount'  => $request->subtotal_after_discount[$i],
                'grand_total'              => $request->subtotal_after_discount[$i],

                'description'              => $request->description[$i],

            ];

            if ($request->parent_no[$i] == '') {            
                $invoice_detail['child_no'] = $index;
            }else{
                $index =$index + 1;
                $invoice_detail['parent_no'] = $index;


            }

            DB::table('invoice_details')->insert($invoice_detail);

            $recipe_id = $this->getProductVariationRecipeId($request->product_variation_id[$i]);

            if($recipe_id)
            {
                $recipe = Recipe::with('details')->find($recipe_id);
                $orderQty = $request->quantity[$i];

                foreach($recipe->details as $detail)
                {
                    $invoice_detail_recipe = [
                        'invoice_master_id'     => $invoiceMaster->id,
                        'date'                  => $invoiceMaster->date,
                        'invoice_no'            => $invoiceMaster->invoice_no,
                        'type'                  => $invoiceMaster->type,
                        'product_type' => 'recipe',
                        'product_variation_id' => $detail->product_variation_id,
                        'unit_id' => $detail->unit_id,
                        'quantity' => $orderQty,
                        'child_unit_value' => $detail->unit->child_unit_value,
                        'child_unit_qty' => ($orderQty * $detail->quantity)  / $detail->unit->child_unit_value,
                    
                        
                    ];

                    DB::table('invoice_details')->insert($invoice_detail_recipe);

                }
            }



        }
    }

    public function makeNarration(Request $request, $invoiceMaster)
    {
        $invoiceNo = $invoiceMaster->invoice_no;
        $discountAmount = $invoiceMaster->discount_amount;
        $taxAmount = $invoiceMaster->tax_amount;
        $grandTotal = $request->grand_total;
        
        $components = [];

        $components[] = "POS Sale";
        $components[] = "invoice no: $invoiceNo";
        $components[] = "amount: $grandTotal";

        if (!empty($request->reference_no)) {
            $components[] = "reference: " . $request->reference_no;
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



    public function createVoucherJournalInvoicePaymentEntries(Request $request,$invoiceMaster)
    {
       
        $invoiceAmount = $invoiceMaster->grand_total;
        $taxAmount = $invoiceMaster->tax_amount;
        $discountAmount = $invoiceMaster->discount_amount;
        $revenueAmount = $invoiceAmount + $discountAmount -$taxAmount; 
        $costAmount = $invoiceMaster->total_cost_amount;

        $narration = $this->makeNarration($request,$invoiceMaster);

        $data = [
            'invoice_master_id'  => $invoiceMaster->id,
            'date' => $request->date,
            'voucher_no' => $invoiceMaster->invoice_no,
            'code' => 'JV',
            'type' => 'Journal Voucher',
            'narration' => $narration,
            'created_by' => Auth::user()->id,
            'created_at' => now(),
        ];

       

        $voucher = $this->createVoucherMaster($data,$invoiceAmount);

        //update invoice master with voucher id
        $data = array_merge($data, [
            'voucher_id' => $voucher->id,
            'customer_id' => $request->customer_id,
        ]);

       


        
        // Debit AR Customer
        $this->arCustomerDebitEntry($data,$invoiceAmount);

        // Debit Sale Discount Given
        if($discountAmount > 0){
            $this->saleDiscountGivenDebitEntry($data,$discountAmount);
        }
        
        // Credit Sales Revenue
        $this->salesRevenueCreditEntry($data,$revenueAmount); 
        
        // Output Tax Revenue
        if($taxAmount > 0){
            $this->outputTaxCreditEntry($data,$taxAmount);
        }
        
        // Debit Cash/Bank/Card
        $this->cashBankCardDebitEntries($request, $data);
        
        // Credit AR Customer
        $this->arCustomerCreditEntry($data,$invoiceAmount); 
        
        // Debit Cogs(Actual Cost of Proudct) Finished Products 
        $this->cogsFinishedProductsDebitEntry($data,$costAmount);

        //Credit Stock Purchased 
        $this->stockPurchasedCreditEntry($data,$costAmount);
        
        // Invoice Payment Record stored in different table for cash register 
        $this->createInvoicePayments($request, $voucher);

        
        
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

    public function cashBankCardDebitEntries(Request $request, $data)
    {
        for($i=0; $i<count($request->payment_coa_id);$i++)
        {
            $debit = array_merge($data , [
                'chart_of_account_id' => $request->payment_coa_id[$i],
                'debit' => $request->payment_amount[$i],
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


    public function createInvoicePayments(Request $request, $voucher)
    {
        $paymentCoaIds = $request->payment_coa_id ?? [];
        $paymentAmounts = $request->payment_amount ?? [];
        $numPayments = count($paymentCoaIds);

        foreach ($paymentCoaIds as $i => $coaId) {
            $amount = $paymentAmounts[$i] ?? 0;
            if ($amount > 0) {
                $coa = ChartOfAccount::find($coaId);

                InvoicePayment::create([
                    'invoice_master_id'   => $voucher->invoice_master_id,
                    'voucher_no'          => $voucher->invoice_no,
                    'voucher_id'          => $voucher->id,
                    'date'                => now(),
                    'time'                => now(),
                    'number_of_payments'  => $numPayments,
                    'cash_register_id'    => $request->cash_register_id,
                    'mode'                => $coa->category ?? 'NA',
                    'chart_of_account_id' => $coaId,
                    'amount'              => $amount,
                    'created_by'          => Auth::id(),
                    'created_at'          => now(),
                ]);
            }
        }
    }
    
   
   















    public function deleteInvoiceDetailVoucherJournalRecords($invoice_master_id){
        DB::table('invoice_details')->where('invoice_master_id', $invoice_master_id)->delete();
        DB::table('vouchers')->where('invoice_master_id', $invoice_master_id)->delete();
        DB::table('voucher_details')->where('invoice_master_id', $invoice_master_id)->delete();
        DB::table('invoice_payments')->where('invoice_master_id', $invoice_master_id)->delete();
        DB::table('journals')->where('invoice_master_id', $invoice_master_id)->delete();
    }

    public function formatTaxLabel($taxType, $taxValue)
    {
        // Check if tax rate is valid (non-zero and positive)
        if ($taxValue <= 0) {
            return  __('file.Tax') .' (-)';
        }

        // Format tax label based on type
        if ($taxType == 'inclusive') {
            return  __('file.Tax').' '.$taxValue . '% incl';
        } else if ($taxType === 'exclusive') {
            return __('file.Tax').' '.$taxValue . '% excl';
        }

        // Return 'No Tax' if no valid tax type is found
        return 'No Tax';
    }


    public function commonData()
    {
        return [
            'products' => Product::getPosProducts(),
            'addons' => ProductVariation::where('product_type', 'addon')->get(),
            'categories' => Category::all(),
            'brands' => Brand::all(),
            'staff' => User::all(),
            'servingTables' => DB::table('serving_tables')->get(),
            'biller' => User::find(Auth::user()->id),
            'customers' => Party::where('party_type', 'customer')->orderBy('id', 'desc')->get(),
            'chart_of_accounts' => ChartOfAccount::where('level', 4)
            ->whereIn('category', ['card', 'cash', 'bank', 'ecommerce'])
            ->orderByRaw("FIELD(category, 'bank', 'cash', 'card', 'ecommerce')")
            ->get(),
            'taxType' => PosSetting::first()->tax_type,
            'taxValue' => (int) PosSetting::first()->tax_value,
            'formatTaxLabel' => $this->formatTaxLabel(
                PosSetting::first()->tax_type,
                (int) PosSetting::first()->tax_value
            ),
            'expenseCoas' => ChartOfAccount::getExpenseAccountsByUserType(),
            'suppliers' =>Party::whereIn('party_type',['supplier','both'])->get(),
            'expensePaidTruAccounts' => ChartOfAccount::getPettyCashAccount(),
            'users' => User::all(),


        ];
    }



    public function storeExpenseMaster(Request $request)
    {
        $newExpenseNo = Expense::generateExpenseNo('EXP');

        $taxAmount = $this->calculateExpenseTax($request->expense_amount,$request->expense_tax_value);

        $data = [
            'date' => $request->expense_date,
            'expense_no' => $newExpenseNo,

            'party_id' => $request->expense_supplier_id,
            'chart_of_account_id' => $request->expense_paid_by_COA,
            'cash_register_id' => $request->cash_register_id,

            'expense_type' =>$request->expense_type,
            'reference_no' =>$request->expense_reference_no,

            'total_amount' => $request->expense_amount,
            'total_tax_amount' => $taxAmount,
            'grand_total' => $request->expense_amount,
            'description' => $request->input('expense_narration'),
            'attachment' => $request->file('attachment'), // For handling file uploads
            'spend_by' => $request->expense_spend_by,
            'created_by' => Auth::user()->id,
            'created_at' => now(),
        ];

            
        // Check if the file input is provided
        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment'); 
            $attachmentName = time() . '.' .  $attachment->extension();
            
            // Move the file to the desired folder
            $attachment->move(public_path('attachments/expense-order'), $attachmentName);
            
            // Store the filename in the data array
            $data['attachment'] = $attachmentName; 
        }
        
        $expense  = Expense::create($data);

        return $expense;

    }

    public function storeExpenseDetailsAndJournalEntries(Request $request, $expense)
    {
        DB::table('expense_details')->insert([
            'date' => $expense->date,
            'expense_no' => $expense->expense_no,
            'expense_id' => $expense->id,
            
            'tax_type' => 'inclusive',
            'tax_percentage' => $request->expense_tax_value,
            'tax_amount' => $expense->total_tax_amount,

            'chart_of_account_id' => $request->expense_paid_for_COA,
            'description' => $request->expense_narration,
            'amount' => $request->expense_amount - $expense->total_tax_amount,
            'total' => $request->expense_amount,
        ]);

        $common = [
            'date' => $expense->date,
            'voucher_no' => $expense->expense_no,
            'type' => 'expense',
            'expense_id' => $expense->id,
            'party_id' => $request->expense_supplier_id,
            'narration' => $request->expense_narration,
            'created_by' => Auth::user()->id,
            'created_at' => now(),
        ];

        DB::table('journals')->insert(array_merge($common,[
            'chart_of_account_id' => $request->expense_paid_for_COA,
            'debit' => $request->expense_amount - $expense->total_tax_amount,
        ]));

        if($expense->total_tax_amount > 0)
        {
             DB::table('journals')->insert(array_merge($common,[
                'chart_of_account_id' => config('coa.adv_tax_paid_input_tax'),
                'narration' => "Tax inclusive on expense ". $expense->expense_no,
                'debit' => $expense->total_tax_amount,
            ]));
        }
        


        DB::table('journals')->insert(array_merge($common,[
            'chart_of_account_id' => $expense->chart_of_account_id,
            'credit' => $request->expense_amount ,
        ]));
           
    }

  
    public function calculateExpenseTax($amount, $expense_tax_value)
    {
        if ($expense_tax_value <= 0 || $amount <= 0) {
            return 0.00;
        }
        $taxRate = $expense_tax_value / 100;
        $taxAmount = $amount - ($amount / (1 + $taxRate));
        return round($taxAmount, 2);
    }
    
   

    



}
 /*
        public function createVoucherJournalInvoicePaymentEntries1(Request $request,$invoiceMaster)
        {
            
            $invoice_master_id = $invoiceMaster->id;
            $invoice_no = $invoiceMaster->invoice_no;
            $refNo = $request->reference_no != null ? ' reference: '.$request->reference_no : '';

            $narration = "POS Sale ". ' invoice no: '.$invoice_no.' amount: '.$request->grand_total.$refNo;
            //create voucher 
            $voucher_id = DB::table('vouchers')->insertGetId([
                'invoice_master_id'     => $invoice_master_id,
                'date' => $request->date,
                'voucher_no' => $invoice_no,
                'code' => 'JV',
                'type' => 'Journal Voucher',
                'narration' => $narration,
                'total_amount' => $request->grand_total,
                'created_by' => Auth::user()->id,
                'created_at' => now(),
            ]);

            

            $commonData = [
                'invoice_master_id' => $invoice_master_id,
                'date'              => $request->date,
                'voucher_no'        => $invoice_no,
                'voucher_id'        => $voucher_id,
                'code'              => 'JV',
                'customer_id'       => $request->customer_id,
                'narration'         => $narration,
                'created_at'        => now(),
            ];




            // Debit AR Customer
            $arCustomer_debit = array_merge($commonData , [
                'chart_of_account_id' => config('coa.ar_customers'),
                'debit' => $request->grand_total,
            ]);
            DB::table('voucher_details')->insert($arCustomer_debit);
            DB::table('journals')->insert($arCustomer_debit);


            // Credit Sales Revenue
            $salesRevenue_credit = array_merge($commonData , [
                'chart_of_account_id' => config('coa.sales_revenue'),
                'credit' => $request->grand_total,
            ]);
            DB::table('voucher_details')->insert($salesRevenue_credit);
            DB::table('journals')->insert($salesRevenue_credit);


            // Debit Cash/Bank/Card
            for($i=0; $i<count($request->payment_coa_id);$i++)
            {
                $debit = array_merge($commonData , [
                    'chart_of_account_id' => $request->payment_coa_id[$i],
                    'debit' => $request->payment_amount[$i],
                ]); 
                DB::table('voucher_details')->insert($debit);
                DB::table('journals')->insert($debit);
            }

            
            // Credit AR Customer
            $arCustomer_credit = array_merge($commonData , [
                'chart_of_account_id' => config('coa.ar_customers'),
                'credit' => $request->grand_total,
            ]);
            DB::table('voucher_details')->insert($arCustomer_credit);
            DB::table('journals')->insert($arCustomer_credit);


        







            // Invoice Payment Record stored in different table for cash register 
            for($i=0; $i<count($request->payment_coa_id);$i++)
            {
                $coa = ChartOfAccount::find($request->payment_coa_id[$i]);
                            
            InvoicePayment::create([
                    'invoice_master_id' => $invoice_master_id,
                    'mode' => $coa->category,
                    'date' => now(),
                    'time' => now(),
                    'number_of_payments' => count($request->payment_coa_id),
                    'voucher_no' => $invoice_no,
                    'voucher_id' => $voucher_id,
                    'chart_of_account_id' => $request->payment_coa_id[$i],
                    'amount' => $request->payment_amount[$i],
                    'cash_register_id' => $request->cash_register_id,
                    'created_by' => Auth::id()
                ]);

            }

        }
    */