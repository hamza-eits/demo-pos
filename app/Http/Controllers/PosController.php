<?php

namespace App\Http\Controllers;

use App\Models\Pos;
use App\Models\Recipe;
use App\Models\Product;
use App\Models\CashRegister;
use App\Services\PosService;
use Illuminate\Http\Request;
use App\Models\InvoiceMaster;
use App\Models\InvoicePayment;
use App\Models\ProductVariation;
use Yajra\DataTables\DataTables;
use App\Models\Accounting\Expense;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Accounting\ChartOfAccount;
use App\Models\Accounting\ExpenseDetail;
use App\Models\Startup\Company;
use Illuminate\Support\Facades\Validator;

class PosController extends Controller
{
    protected $posService;
    public function __construct(PosService $posService) {
        $this->posService = $posService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {


        try {
            if ($request->ajax()) {
                $data = InvoiceMaster::query()
                
                 ->when($request->start_date, function ($query, $startDate) {
                    return $query->where('date', '>=', $startDate);
                })
                ->when($request->end_date, function ($query, $endDate) {
                    return $query->where('date', '<=', $endDate);
                })
                ->where('type', 'INV')
                ->orderByDesc('date')
                ->orderByDesc('id')
                ->get();

                return Datatables::of($data)
                    ->addIndexColumn()
                    // Status toggle column
                    ->addColumn('date', fn($row) => date('d-m-Y', strtotime($row->date)))
                    ->addColumn('partyName', fn($row) => $row->party->business_name ?? 'N/A')
                    ->addColumn('billerName', fn($row) => $row->biller->name ?? 'N/A')
                    ->addColumn('paymentMode', function($row){

                        return $row->status == 'completed' ? InvoiceMaster::paymentModeNames($row->payments) : '-';
                      
                    })

                    ->addColumn('action', function ($row) {
                        $btn = '
                        <div class="d-flex align-items-center col-actions">
                            <div class="dropdown">
                                <a class="action-set show" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="true">
                                    <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a href="' . route('pos.printInvoice', $row->id) . '"  class="dropdown-item">
                                            <i class="bx bx-show font-size-16 text-warning me-1"></i> View
                                        </a>
                                    </li>
                                    <li>
                                        <a href="' . route('point-of-sale.edit', $row->id) . '"  class="dropdown-item">
                                            <i class="bx bx-pencil font-size-16 text-primary me-1"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" onclick="deleteRecord(' . $row->id . ')" class="dropdown-item">
                                            <i class="bx bx-trash font-size-16 text-danger me-1"></i> Delete
                                        </a>
                                    </li>
                                    
                                    
                                </ul>
                            </div>
                        </div>';


                        return $btn;
                    })
                    ->rawColumns(['action']) // Mark these columns as raw HTML
                    ->make(true);
            }

            return view('pos.index');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return back()->with('flash-danger', $e->getMessage());
        }
    }

   

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       
        $data = $this->posService->commonData();

        return view('pos.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) 
    {
        DB::beginTransaction();
        try {

        $response = $this->posService->validateRequest($request);    

        if ($response !== true) {
            return $response; // return error response if validation failed
        }

            $invoiceMaster = $this->posService->createOrUpdateInvoiceMaster($request);
        
            $this->posService->createInvoiceDetail($request,$invoiceMaster);

            $status = $request->status;
        
            if($status == "completed")
            {
                $this->posService->createVoucherJournalInvoicePaymentEntries($request,$invoiceMaster);
            }    

            



            DB::commit();
            return response()->json([
                'message' => "Order Added Successful". $invoiceMaster->invoice_no,
                'success' => true,
                'id' => $invoiceMaster->id
            ], 200);    
        
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    

    public function edit($id)
    {
        $order = InvoiceMaster::with(['details' =>function($query){
            $query->whereNotNull('parent_no');
        }])->find($id);       
        
        $data = $this->posService->commonData();
        $data['order'] = $order;
        $data['id'] = $id;
        

        
        return view('pos.edit', $data); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            $response = $this->posService->validateRequest($request);    

            if ($response !== true) {
                return $response; // return error response if validation failed
            }
 
            $invoiceMaster = $this->posService->createOrUpdateInvoiceMaster($request,$id);
            
            $this->posService->deleteInvoiceDetailVoucherJournalRecords($id);

            $this->posService->createInvoiceDetail($request,$invoiceMaster);
 
            $status = $request->status;
            
             if($status == "completed")
             {
                 $this->posService->createVoucherJournalInvoicePaymentEntries($request,$invoiceMaster);
             }    
            
             
            DB::commit();
            return response()->json([
                'message' => "Order Update Successful",
                'success' => true,
            ], 200);
         
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();// Start a transaction

        try {
            $invoiceMaster = InvoiceMaster::find($id);

            if($invoiceMaster){

                $this->posService->deleteInvoiceDetailVoucherJournalRecords($invoiceMaster->id);
                $invoiceMaster->delete();// Delete the brand record
            }



            DB::commit();// Commit the transaction
            
            return response()->json([
                'success' => true,
                'message' => 'Record Delete successfully.',
                ],200);
                
        } catch (\Exception $e) {
          
            DB::rollBack();  // Rollback the transaction if there is an error

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    
    }

    public function fetchProducts(Request $request)
    {

        $filters = [
            'category_id' => $request->get('category_id') ?? null,
            'brand_id'    =>  $request->get('brand_id') ?? null,
        ];

        // Query the products with category and brand filters
        $products = Product::query()
        ->posProducts()
        ->applyPosFilters($filters) // Filter by category/brand, or limit to 30 results if no filters are applied
        ->get();
           

        // Load the products in the view
        return view('pos.render.product_table', compact('products'));
    }

    public function searchPurchaseWiseProductVariation(Request $request)
    {
        // Get the search query from the request
        $input = $request->get('query');

        // Search for products that match the query
        $products = DB::table('v_purchase_wise_product_variation')
            ->where('stock', '>', 0)
            ->where(function($query) use ($input) {
                $query->where('name', 'LIKE', "%{$input}%")
                ->orWhere('stock_barcode', 'LIKE', "%{$input}%");
            })
            // ->take(20)
            ->get();

        // Return the results as JSON
        return response()->json($products);
    }

    public function searchProductVariationInventory(Request $request)
    {
        // Get the search query from the request
        $query = $request->get('query');
        
       
        // Search for products that match the query
        $products = DB::table('v_product_variation_inventory')
            ->where('product_code', 'LIKE', "%$query%")
            ->orWhere('name', 'LIKE', "%$query%")
            ->take(20)
            ->get();


        // Return the results as JSON
        return view('pos.render.product_variation_inventory_table', compact('products'));
    }

    public function searchProductVariations(Request $request)
    {
        // Get the search query from the request
        $query = $request->get('query');

        // Search for products that match the query
        $products = ProductVariation::with([
            'product:id,name,description,is_decimal_qty_allowed,is_price_editable'
        ])->where('name', 'LIKE', "%$query%")
            ->orWhere('barcode', 'LIKE', "%$query%")
            ->get();

        // Return the results as JSON
        return response()->json($products);
    }


    public function fetchProductVariations(Request $request)
    {
        $productId = $request->input('product_id');

        // Fetch the variations for the product
        $variations = ProductVariation::where('product_id', $productId)->get();

        // Return the variations as JSON
        return view('pos.render.product_variation', compact('variations'));
    }


    public function fetchTodayCompletedOrders()
    {
        $todayDate = date('Y-m-d');

        $orders = InvoiceMaster::where('date', $todayDate)
            ->ofTypeInvoice()
            ->where('status', 'completed')
            ->orderBy('id', 'desc')
            ->get();

        return view('pos.render.completed_orders', compact('orders'));
    }
    public function fetchTodayDraftOrders()
    {
        $todayDate = date('Y-m-d');

        $orders = InvoiceMaster::where('date', $todayDate)
            ->ofTypeInvoice()
            ->where('status', 'draft')
            ->orderBy('id', 'desc')
            ->get();

        return view('pos.render.draft_orders', compact('orders'));
    }

    public function printInvoice($id)
    {
        $order = InvoiceMaster::with(['details' => function ($query) {
            $query->posProductTypes();
        }])->find($id);

        $data = [];


        $data [] = [
            'name' => 'Subtotal',
            'value' => $order->subtotal_before_discount,
        ];
        //discount
        if($order->discount_amount > 0)
        {
            $label = $order->discount_type === 'percentage'
            ? 'Discount (' . $order->discount_amount . '%)'
            : 'Discount (Fixed)';

            $data [] = [
                'name' => $label,
                'value' => '-'.$order->discount_amount,
            ];
        }
        //tax
        if($order->tax_amount > 0)
        {
            $label = $order->tax_type === 'inclusive'
            ? __('file.Tax') . ' (Incl. ' . number_format($order->tax_value) . '%)'
            : __('file.Tax') . ' (Excl. ' . number_format($order->tax_value) . '%)';

            $data [] = [
                'name' => $label,
                'value' => $order->tax_amount,
            ];
        }

        $company = Company::first();

        $showAddress = 1;
        $showEmail = 1;
        $showContactNo = 0;
        $showCompanyName = 0;
        $showWebsite = 0;

        if($showCompanyName){
            $invoiceHeader [] = [ 'value' => $company->name ];
        }
        if($showContactNo){
            $invoiceHeader [] = [ 'value' => $company->contact_no ];
        }
        if($showWebsite){
            $invoiceHeader [] = [ 'value' => $company->website ];
        }
        if($showEmail){
            $invoiceHeader [] = [ 'value' => $company->email ];
        }
        
        if($showAddress){
            $invoiceHeader [] = [ 'value' => $company->address ];
        }

        // return response()->json($invoiceHeader);

        return view('pos.render.print_invoice', compact('order','data','invoiceHeader'));
    }
    public function printKot($id)
    {
        $order = InvoiceMaster::with(['details' => function ($query) {
            $query->posProductTypes();
        }])->find($id);




        return view('pos.render.print_kot', compact('order'));
    }
    public function fetchCashRegisterSummary(Request $request)
    {
        $cash_register_id = $request->cash_register_id;

        $cashRegsiter = CashRegister::find($cash_register_id);

        $openingDate = date('Y-m-d', strtotime($cashRegsiter->opened_at));

        $expenseAmount = Expense::whereDate('date','>=',$openingDate)
        ->where('created_by',Auth::id())
        ->sum('grand_total');

        $cashSale = $this->getCategorySaleAmount($cash_register_id, 'cash');
        $cardSale = $this->getCategorySaleAmount($cash_register_id, 'card');
        $bankSale = $this->getCategorySaleAmount($cash_register_id, 'bank');

        $openingCash = $cashRegsiter->opening_cash;
        $totalSale = $openingCash + $cashSale + $cardSale + $bankSale;
        $closingCash = ($openingCash + $cashSale) - $expenseAmount;


        $data = [
            [
                'title' => "Opening Cash <span class='badge bg-primary'>A</span>",
                'amount' => $openingCash,
                'name'  => 'opening_cash',
            ],
            [
                'title' => "Cash Payment <span class='badge bg-secondary'>B</span>",
                'amount' => $cashSale,
                'name'  => 'cash_sale',
            ],
            [
                'title' => "Card Payment",
                'amount' => $cardSale,
                'name'  => 'card_sale',
            ],
            [
                'title' => "Bank Payment",
                'amount' => $bankSale,
                'name'  => 'bank_sale',
            ],
            [
                'title' => "Total Sales Amount",
                'amount' => $totalSale,
                'name'  => 'total_sale',
            ],
            [
                'title' => "Total Expense Amount <span class='badge bg-danger'>C</span>",
                'amount' => $expenseAmount,
                'name'  => 'total_expese_amount',
            ],
            [
                'title' => "Total Cash Debit",
                'amount' => $openingCash + $cashSale,
                'name'  => 'total_cash_debit',
            ],
            [
                'title' => "Total Cash Credit",
                'amount' => $expenseAmount,
                'name'  => 'total_cash_credit',
            ],
            [
                'title' => "<b>Closing Cash <span class='badge bg-primary'>A</span> + <span class='badge bg-secondary'>B</span> - <span class='badge bg-danger'>C</span>",
                'amount' => $closingCash,
                'name'  => 'closing_cash',
            ],
        ];



        return view(
            'pos.render.cash_register_summary',
            compact('cashRegsiter', 'data')
        );
    }

    private function getCategorySaleAmount($cash_register_id, $category)
    {
        $coa_ids = ChartOfAccount::where('category', $category)
            ->where('level', 4)
            ->pluck('id');

        $saleAmount = InvoicePayment::where('cash_register_id', $cash_register_id)
            ->whereIn('chart_of_account_id', $coa_ids)
            ->sum('amount');

        return $saleAmount;
    }


    public function storeExpense(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'expense_paid_for_COA' => 'required',
            'expense_supplier_id' =>'required',
            'expense_paid_for_COA' =>'required',
            'expense_narration' =>'required',
            'expense_amount' =>'required',
            'cash_register_id' =>'required',
        ]);
    

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }
         
        // Start a transaction
        DB::beginTransaction();
        try {


            
            $expense  = $this->posService->storeExpenseMaster($request);
            
            $this->posService->storeExpenseDetailsAndJournalEntries($request, $expense);


            DB::commit();// Commit the transaction

            // Return a JSON response with a success message
            return response()->json([
                'success' => true,
                'message' => 'Expense added Successfully',
            ],200);
        

            
        } catch (\Exception $e) {
            
            DB::rollBack();// Rollback the transaction if there's an error

            // Return a JSON response with an error message
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // function addProductToOrderItemTable(id, name, stockBarcode, variationBarcode ,unitPrice,unitCost, type, description,isPriceEditable,isDecimalQtyAllowed) {

    public function processBarcodeScan($barcode)
    {
        try {
            // Extract item code and price from the barcode
            $barcodeLength = strlen($barcode);

            // The first 7 digits represent the item code
            // $itemCode = substr($barcode, 0, 7);
            $itemCode = substr($barcode, 1, 5);

            // The remaining digits represent the price (in thousandths)
            // $priceDigits = substr($barcode, 7);
            $priceDigits = substr($barcode, 6);


            // Convert the price digits to a float value (e.g., "12345" => 12.34)
            $totalPrice = $this->convertToPriceFormat($priceDigits);

            $productVariation = ProductVariation::where('barcode', $itemCode)->first();

            if (!$productVariation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found',
                ], 404);
            }

            if ($barcodeLength < 11) {
                return response()->json([
                    'success' => false,
                    'message' => 'Barocde length connot be less then 11',
                ], 404);
            }


            $sellingPrice = $productVariation->selling_price;
            $netWeight = round($totalPrice / $sellingPrice, 3);

            return response()->json([
                'id' => $productVariation->id,
                'name' => $productVariation->name,
                'stockBarcode' => $productVariation->barcode,
                'variationBarcode' => $productVariation->barcode,
                'unitPrice' => $productVariation->selling_price,
                'unitCost' => $productVariation->purchase_price,
                'type' => $productVariation->product_type,
                'description' => $productVariation->product->description ?? '',
                'isPriceEditable' => $productVariation->product->is_price_editable,
                'isDecimalQtyAllowed' => $productVariation->product->is_decimal_qty_allowed,
                'quantity' => $netWeight,
                'subtotal' => $totalPrice,
                 'success' => true,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }
    }

    /**
     * Convert a barcode-encoded number to a price format.
     *
     * The input number is a string where the last three digits represent the decimal part (thousandths).
     * This function pads the number to at least 4 digits, splits it into integer and decimal parts,
     * then combines them as a float with two decimal places (truncating, not rounding).
     *
     * Example:
     *   Input:  "12345"   (represents 12.345)
     *   Output: 12.34     (truncated to two decimals)
     *
     * @param string $number The numeric string from the barcode.
     * @return float The formatted price as a float with two decimals.
     */
    function convertToPriceFormat($number) {

       
        // Ensure the number is at least 4 digits (pad with zeros on the left if needed)
        $number = str_pad($number, 4, "0", STR_PAD_LEFT);
        
        // Extract integer part (all but last 3 digits)
        $intPart = substr($number, 0, -3);
        // Extract decimal part (last 3 digits)
        $decimalPart = substr($number, -3);
         
        // Combine integer and decimal parts as a string (e.g., "12.345")
        $full = $intPart . '.' . $decimalPart;

        // Truncate to two decimal places (no rounding)
        $truncated = substr($full, 0, strpos($full, '.') + 3);
       
        // Convert to float and return
        return floatval($truncated);
    }
    



    

   
    

}
