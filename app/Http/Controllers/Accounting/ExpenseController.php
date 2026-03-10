<?php

namespace App\Http\Controllers\Accounting;
use App\Models\Startup\Tax;

use App\Models\Startup\User;
use Illuminate\Http\Request;
use App\Models\Startup\Party;
use Yajra\DataTables\DataTables;

use App\Models\Accounting\Expense;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\Accounting\ChartOfAccount;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller; //import base controller

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $users= User::all();
        $spendThruAccounts = ChartOfAccount::whereIn('category',['cash','bank'])->where('level',4)->get();
        
        // $chartOfAccounts = ChartOfAccount::where('level',4)->get();





        try{
            if ($request->ajax()) {
                $data = Expense::query()
                ->when($request->start_date, function ($query, $startDate) {
                    return $query->where('date', '>=', $startDate);
                })
                ->when($request->end_date, function ($query, $endDate) {
                    return $query->where('date', '<=', $endDate);
                })
                ->when($request->chart_of_account_id, function ($query, $chartOfAccount) {
                    return $query->where('chart_of_account_id', $chartOfAccount);
                })
                ->when($request->user_id, function ($query, $spendBy) {
                    return $query->where('spend_by', $spendBy);
                })
                ->when(Auth::user()->type == 'user', function($query){
                    return $query->where('created_by', Auth::user()->id);

                })

                ->orderByDesc('date')
                ->orderByDesc('id')
                ->get();
    
                return Datatables::of($data)
                    ->addIndexColumn()
                    // Status toggle column
                   
                    ->addColumn('COA_name', function($row){
                        return $row->chartOfAccount->name ?? 'N/A';
                    })
                    ->addColumn('expense_account', function($row){
                        return $row->details[0]->chartOfAccount->name ?? 'N/A';
                    })
                    ->addColumn('party_name', function($row){
                        return $row->party->business_name ?? 'N/A';
                    })
                    ->addColumn('spendBy', function($row){
                        return $row->spendBy->name ?? 'N/A';
                    })
                    ->addColumn('createdBy', function($row){
                        return $row->createdBy->name ?? 'N/A';
                    })
                    ->addColumn('date', function($row){
                        return date('d-m-Y', strtotime($row->date)) ?? 'N/A';
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
                                            <a href="'.route('expense.show', $row->id).'" class="dropdown-item">
                                                <i class="bx bx-show font-size-16 text-primary me-1"></i> View
                                            </a>
                                        </li>
                                        <li>
                                            <a href="'.route('expense.edit', $row->id).'" class="dropdown-item">
                                                <i class="bx bx-pencil font-size-16 text-secondary me-1"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" onclick="deleteExpense(' . $row->id . ')" class="dropdown-item">
                                                <i class="bx bx-trash font-size-16 text-danger me-1"></i> Delete
                                            </a>
                                        </li>
                                       
                                       
                                    </ul>
                                </div>
                            </div>';
    
                   
                    return $btn;
                    // <li>
                    //     <a href="javascript:void(0)" onclick="editBrand(' . $row->id . ')" class="dropdown-item">
                    //         <i class="bx bx-pencil font-size-16 text-secondary me-1"></i> Edit
                    //     </a>
                    // </li>
                    //     <li>
                    //     <a href="javascript:void(0)" onclick="deleteBrand(' . $row->id . ')" class="dropdown-item">
                    //         <i class="bx bx-trash font-size-16 text-danger me-1"></i> Delete
                    //     </a>
                    // </li>
                    })
                    
                  
    
                    ->rawColumns(['action']) // Mark these columns as raw HTML
                    ->make(true);
            }
    
            return view('accounting.expenses.index', compact('users','spendThruAccounts'));

        }catch (\Exception $e){

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
        $suppliers =  Party::whereIn('party_type',['supplier','both'])->get();

        $paidThruAccounts = ChartOfAccount::havingCategory()
        //for biller only show petty cash
        ->when(Auth::user()->type == 'user', fn($q) => $q->where('id',111200))
        ->where('level',4)
        ->get();

        $accounts = ChartOfAccount::getExpenseAccountsByUserType();
        $users = User::all();

        $taxes = Tax::all();

        $newExpenseNo = Expense::generateExpenseNo('EXP');
        return view('accounting.expenses.create', compact('suppliers','paidThruAccounts','accounts','newExpenseNo','taxes','users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'paid_by_COA' => 'required',
            'chart_of_account_id.*' =>'required',
            'total.*' =>'required',
            'per_unit_price.*' =>'required',
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

            $newExpenseNo = Expense::generateExpenseNo('EXP');


            $data = [
                'date' => $request->input('date'),
                'party_id' => $request->input('supplier_id'),
                'chart_of_account_id' => $request->input('paid_by_COA'),
                'expense_no' => $newExpenseNo,
                'expense_type' =>$request->expense_type,
                'reference_no' =>$request->reference_no,
                'total_amount' => $request->total_amount,
                'total_tax_amount' => $request->total_tax_amount,
                'grand_total' => $request->grand_total,
                'description' => $request->input('expense_description'),
                'attachment' => $request->file('attachment'), // For handling file uploads
                'spend_by' => $request->spend_by,
                'created_by' => Auth::user()->id,
                'created_at' => now(),
            ];

            
            // Check if the file input is provided
            if ($request->hasFile('attachment')) {
                $attachment = $request->file('attachment'); 
                $attachmentName = time() . '.' .  $attachment->extension();
                
                // Move the file to the desired folder
                $attachment->move(public_path('attachments/expenses'), $attachmentName);
                
                // Store the filename in the data array
                $data['attachment'] = $attachmentName; 
            }
            
            $expense  = Expense::create($data);


           $this->createDetailsJounralEntry($request, $expense);

                



           

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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $expense = Expense::with('details')->find($id);
           
            return view('accounting.expenses.show', compact('expense'));

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
            $suppliers =  Party::whereIn('party_type',['supplier','both'])->get();

            
            $paidThruAccounts = ChartOfAccount::havingCategory()
            //for biller only show petty cash
            ->when(Auth::user()->type == 'user', fn($q) => $q->where('id',111200))
            ->where('level',4)
            ->get();
                                    
            $accounts = ChartOfAccount::getExpenseAccountsByUserType();

            $users = User::all();

            $taxes = Tax::all();

            $expense = Expense::with(['details'])->findOrFail($id);
            
           return view('accounting.expenses.edit', compact('suppliers','paidThruAccounts','accounts','taxes','expense','users'));

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
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'paid_by_COA' => 'required',
            'chart_of_account_id.*' =>'required',
            'total.*' =>'required',
            'per_unit_price.*' =>'required',
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



            $data = [
                'date' => $request->input('date'),
                'party_id' => $request->input('supplier_id'),
                'chart_of_account_id' => $request->input('paid_by_COA'),
                'expense_type' =>$request->expense_type,
                'reference_no' =>$request->reference_no,
                'total_amount' => $request->total_amount,
                'total_tax_amount' => $request->total_tax_amount,
                'grand_total' => $request->grand_total,
                'description' => $request->input('expense_description'),
                'attachment' => $request->file('attachment'), // For handling file uploads
                'spend_by' => $request->spend_by,
                'created_by' => Auth::user()->id,
                'created_at' => now(),
            ];


            
            // Check if the file input is provided
            if ($request->hasFile('attachment')) {
                $attachment = $request->file('attachment'); 
                $attachmentName = time() . '.' .  $attachment->extension();
                
                // Move the file to the desired folder
                $attachment->move(public_path('attachments/expenses'), $attachmentName);
                
                // Store the filename in the data array
                $data['attachment'] = $attachmentName; 
            }
            
            $expense = Expense::find($id);
            $expense->update($data);

            $expense->details()->delete();
            $expense->journals()->delete();

           $this->createDetailsJounralEntry($request, $expense);

                



           

            DB::commit();// Commit the transaction

            // Return a JSON response with a success message
            return response()->json([
                'success' => true,
                'message' => 'Expense Update Successfully.',
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
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {


        DB::beginTransaction();// Start a transaction

        try {
            $data = Expense::find($id);
            DB::table('journals')->where('expense_id',$id)->delete();
            $data->details()->delete();

            $data->delete();// Delete the brand record

            DB::commit();// Commit the transaction
            
            return response()->json([
                'success' => true,
                'message' => 'Expense Delete successfully.',
                ],200);
                
        } catch (\Exception $e) {
          
            DB::rollBack();  // Rollback the transaction if there is an error

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
        
    }

    public function createDetailsJounralEntry(Request $request, $expense)
    {
        $common = [
            'date' => $request->input('date'),
            'party_id' => $request->input('supplier_id'),
            'voucher_no' => $expense->expense_no,
            'type' => 'expense',
            'expense_id' => $expense->id,
            'created_by' => Auth::user()->id,
            'created_at' => now(),
        ];

       for($i=0; $i < count($request->COA_id); $i++)
        {
            $expense_detail = [
                'expense_id' => $expense->id,
                'date' => $request->input('date'),
                'expense_no' => $expense->expense_no,
                'chart_of_account_id' => $request->COA_id[$i],
                'description' => $request->description[$i],
                
                'amount' => $request->amount[$i],
                'tax_type' => $request->tax_type[$i],
                'tax_percentage' => $request->tax_percentage[$i],
                'tax_amount' => $request->tax_amount[$i],
                'total' => $request->total[$i],
                'created_by' => Auth::user()->id,
                'created_at' => now(),
            ];
            DB::table('expense_details')->insertGetId($expense_detail);


            DB::table('journals')->insert(array_merge($common,[
                'chart_of_account_id' => $request->COA_id[$i],
                'narration' => $request->description[$i],
                'debit' => $request->total[$i],
            ]));

            if($request->tax_percentage > 0 && $request->tax_type != null){
                DB::table('journals')->insert(array_merge($common,[
                    'chart_of_account_id' => config('coa.adv_tax_paid_input_tax'),
                    'narration' => "Tax ".$request->tax_type[$i] .' on total '. $request->amount[$i],
                    'debit' => $request->tax_amount[$i],
                ]));
            }
             
        }

        DB::table('journals')->insert(array_merge($common,[
            'chart_of_account_id' => $request->input('paid_by_COA'),
            'narration' =>  $request->input('expense_description'),
            'credit' => $request->grand_total,
        ]));



        

    }






    
    

}
