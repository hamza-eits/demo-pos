<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Startup\Tax;
use Illuminate\Support\Str;
use App\Models\Startup\Unit;
use Illuminate\Http\Request;
use App\Models\InvoiceDetail;
use App\Models\InvoiceMaster;
use App\Models\Startup\Party;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ProductVariation;
use App\Models\PurchaseCategory;
use Yajra\DataTables\DataTables;
use App\Models\Accounting\Journal;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Illuminate\Support\Facades\Validator;

class PurchaseInvoiceController extends Controller
{
    protected $type;

    public function __construct()
    {
        $this->type = 'PI';
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        

        try{
            if ($request->ajax()) {
                $data = InvoiceMaster::where('type',$this->type)
                ->orderBy('date', 'desc')
                ->orderBy('id', 'desc')
                ->get();
    
                return Datatables::of($data)
                    ->addIndexColumn()
                    // Status toggle column

                    ->addColumn('supplier_name', function($row){
                        return $row->party->business_name ?? 'N/A';
                    })
                    ->addColumn('date', function($row){
                        return date('d-m-Y', strtotime($row->date)) ?? 'N/A';
                    })
                    ->addColumn('due_date', function($row){
                        return  $row->due_date ? date('d-m-Y', strtotime($row->due_date)) : '-';
                    })
                    ->addColumn('credit_period', function($row) {
                        if ($row->date && $row->due_date) {
                            $start = Carbon::parse($row->date);
                            $end = Carbon::parse($row->due_date);
                            return $start->diffInDays($end) . ' days';
                        }
                        return '-';
                    })
                   

                    ->addColumn('action', function ($row) {
                        
                        
                        $btn = '
                            <div class="d-flex align-items-center col-actions">
                                <div class="dropdown">
                                    <a class="action-set show" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="true">
                                        <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">';
                                    
                                    $btn .= '        
                                        <li>
                                            <a href="'.route('purchase-invoice.show', $row->id).'" class="dropdown-item">
                                                <i class="bx bx-show font-size-16 text-primary me-1"></i> View
                                            </a>
                                        </li>
                                        <li>
                                            <a href="'.route('purchase-invoice.edit', $row->id).'" class="dropdown-item">
                                                <i class="bx bx-pencil font-size-16 text-warning me-1"></i> Edit
                                            </a>
                                        </li>
                                         <li>
                                                <a href="javascript:void(0)" onclick="deletePurchaseInvoice(' . $row->id . ')" class="dropdown-item">
                                                    <i class="bx bx-trash font-size-16 text-danger me-1"></i> Delete
                                                </a>
                                            </li>
                                        
                                        
                                        ';
                                        
                                        if($row->is_locked == 0)
                                        {
                                            $btn .= '
                                            <li>
                                               <a  href="javascript:void(0)" onclick="lock(' . $row->id . ')" class="dropdown-item">
                                                   <i class="bx bx-lock-alt font-size-16 text-dark me-1"></i> Lock
                                               </a>
                                           </li>';
                                        }else{

                                            $btn .= '
                                                <li>
                                                    <a target="_blank" href="'.route('purchase-invoice.barcode-pdf', $row->id).'" class="dropdown-item">
                                                        <i class="bx bxs-file-pdf font-size-16 text-danger me-1"></i> Barcode PDF
                                                    </a>
                                                </li>';
                                            $btn .= '
                                                <li>
                                                    <a target="_blank" href="'.route('purchase-invoice.barcode-pdf', [$row->id,true]).'" class="dropdown-item">
                                                        <i class="bx bx-download font-size-16 text-danger me-1"></i> Barcode PDF 
                                                    </a>
                                                </li>';
                                        }
                                        
                                       
                                    
                                    
                                $btn .= ' 
                                    </ul>
                                </div>
                            </div>';
                        
    
                   
                    return $btn;
                   
                    })
    
                    ->rawColumns(['action']) // Mark these columns as raw HTML
                    ->make(true);
            }
    
            return view('purchase_invoices.index');

        }catch (\Exception $e){

            return back()->with('flash-danger', $e->getMessage());
        }
        
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productVariations = ProductVariation::purchaseInvoice()->get();
        $taxes = Tax::all();
        $units = Unit::all();
        $purchaseCategories = PurchaseCategory::all();
        $suppliers = Party::where('party_type','supplier')->get();
        return view('purchase_invoices.create', compact('productVariations','purchaseCategories','taxes','suppliers','units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Start a transaction
        DB::beginTransaction();

        try {

            $validator = $this->validateRequest($request);


            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ]);
            }

            $invoiceMaster = $this->createOrUpdateInvoiceMaster($request,null);
            

            $this->createInvoiceDetails($request, $invoiceMaster);

            $this->journalEntries($invoiceMaster->id);


            // $this->generateVariationBarcodes($invoiceMaster->id);

            DB::commit();// Commit the transaction

            // Return a JSON response with a success message
            return response()->json([
                'success' => true,
                'message' => 'Purchase Invoice added successfully.',
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $purchaseInvoice = InvoiceMaster::with('details')->find($id);
        return view('purchase_invoices.show', compact('purchaseInvoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $purchaseInvoice = InvoiceMaster::with('details')->find($id);
        $productVariations = ProductVariation::purchaseInvoice()->get();
        $taxes = Tax::all();
        $units = Unit::all();
        $purchaseCategories = PurchaseCategory::all();

        $suppliers = Party::where('party_type','supplier')->get();
        return view('purchase_invoices.edit', compact('purchaseInvoice','purchaseCategories','productVariations','taxes','suppliers','units'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();

        try {

            $validator = $this->validateRequest($request);


            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ]);
            }

            $invoiceMaster = $this->createOrUpdateInvoiceMaster($request,$id);
            
            
            $invoiceMaster->details()->delete();
            $invoiceMaster->journals()->delete();

            $this->createInvoiceDetails($request, $invoiceMaster);

            $this->journalEntries($invoiceMaster->id);

            DB::commit();// Commit the transaction

            // Return a JSON response with a success message
            return response()->json([
                'success' => true,
                'message' => 'Purchase Invoice added successfully.',
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {


        DB::beginTransaction();// Start a transaction

        try {
            $data = InvoiceMaster::find($id);
            $data->details()->delete();

            $data->delete();// Delete the brand record

            DB::commit();// Commit the transaction
            
            return response()->json([
                'success' => true,
                'message' => 'Purchase Invoice Delete successfully.',
                ],200);
                
        } catch (\Exception $e) {
          
            DB::rollBack();  // Rollback the transaction if there is an error

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
        
    }

    private function validateRequest(Request $request)
    {
        return Validator::make($request->all(), [
            'supplier_id' => 'required',
            'reference_no' => 'required',
            'date' => 'required',
            'product_variation_id.*' => 'required',
            'purchase_category.*' => 'required',
            'quantity.*' => 'required',
            'unit_price.*' => 'required',
        ]);
    }
    private function createInvoiceDetails(Request $request, $invoiceMaster)
    {
        for($i=0; $i<count($request->product_variation_id); $i++)
        {
            $invoiceDetail = InvoiceDetail::create([
                'invoice_master_id'     => $invoiceMaster->id,
                'date'                  => $invoiceMaster->date,
                'type'                  => $invoiceMaster->type,
                'invoice_no'            => $invoiceMaster->invoice_no,
                    
                'unit_id'               => $request->unit_id[$i],
                'product_variation_id'  => $request->product_variation_id[$i],
                'selling_price'         => $request->selling_price[$i],

                'variation_barcode'      => $request->variation_barcode[$i],
                'purchase_category'      => $request->purchase_category[$i],
                'stock_barcode'          => $request->variation_barcode[$i].'-'.$request->purchase_category[$i],


                'product_type'          => $request->product_type[$i],
                'unit_price'            => $request->unit_price[$i],
                'quantity'              => $request->quantity[$i],
                'subtotal'              => $request->subtotal[$i],

                //calclated on front end Base Unit * Qty
                'stock_quantity'        => $request->base_unit_qty[$i], 
                
                'tax_type'              => $request->tax_type[$i],
                'tax_value'             => $request->tax_value[$i],
                'tax_amount'            => $request->tax_amount[$i],
                'subtotal_after_tax'    => $request->subtotal_after_tax[$i],
                'grand_total'           => $request->subtotal_after_tax[$i],


                'base_unit_value' =>  $request->base_unit_value[$i],
                'base_unit_qty' =>  $request->base_unit_qty[$i],
            ]); 
        }
    }

    private function createOrUpdateInvoiceMaster(Request $request,$id = null)
    {
        $data = [
            'date' => $request->date,
            'due_date' => $request->due_date,
            'reference_no' => $request->reference_no,
            'status' => $request->status,
            'payment_mode' => $request->payment_mode,
            'subject' => $request->subject,
            'description' => $request->description,
            'party_id' => $request->supplier_id,
            'total_quantity' => $request->total_quantity,
            'subtotal' => $request->purchase_subtotal,
            'tax_amount' => $request->purchase_tax_amount,
            'grand_total' => $request->purchase_grand_total,
        ];

        if($id) {
            $invoiceMaster = InvoiceMaster::findOrFail($id);
            $data['updated_by'] = Auth::id();
            $invoiceMaster->update($data);
        } else {
            $data['type'] = $this->type;
            $data['invoice_no'] = InvoiceMaster::generateInvoiceNo($this->type);
            $data['created_by'] = Auth::id();
            $invoiceMaster = InvoiceMaster::create($data);
        }
        return $invoiceMaster;
    }


    public function lock($id)
    {
        $invoiceMaster = InvoiceMaster::find($id);
        if($invoiceMaster)
        {
            $invoiceMaster->update(
                ['is_locked' => 1]
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Purchase Lock successfully.',
            ],200);
        }
        

    }

    public function barcodePdf($id,$download = false, $product_variation_id = null, $is_single = null)
    {
        $invoiceMaster = InvoiceMaster::findOrFail($id);

        $invoiceDetail = DB::table('invoice_details')
        ->where('invoice_master_id',$invoiceMaster->id)
        ->when($product_variation_id, function($query) use ($product_variation_id){
            return $query->where('product_variation_id', $product_variation_id);
        })
        ->get();


        $data = [];

        foreach($invoiceDetail as $detail)
        {
            $generator = new BarcodeGeneratorPNG();
            $barcode = $generator->getBarcode($detail->variation_barcode, $generator::TYPE_CODE_128,1,30);
            $quantity = $is_single ? 1 : $detail->quantity;


            $variation = ProductVariation::find($detail->product_variation_id);
            for($i=0; $i < $quantity; $i++)
            {
                $data[] = [
                    'barcode' => $barcode,
                    'label' => $detail->stock_barcode,
                    'price' => $variation->selling_price." AED"
                ];
            }
        }

        // Generate PDF
        $fileName = 'Label of Invoice No: '.$invoiceMaster->invoice_no . '.pdf';
        $pdf = Pdf::loadView('purchase_invoices.barcode_pdf', compact('data'));

        // Return PDF response
        return $download ? $pdf->download($fileName) : $pdf->stream();


    }


    private function journalEntries($id)
    {
        $invoiceMaster = InvoiceMaster::findOrFail($id);
                    $grandTotal = $invoiceMaster->grand_total;


        $commonData = [
                'date' => $invoiceMaster->date,
                'voucher_no' => $invoiceMaster->invoice_no,
                'type' => $invoiceMaster->type,
                'supplier_id' => $invoiceMaster->party_id,
                'invoice_master_id' => $invoiceMaster->id,
                'created_by' => Auth::user()->id,
                'created_at' => now()
            ];
            $narration = 'Stock Purchased (Ref.) INV# '.$invoiceMaster->reference_no;
        
            $inventoryDebit = array_merge($commonData, [
                'chart_of_account_id' => config('coa.inventory_raw_material'),
                'narration' => $narration,
                'debit' => $grandTotal,
            ]);
            Journal::create($inventoryDebit);
        
            $accountsPayableCredit = array_merge($commonData, [
                'chart_of_account_id' => config('coa.ap_suppliers'),
                'narration' => $narration,
                'credit' => $grandTotal,
            ]);
            Journal::create($accountsPayableCredit);

    }

   

    
}
