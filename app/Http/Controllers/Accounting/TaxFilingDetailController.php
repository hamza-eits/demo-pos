<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Accounting\TaxFiling;
use Illuminate\Support\Facades\Auth;
use App\Models\Accounting\ChartOfAccount;
use Illuminate\Support\Facades\Validator;
use App\Models\Accounting\TaxFilingDetail;

class TaxFilingDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        

        
        
    }
    public function create(Request $request)
    {
        $taxFilingId = $request->id;

        $chart_of_accounts = ChartOfAccount::whereIn('category',['cash','bank','card'])->get();

        $tax_coa = ChartOfAccount::find(211101);
       
        try {
            $taxFiling = TaxFiling::find($taxFilingId);
            return view('accounting.tax_filing_details.create', compact('taxFiling','chart_of_accounts','tax_coa'));

        } catch (\Exception $e) {
            // Return a JSON response with an error message
            return response()->json([
                'message' => $e->getMessage(),
                'success' => false,
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
                'chart_of_account_id.*' => 'required',

            ]);



            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ]);
            }

            $taxFiling =  TaxFiling::find($request->tax_filing_id);

            //insert new record in voucher detail
            for($i=0; $i < count($request->chart_of_account_id); $i++)
            {
               
               $entry = [
                   'tax_filing_id' => $taxFiling->id,
                   'date' => $taxFiling->filed_on,
                   'voucher_no' => $taxFiling->voucher_no,
                   'code' => "TF",
                   'type' => "Tax Filing",
                   'chart_of_account_id' => $request->chart_of_account_id[$i],
                   'narration' => $request->narration[$i],
                   'reference_no' => $request->reference_no[$i],
                   'debit' => $request->debit[$i],
                   'credit' => $request->credit[$i],
                   'created_at' => now(),
                   'created_By' => Auth::user()->id,
               
               ];
               DB::table('tax_filing_details')->insert($entry);
    
            }      
            $taxFiling->update(['is_voucher_created'=> 1]);
           


            DB::commit();// Commit the transaction

            // Return a JSON response with a success message
            return response()->json([
                'success' => true,
                'message' => 'TaxFilingDetail added successfully.',
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
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        
        try {
            $data = TaxFilingDetail::findOrFail($id);
            return response()->json($data);

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
     */

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation for image
            'is_active' => 'boolean', // Validation for boolean (0 or 1)

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

       
        $data = $request->all();// storing request data in array
      

       try {
           $brand = TaxFilingDetail::findOrFail($id);

           // Handle the image upload
           if ($request->hasFile('image')) {
               // Delete old image if it exists
               if ($brand->image && $brand->image != 'default.jpg') {
                   unlink(public_path('build/img/brand/' . $brand->image));
               }

               $imageName = time() . '.' . $request->image->extension();
               $request->image->move(public_path('build/img/brand'), $imageName);
               $data['image'] = $imageName; // Save the image name in the data array
           }


           $brand->update($data);

           DB::commit();// Commit the transaction

           // Return a JSON response with a success message
           return response()->json([
               'success' => true,
               'message' => 'TaxFilingDetail Update successfully.',
               ],200);


       } catch (\Exception $e) {
          
           DB::rollBack(); // Rollback the transaction if there is an error

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
            $brand = TaxFilingDetail::find($id);

            $brand->delete();// Delete the brand record

            DB::commit();// Commit the transaction
            
            return response()->json([
                'success' => true,
                'message' => 'TaxFilingDetail Delete successfully.',
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
