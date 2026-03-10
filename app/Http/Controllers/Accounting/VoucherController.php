<?php

namespace App\Http\Controllers\Accounting;
use App\Http\Controllers\Controller; //import base controller

use App\Models\Accounting\Voucher;
use App\Models\Accounting\VoucherType;
use App\Models\Accounting\VoucherDetail;
use App\Models\Accounting\ChartOfAccount;


use App\Models\Startup\Party;


use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;



class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

      
        $voucherTypes = VoucherType::all();
        try{
            if ($request->ajax()) {


                $query = Voucher::query()
                ->when($request->voucher_type_code, function ($query, $voucherTypeCode) {
                    return $query->where('code', $voucherTypeCode);
                })
                ->when($request->start_date, function ($query, $startDate) {
                    return $query->where('date', '>=', $startDate);
                })
                ->when($request->end_date, function ($query, $endDate) {
                    return $query->where('date', '<=', $endDate);
                })
                //User Based 
                ->when(Auth::user()->type == 'user', fn($q) => $q->where('created_by', Auth::user()->id))
                
                ->orderByDesc('id')
                ->orderByDesc('date');


                $data = $query->get();
    
                return Datatables::of($data)
                    ->addIndexColumn()
                    // Status toggle column
                   

                    ->addColumn('action', function ($row) {
                        $btn = '
                            <div class="d-flex align-items-center col-actions">
                                <div class="dropdown">
                                    <a class="action-set show" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="true">
                                        <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">';
                                    if($row->attachment)
                                    {
                                        $fileUrl = asset('/attachments/vouchers/' . htmlspecialchars($row->attachment));
                                        $btn .= '
                                        <li>
                                            <a href="' . $fileUrl . '" target="_blank" class="dropdown-item">
                                                <i class="bx bx-file font-size-16 text-danger me-1"></i> Attachment
                                            </a>
                                        </li>';
                                    }

                                     $btn .= '    <li>
                                            <a href="'.route('voucher.show', $row->id).'" class="dropdown-item">
                                                <i class="bx bx-show font-size-16 text-primary me-1"></i> Show
                                            </a>
                                        </li>
                                        <li>
                                            <a href="'.route('voucher.edit', $row->id).'" class="dropdown-item">
                                                <i class="bx bx-pencil font-size-16 text-secondary me-1"></i> Edit
                                            </a>
                                        </li>
                                       
                                         <li>
                                            <a href="javascript:void(0)" onclick="deleteVoucher(' . $row->id . ')" class="dropdown-item">
                                                <i class="bx bx-trash font-size-16 text-danger me-1"></i> Delete
                                            </a>
                                        </li>';

                                      
                                       
                                       
                                        $btn .= '
                                        </ul>
                                    </div>
                                </div>';
    
                   
                    return $btn;
                   
                    })


                    ->rawColumns(['action','attachment']) // Mark these columns as raw HTML
                    ->make(true);
            }
    
            return view('accounting.vouchers.index', compact('voucherTypes'));

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
       

        $suppliers = Party::whereIn('party_type',['supplier','both'])->get(); 
        $customers = Party::whereIn('party_type',['customer','both'])->get(); 
        $chart_of_accounts = ChartOFAccount::where('level',4)->get();
        $voucherTypes = VoucherType::where('code','!=','JV')->get(); 


       return view('accounting.vouchers.create', compact('suppliers','customers','voucherTypes','chart_of_accounts'));
    }


    public function createJournalVoucher(){


        $suppliers = Party::whereIn('party_type',['supplier','both'])->get(); 
        $customers = Party::whereIn('party_type',['customer','both'])->get(); 
        $chart_of_accounts = ChartOFAccount::where('level',4)->get();
        return view('accounting.vouchers.create_journal_voucher', compact('suppliers','customers','chart_of_accounts'));


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeJournalVoucher(Request $request)
    {
        
        // Start a transaction
        DB::beginTransaction();

        try {

            // Validate the request data
            $validator = Validator::make($request->all(), [

               'attachment' => 'file|mimes:jpg,jpeg,png,pdf|max:2048', // Validation rules for each file
               'chart_of_account_id.*' => 'required',
               'narration.*' => 'required',
            ]);


            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ]);
            }

            
            if($request->total_amount_debit != $request->total_amount_credit)
            {
                return response()->json([
                    'success' => false,
                    'message' => "Debit and Credit are not equal"
                ]);
            }

            $voucher_type = DB::table('voucher_types')->where('code','JV')->first();
            $newVoucherNo =  Voucher::generateVoucherNo( $voucher_type->code);
    
            $voucher = [
                'date' => $request->date,
                'voucher_no' => $newVoucherNo,
                'code' => $voucher_type->code,
                'type' => $voucher_type->name,
                'narration' => $request->narration_main,
                'total_amount' => $request->total_amount,
                'created_by' => Auth::user()->id,
            ];


           // Handle the image upload
           if ($request->hasFile('attachment')) {
               $imageName = time() . '.' . $request->attachment->extension();
               $request->attachment->move(public_path('attachments/vouchers'), $imageName);
               $voucher['attachment'] = $imageName; // Save the image name in the data array
           }
           $voucher_id = DB::table('vouchers')->insertGetId($voucher);

           //inserting into voucher details and journal
    
           for($i=0; $i < count($request->chart_of_account_id); $i++)
           {
              
              $entry = [
                  'voucher_id' => $voucher_id,
                  'date' => $request->date,
                  'voucher_no' => $newVoucherNo,
                  'code' => $voucher_type->code,
                  'type' => $voucher_type->name,
                  'chart_of_account_id' => $request->chart_of_account_id[$i],
                  // 'party_id' => '',//  client is both a customer and supplier
                  'customer_id' => $request->customer_id[$i],
                  'supplier_id' => $request->supplier_id[$i],
                  'narration' => $request->narration[$i],
                  'reference_no' => $request->reference_no[$i],
                  'debit' => $request->debit[$i],
                  'credit' => $request->credit[$i],
                  'created_at' => now(),
                  'updated_at' => NULL,
              
              ];
              DB::table('voucher_details')->insert($entry);

               unset($entry['reference_no']);  // Remove reference_no for journals
               $entry['updated_by'] = Auth::user()->id; 
               DB::table('journals')->insert($entry);

   
           }   

            DB::commit();// Commit the transaction

            // Return a JSON response with a success message
            return response()->json([
                'success' => true,
                'message' => 'Journal Voucher added successfully.',
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
    
     public function store(Request $request)
     {
        
         // Start a transaction
         DB::beginTransaction();
 
         try {
 
             // Validate the request data
             $validator = Validator::make($request->all(), [

                'voucher_type_code' => 'required',
                'chart_of_account_id_main' => 'required',
                'attachment.*' => 'file|mimes:jpg,jpeg,png,pdf|max:2048', // Validation rules for each file


                'chart_of_account_id.*' => 'required',
                'amount.*' => 'required',
                'narration.*' => 'required',

                 
             ]);
 
 
             if ($validator->fails()) {
                 return response()->json([
                     'success' => false,
                     'message' => $validator->errors()->first()
                 ]);
             }
 
             $voucher_type = DB::table('voucher_types')->where('code',$request->voucher_type_code)->first();
             $newVoucherNo =  Voucher::generateVoucherNo( $voucher_type->code);
     
             $voucher = [
                 'date' => $request->date,
                 'voucher_no' => $newVoucherNo,
                 'code' => $voucher_type->code,
                 'type' => $voucher_type->name,
                 'chart_of_account_id' => $request->chart_of_account_id_main,
                 'narration' => $request->narration_main,
                 'total_amount' => $request->total_amount,
                 'created_by' => Auth::user()->id,
             ];


            // Handle the image upload
            if ($request->hasFile('attachment')) {
                $imageName = time() . '.' . $request->attachment->extension();
                $request->attachment->move(public_path('attachments/vouchers'), $imageName);
                $voucher['attachment'] = $imageName; // Save the image name in the data array
            }
            $voucher_id = DB::table('vouchers')->insertGetId($voucher);

            //inserting into voucher details and journal
     
             for($i=0; $i < count($request->chart_of_account_id); $i++)
             {
                
                
                $narration = $voucher_type->name . ' Voucher No: ' . $newVoucherNo . ' for the amount of Rs. ' . number_format($request->amount[$i] ?? 0, 2);
                
                
                
                //setting array data of top entry
                $mainAccountEntry = [
                    'voucher_id' => $voucher_id,
                    'date' => $request->date,
                    'voucher_no' => $newVoucherNo,
                    'code' => $voucher_type->code,
                    'type' => $voucher_type->name,
                    'chart_of_account_id' => $request->chart_of_account_id_main,
                    'customer_id' => $request->customer_id[$i],
                    'supplier_id' => $request->supplier_id[$i],
                    // 'narration' => $request->narration_main,
                    'narration' => $narration,
                    'created_at' => now(),
                    'updated_at' => NULL,
                ];
                //setting array data of detail entry
                $detailAccountEntry = [
                    'voucher_id' => $voucher_id,
                    'date' => $request->date,
                    'voucher_no' => $newVoucherNo,
                    'code' => $voucher_type->code,
                    'type' => $voucher_type->name,
                    'chart_of_account_id' => $request->chart_of_account_id[$i],
                    // 'party_id' => '',//  client is both a customer and supplier
                    'customer_id' => $request->customer_id[$i],
                    'supplier_id' => $request->supplier_id[$i],
                    // 'narration' => $request->narration[$i],
                    'narration' => $narration,
                    'reference_no' => $request->reference_no[$i],
                    'created_at' => now(),
                    'updated_at' => NULL,
                ];


                if($voucher_type->code == 'BP' || $voucher_type->code == 'CP')
                {

                    $mainAccountEntry['credit'] = $request->amount[$i]; //top account CR
                    $detailAccountEntry['debit'] = $request->amount[$i];//detail account DR
                    
                }elseif($voucher_type->code == 'BR' || $voucher_type->code == 'CR')
                {

                    $mainAccountEntry['debit'] = $request->amount[$i]; //top account CR
                    $detailAccountEntry['credit'] = $request->amount[$i];//detail account DR
                    
                }

                // Insert main account entry into voucher_details
                DB::table('voucher_details')->insert($mainAccountEntry);

                // Insert the same main account entry into journals (with created_by for journals)
                $mainAccountEntry['created_by'] = Auth::user()->id;  // Add created_by only for journals
                DB::table('journals')->insert($mainAccountEntry);

                // Insert detail account entry into voucher_details
                DB::table('voucher_details')->insert($detailAccountEntry);

                // Insert the same detail account entry into journals (with created_by for journals)
                
                unset($detailAccountEntry['reference_no']);  // Remove reference_no for journals
                $detailAccountEntry['created_by'] = Auth::user()->id;  // Add created_by only for journals
                DB::table('journals')->insert($detailAccountEntry);
                
            }      

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
        try {
            $voucher = Voucher::with(['details','chartOfAccount'])->find($id);
            return view('accounting.vouchers.show', compact('voucher'));

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
        // Start a transaction
        DB::beginTransaction();

        try {

            // Validate the request data
            $validator = Validator::make($request->all(), [
                
            ]);


            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ]);
            }


            if($request->total_amount_debit != $request->total_amount_credit)
            {
                return response()->json([
                    'success' => false,
                    'message' => "Debit and Credit are not equal"
                ]);
            }

            $voucher_type = DB::table('voucher_types')->where('code',$request->voucher_type_code)->first();
            
            $voucher =  Voucher::find($id);
    
            $voucherData = [
                'date' => $request->date,
                'code' => $voucher_type->code,
                'type' => $voucher_type->name,
                'narration' => $request->narration_main,
                'total_amount' => $request->total_amount,
                'created_by' => Auth::user()->id,
            ];

            
           // Handle the attachment upload
           if ($request->hasFile('attachment')) {
            // Delete old attachment if it exists
            if ($voucher->attachment) {
                unlink(public_path('attachments/vouchers/' . $voucher->attachment));
            }

                $imageName = time() . '.' . $request->attachment->extension();
                $request->attachment->move(public_path('attachments/vouchers'), $imageName);
                $voucherData['attachment'] = $imageName; // Save the image name in the data array
            }


            //update table voucher the record
            DB::table('vouchers')->where('id', $voucher->id)->update($voucherData);
            
            // delete existing voucher detail  and journal record
            DB::table('voucher_details')->where('voucher_id',$voucher->id)->delete();
            DB::table('journals')->where('voucher_id',$voucher->id)->delete();

            //insert new record in voucher detail
            for($i=0; $i < count($request->chart_of_account_id); $i++)
            {
               
               $entry = [
                   'voucher_id' => $voucher->id,
                   'date' => $request->date,
                   'voucher_no' => $voucher->voucher_no,
                   'code' => $voucher_type->code,
                   'type' => $voucher_type->name,
                   'chart_of_account_id' => $request->chart_of_account_id[$i],
                   // 'party_id' => '',//  client is both a customer and supplier
                   'customer_id' => $request->customer_id[$i],
                   'supplier_id' => $request->supplier_id[$i],
                   'narration' => $request->narration[$i],
                   'reference_no' => $request->reference_no[$i],
                   'debit' => $request->debit[$i],
                   'credit' => $request->credit[$i],
                   'created_at' => NULL,
                   'updated_at' => now(),
               
               ];
               DB::table('voucher_details')->insert($entry);

                unset($entry['reference_no']);  // Remove reference_no for journals
                $entry['updated_by'] = Auth::user()->id; 
                DB::table('journals')->insert($entry);

    
            }      



            DB::commit();// Commit the transaction

            // Return a JSON response with a success message
            return response()->json([
                'success' => true,
                'message' => 'Voucher Updated successfully.',
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
            $voucher = Voucher::find($id);

            if($voucher){
                    
                DB::table('journals')->where('voucher_id',$voucher->id)->delete();
                DB::table('voucher_details')->where('voucher_id',$voucher->id)->delete();
                DB::table('vouchers')->where('id', $voucher->id)->delete();
                DB::commit();// Commit the transaction
            }


            
            return response()->json([
                'success' => true,
                'message' => 'Voucher and Journal Delete successfully.',
                ],200);
                
        } catch (\Exception $e) {
          
            DB::rollBack();  // Rollback the transaction if there is an error

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
        
    }

   
}
