<?php

namespace App\Http\Controllers\Startup;
use Illuminate\Http\Request;

use App\Models\Startup\Category;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller; //import base controller


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        

        try{
            if ($request->ajax()) {
                $data = Category::all();
    
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
                                            <a href="javascript:void(0)" onclick="editCategory(' . $row->id . ')" class="dropdown-item">
                                                <i class="bx bx-pencil font-size-16 text-secondary me-1"></i> Edit
                                            </a>
                                        </li>
                                         <li>
                                            <a href="javascript:void(0)" onclick="deleteCategory(' . $row->id . ')" class="dropdown-item">
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
                            ? asset('/build/img/category/' . $row->image) 
                            : asset('/build/img/default.png');
                    
                        return '
                            <a href="javascript:void(0);" class="item-img stock-img">
                                <img src="' . $imageUrl . '" alt="'.$row->image .'"  width="50px" height="50px" >
                            </a>';
                    })

    
                    ->rawColumns(['action','image']) // Mark these columns as raw HTML
                    ->make(true);
            }
    
            return view('startups.categories.index');

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
                $request->image->move(public_path('build/img/category'), $imageName);
                $data['image'] = $imageName; // Save the image name in the data array
            }

            Category::create($data);

            DB::commit();// Commit the transaction

            // Return a JSON response with a success message
            return response()->json([
                'success' => true,
                'message' => 'Category added successfully.',
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
            $data = Category::findOrFail($id);
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
           $category = Category::findOrFail($id);

           // Handle the image upload
           if ($request->hasFile('image')) {
               // Delete old image if it exists
               if ($category->image && $category->image != 'default.jpg') {
                   unlink(public_path('build/img/category/' . $category->image));
               }

               $imageName = time() . '.' . $request->image->extension();
               $request->image->move(public_path('build/img/category'), $imageName);
               $data['image'] = $imageName; // Save the image name in the data array
           }


           $category->update($data);

           DB::commit();// Commit the transaction

           // Return a JSON response with a success message
           return response()->json([
               'success' => true,
               'message' => 'Category Update successfully.',
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
            $category = Category::find($id);

            $category->delete();// Delete the category record

            DB::commit();// Commit the transaction
            
            return response()->json([
                'success' => true,
                'message' => 'Category Delete successfully.',
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
