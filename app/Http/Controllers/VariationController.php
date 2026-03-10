<?php

namespace App\Http\Controllers;

use App\Models\Variation;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreVariationRequest;
use App\Http\Requests\UpdateVariationRequest;


class VariationController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index(Request $request)
    {
      
        try{
            if ($request->ajax()) {
                $data = Variation::all();
    
                return Datatables::of($data)
                    ->addIndexColumn()
                    // Status toggle column
                    ->addColumn('status', function ($row) {
                        $checked = $row->is_active ? 'checked' : '';
                        if($checked)
                            return '<span class="badge bg-success font-size-12 text-center">Active</span>';
                        else
                            return '<span class="badge bg-danger font-size-12 text-center">Inactive</span>';
                    
                    })
                    ->addColumn('values', function ($row) {
                        // Decode JSON string if necessary
                        $values = is_string($row->values) ? json_decode($row->values, true) : $row->values;
            
                        if (is_array($values)) {
                            return implode('', array_map(function($value) {
                                // Generate inline-styled HTML for each value
                                return '<button class=" btn btn-sm p-2 m-1 border rounded btn-primary d-inline-block">' . htmlspecialchars($value) . '</button>';
                            }, $values));
                        }
                        return '';
                    })

                    ->addColumn('action', function ($row) {
                        $btn = '
                            <div class="d-flex align-items-center col-actions">
                                <div class="dropdown">
                                     <a class="action-set show" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="true">
                                        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a href="javascript:void(0)" onclick="editVariation(' . $row->id . ')" class="dropdown-item">
                                                <i class="bx bx-pencil font-size-16 text-secondary me-1"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" onclick="deleteVariation(' . $row->id . ')" class="dropdown-item">
                                                <i class="bx bx-trash font-size-16 text-danger me-1"></i> Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>';
    
                   
                    return $btn;
                    
                    })
    
                    ->rawColumns(['status', 'action','values']) // Mark these columns as raw HTML
                    ->make(true);
            }
    
            return view('variations.index');

        }catch (\Exception $e){

            return back()->with('flash-danger', $e->getMessage());
        }
        
        
    }

    public function store(Request $request)
    {
        // Start a transaction
        DB::beginTransaction();

        try {
           
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:100',
                'values' => 'nullable|array',
                'is_active' => 'boolean', // Validation for boolean (0 or 1)
            ]);



            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ]);
            }
            $data = $request->all();
            // Convert the 'values' array to a JSON string
            if (isset($data['values'])) {
                $data['values'] = json_encode($data['values']);
            }

            $data['is_active'] = $request->has('is_active') ? 1 : 0; // Handle the checkbox value

            Variation::create($data);

            DB::commit();// Commit the transaction

            // Return a JSON response with a success message
            return response()->json([
                'success' => true,
                'message' => 'Variation added successfully.',
            ],200);
        

            
        } catch (\Exception $e) {
            
            DB::rollBack();// Rollback the transaction if there's an error

            // Return a JSON response with an error message
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'danger'
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        
        try {
            $data = Variation::findOrFail($id);
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
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'values' => 'nullable|array', // Validate the 'values' as an array
            'is_active' => 'boolean'

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $data = $request->all();

        // Convert the 'values' array to a JSON string if it exists
        if (isset($data['values'])) {
            $data['values'] = json_encode($data['values']);
        }
        $data['is_active'] = $request->has('is_active') ? 1 : 0; // Handle the checkbox value

        DB::beginTransaction();

        try {
            $variation = Variation::findOrFail($id);
            $variation->update($data);

            DB::commit();// Commit the transaction

            // Return a JSON response with a success message
            return response()->json([
                'success' => true,
                'message' => 'Variation Update successfully.',
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
             $data = Variation::find($id);
 
             $data->delete();// Delete the data record
 
             DB::commit();// Commit the transaction
             
             return response()->json([
                 'success' => true,
                 'message' => 'Variation Delete successfully.',
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
