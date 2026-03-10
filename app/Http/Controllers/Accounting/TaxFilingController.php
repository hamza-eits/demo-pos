<?php

namespace App\Http\Controllers\Accounting;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\InvoiceMaster;
use App\Models\Startup\Party;
use Termwind\Components\Span;
use Yajra\DataTables\DataTables;
use App\Models\Accounting\Expense;
use App\Models\Accounting\Journal;
use App\Models\Accounting\Voucher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Models\Accounting\TaxFiling;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\Accounting\VoucherType;
use App\Models\Accounting\ChartOfAccount;
use Illuminate\Support\Facades\Validator;
use App\Models\Accounting\TaxFilingDetail;

class TaxFilingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

      
        try{
            if ($request->ajax()) {


                $data = TaxFiling::all();
                return Datatables::of($data)
                    ->addIndexColumn()
                    // Status toggle column
                    ->addColumn('is_voucher_created', function($row){
                        return ($row->is_voucher_created == 1) ?
                        '<span class="text-success">created</span>' :
                        '<span class="text-danger">pending</span>';
                    })

                    ->addColumn('action', function ($row) {
                        

                        $btn = '
                        <div class="d-flex align-items-center col-actions">
                            <div class="dropdown">
                                <a class="action-set show" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="true">
                                    <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">';
                                
                                $btn .= 
                                    '<li>
                                        <a href="'.route('tax-filing.show', $row->id).'" class="dropdown-item">
                                            <i class="bx bx-show font-size-16 text-primary me-1"></i>Show
                                        </a>
                                    </li>';
                                    
                                if($row->is_posted == 0)
                                {
                                    if($row->is_voucher_created == 0)
                                    {
                                        $btn .= 
                                        '<li>
                                            <a href="'.route('tax-filing-detail.create', ['id'=>$row->id]).'" class="dropdown-item">
                                                <i class="bx bx-plus font-size-16 text-primary me-1"></i> Pay Now
                                            </a>
                                        </li>'; 
                                    }
                                    
                                    if($row->is_voucher_created == 1)    
                                    {
                                        $btn .= 
                                        '<li>
                                            <a href="javascript:void(0)" onclick="posting(' . $row->id . ')" class="dropdown-item">
                                                <i class="bx bx-lock font-size-16 text-success me-1"></i> Posting
                                            </a>
                                        </li>
                                        ';
                                    }

                                    $btn .= 
                                        '<li>
                                            <a href="javascript:void(0)" onclick="deleteTaxFiling(' . $row->id . ')" class="dropdown-item">
                                                <i class="bx bx-trash font-size-16 text-danger me-1"></i> Delete
                                            </a>
                                        </li>';
                                }
                                    
                                

                                
                                
                                
                                $btn .= '
                                </ul>
                            </div>
                        </div>';
                                 
                   
                    return $btn;
                   
                    })
                //     <li>
                //     <a href="'.route('tax-filing.edit', $row->id).'" class="dropdown-item">
                //         <i class="bx bx-pencil font-size-16 text-secondary me-1"></i> Edit
                //     </a>
                // </li>
               
                //  <li>
                //     <a href="javascript:void(0)" onclick="deleteVoucher(' . $row->id . ')" class="dropdown-item">
                //         <i class="bx bx-trash font-size-16 text-danger me-1"></i> Delete
                //     </a>
                // </li>

                    ->rawColumns(['action','attachment','is_voucher_created']) // Mark these columns as raw HTML
                    ->make(true);
            }
    
            return view('accounting.tax_filings.index');

        }catch (\Exception $e){
            dd($e);
            return back()->with('flash-danger', $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    
       return view('accounting.tax_filings.create');
    }


   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   
     
     public function store(Request $request)
     {
        
         // Start a transaction
         DB::beginTransaction();
 
         try {
 
 
             // Validate the request data
            $validator = Validator::make($request->all(), [

                'startDate' => 'required',
                'endDate' => 'required',
            ]);


            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ]);
            }

            $startDate = $request->startDate;
            $endDate = $request->endDate;

            $purchaseInvoices = InvoiceMaster::where('type','PI')
            ->where('is_tax_filed',0)
            ->whereBetween('date', [$startDate,$endDate])
            ->get();

            $saleInvoices = InvoiceMaster::where('type','SI')
            ->where('is_tax_filed',0)
            ->whereBetween('date', [$startDate,$endDate])
            ->get();

            $expenses = Expense::where('calculated_tax_amount','>',0)
            ->where('is_tax_filed',0)
            ->whereBetween('date', [$startDate,$endDate])
            ->get();
        

            $si = $saleInvoices->sum('total_tax_amount') ?? 0;
            $pi = $purchaseInvoices->sum('total_tax_amount') ?? 0;
            $exp = $expenses->sum('calculated_tax_amount') ?? 0;

            $inputTax = $pi + $exp;
            $outputTax = $si;
            $taxPayable = $outputTax - $inputTax;

            $newVoucherNo =  TaxFiling::generateVoucherNo('TF');

            $data = [
                'filed_on' => date('Y-m-d'),
                'start_month_year' => $request->startDate,
                'end_month_year' => $request->endDate,
                'voucher_no' => $newVoucherNo,
                'sales_tax_amount' => $si,
                'purchases_tax_amount' => $pi,
                'expenses_tax_amount' => $exp,
                'input_tax' => $inputTax,
                'output_tax' => $outputTax,
                'total_payable' => $taxPayable,
            ];
            
            DB::table('tax_filings')->insertGetId($data);
     
             

             DB::commit();// Commit the transaction
 
             // Return a JSON response with a success message
             return response()->json([
                 'success' => true,
                 'message' => 'Voucher added successfully.',
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
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $chart_of_accounts = ChartOFAccount::where('level',4)->get();
        $details = TaxFilingDetail::where('tax_filing_id',$id)->get();
        
       
        try {
            $taxFiling = TaxFiling::find($id);
            return view('accounting.tax_filings.show', compact('taxFiling','chart_of_accounts','details'));

        } catch (\Exception $e) {
            // Return a JSON response with an error message
            return response()->json([
                'message' => $e->getMessage(),
                'success' => false,
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      

        try {
            $voucher = Voucher::with('details')->find($id);

            $voucherTypes = VoucherType::all();
            $suppliers = Party::whereIn('party_type',['supplier','both'])->get(); 
            $customers = Party::whereIn('party_type',['customer','both'])->get(); 
            $chart_of_accounts = ChartOFAccount::where('level',4)->get();



            return view('accounting.vouchers.edit', compact('voucher','suppliers','customers','chart_of_accounts','voucherTypes'));

        } catch (\Exception $e) {
            // Return a JSON response with an error message
            return response()->json([
                'message' => $e->getMessage(),
                'success' => false,
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();// Start a transaction

        try {
            $taxFiling = TaxFiling::find($id);

            $taxFiling->details()->delete();

            $taxFiling->delete();// Delete the brand record

            DB::commit();// Commit the transaction
            
            return response()->json([
                'success' => true,
                'message' => 'Tax Filing Delete successfully.',
                ],200);
                
        } catch (\Exception $e) {
          
            DB::rollBack();  // Rollback the transaction if there is an error

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
        
    }

    public function posting($id)
    {
        $taxFiling = TaxFiling::find($id);

        $taxFilingDetails = TaxFilingDetail::where('tax_filing_id',$id)->get();

        foreach($taxFilingDetails as $row)
        {
            $entry = [
                'tax_filing_id' => $taxFiling->id,
                'date' => $taxFiling->filed_on,
                'voucher_no' => $taxFiling->voucher_no,
                'code' => "TF",
                'type' => "Tax Filing",
                'chart_of_account_id' => $row->chart_of_account_id,
                'narration' => $row->narration,
                'debit' => $row->debit,
                'credit' => $row->credit,
                'created_at' => now(),
                'created_By' => Auth::user()->id,
            
            ];
            DB::table('journals')->insert($entry);
        }

        $taxFiling->update(['is_posted'=> 1]);

        $startDate = $taxFiling->start_month_year;
        $endDate = $taxFiling->end_month_year;


        DB::table('invoice_masters')->whereBetween('date', [$startDate,$endDate])
        ->update([
            'tax_filing_id' =>$taxFiling->id,
            'is_tax_filed' =>1,
        ]);
        DB::table('expenses')->whereBetween('date', [$startDate,$endDate])
        ->update([
            'tax_filing_id' =>$taxFiling->id,
            'is_tax_filed' =>1,
        ]);




        // Return a JSON response with a success message
        return response()->json([
            'success' => true,
            'message' => 'Posting done successfully.',
        ],200);
    
    }

   
}
