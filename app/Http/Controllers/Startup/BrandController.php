<?php

namespace App\Http\Controllers\Startup;
use App\Http\Controllers\Controller; //import base controller

use Illuminate\Http\Request;
use App\Models\Startup\Brand;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;


class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $data = Brand::all();
        try{
            if ($request->ajax()) {
    
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
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a href="javascript:void(0)" onclick="editBrand(' . $row->id . ')" class="dropdown-item">
                                                <i class="bx bx-pencil font-size-16 text-secondary me-1"></i> Edit
                                            </a>
                                        </li>
                                         <li>
                                            <a href="javascript:void(0)" onclick="deleteBrand(' . $row->id . ')" class="dropdown-item">
                                                <i class="bx bx-trash font-size-16 text-danger me-1"></i> Delete
                                            </a>
                                        </li>
                                       
                                       
                                    </ul>
                                </div>
                            </div>';
    
                   
                    return $btn;
                   
                    })
                    
                    ->addColumn('image', function ($row) {
                        $imageUrl = $row->image 
                            ? asset('/build/img/brand/' . $row->image) 
                            : asset('/build/img/default.png');
                    
                        return '
                            <a href="javascript:void(0);" class="item-img stock-img">
                                <img src="' . $imageUrl . '" alt="'.$row->image .'"  width="50px" height="50px" >
                            </a>';
                    })

    
                    ->rawColumns(['action','image']) // Mark these columns as raw HTML
                    ->make(true);
            }
    
            return view('startups.brands.index');

        }catch (\Exception $e){
            dd($e->getMessage());
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

             // Handle the image upload
            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('build/img/brand'), $imageName);
                $data['image'] = $imageName; // Save the image name in the data array
            }

            Brand::create($data);

            DB::commit();// Commit the transaction

            // Return a JSON response with a success message
            return response()->json([
                'success' => true,
                'message' => 'Brand added successfully.',
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
            $data = Brand::findOrFail($id);
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
           $brand = Brand::findOrFail($id);

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
               'message' => 'Brand Update successfully.',
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
            $brand = Brand::find($id);

            $brand->delete();// Delete the brand record

            DB::commit();// Commit the transaction
            
            return response()->json([
                'success' => true,
                'message' => 'Brand Delete successfully.',
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
