<?php

namespace App\Http\Controllers\Startup;
use App\Http\Controllers\Controller; //import base controller

use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Validation\Rules;

use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index(Request $request)
    {
    
       $types = $this->userTypes();
        try{
            if ($request->ajax()) {
                $data = User::all();
    
                return Datatables::of($data)
                    ->addIndexColumn()
                  

                    ->addColumn('action', function ($row) {
                        $btn = '
                            <div class="d-flex align-items-center col-actions">
                                <div class="dropdown">
                                    <a class="action-set show" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="true">
                                        <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a href="javascript:void(0)" onclick="editUser(' . $row->id . ')" class="dropdown-item">
                                                <i class="bx bx-pencil font-size-16 text-secondary me-1"></i> Edit
                                            </a>
                                        </li>
                                         <li>
                                            <a href="javascript:void(0)" onclick="deleteUser(' . $row->id . ')" class="dropdown-item">
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
                            ? URL::asset('/build/img/user/' . $row->image) 
                            : URL::asset('/build/img/default.png');
                    
                        return '
                            <a href="javascript:void(0);" class="item-img stock-img">
                                <img src="' . $imageUrl . '" alt="'.$row->image .'"  width="50px" height="50px" >
                            </a>';
                    })
    
                    ->rawColumns(['action','image']) // Mark these columns as raw HTML
                    ->make(true);
            }
    
            return view('startups.users.index',compact('types'));

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
                'name' => ['required', 'string', 'max:255'],
                'mobile_no' => ['nullable', 'numeric', 'unique:users,mobile_no'],
                'email' => ['required', 'string', 'max:255', 'unique:users,email'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'type' => 'required',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation for image
                'is_active' => 'boolean'

            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ]);
            }

            
            $image = '';
             // Handle the image upload
            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('build/img/user'), $imageName);
                $image = $imageName; // Save the image name in the data array
            }

            $user = User::create([
                'name' => $request->name,
                'mobile_no' => $request->mobile_no,
                // 'email' => $request->mobile_no,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'hint' => $request->password,
                'type' => $request->type,
                'image' => $image,

            ]);

            $this->assignRole($user->id, $user->type);

            DB::commit();// Commit the transaction

            // Return a JSON response with a success message
            return response()->json([
                'success' => true,
                'message' => 'User added successfully.',
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
            $data = User::findOrFail($id);
            return response()->json($data);

        } catch (\Exception $e) {
            // Return a JSON response with an error message
            return response()->json([
                'message' => $e->getMessage(),
                'success' => false
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $id)
    {
        
        DB::beginTransaction();
        $current_user = User::find($id);
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'mobile_no' => ['nullable', 'numeric', 'unique:users,mobile_no,' . $current_user->id],
            'email' => ['required', 'string','max:255','unique:users,email,' . $current_user->id],
            'hint' => ['required', 'string', 'max:255'],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation for image
            'is_active' => 'boolean'

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

      
       try {
           $user = User::findOrFail($id);

           $image =NULL;

           // Handle the image upload
           if ($request->hasFile('image')) {
               // Delete old image if it exists
               if ($user->image && $user->image != 'user.png') {
                   unlink(public_path('build/img/user/' . $user->image));
               }

               $imageName = time() . '.' . $request->image->extension();
               $request->image->move(public_path('build/img/user'), $imageName);
               $image = $imageName; // Save the image name in the data array
           }


           $user->update([
                'name' => $request->name,
                'mobile_no' => $request->mobile_no,
                'email' => $request->email,
                'password' => Hash::make($request->hint),
                'hint' => $request->hint,
                'type' => $request->type,
                'image' => $image,
           ]);


            $this->assignRole($user->id, $user->type);

           DB::commit();// Commit the transaction



           // Return a JSON response with a success message
           return response()->json([
               'success' => true,
               'message' => 'User Update successfully.',
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
            $user = User::findOrFail($id);


            if($user->id == 1)
            {
                 return response()->json([
                    'success' => false,
                    'message' => 'Cannot Delete Super User',
                ], 500);
            }
            
            
            
            
            $is_record = DB::table('invoice_masters')
                ->where('created_by', $user->id)
                ->exists();

            if($is_record)
            {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot Delete User beacuse its has record',
                ], 500);
            }else{

                // Remove all existing roles
                $user->syncRoles([]);
                // Delete the user record
                $user->delete();

            }
            
          
            DB::commit();// Commit the transaction
            
            return response()->json([
                'success' => true,
                'message' => 'User Delete successfully.',
                ],200);
                
        } catch (\Exception $e) {
          
            DB::rollBack();  // Rollback the transaction if there is an error

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
        
    }


    public function assignRole($id, $type)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Remove all existing roles
        $user->syncRoles([]);

        // Assign the specified role
        switch ($type) {
            case 'super-admin':
                $user->assignRole('super-admin');
                break;

            case 'admin':
                $user->assignRole('admin');
                break;

            default:
                $user->assignRole('user');
                break;
        }

        return response()->json(['message' => 'Role assigned successfully.']);
    }


    public function userTypes()
    {
        $data = [
            'super-admin',
            'admin',
            'user'
           
        ];

        return $data;
    }


    public function downloadSampleFile(){
       
        try {
            $filePath = public_path('csv_sample/user.csv');
            if (File::exists($filePath)) {
                return response()->download($filePath, 'user.csv');
            } else {

                return back()->with('error', 'File not found.');
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // public function uploadFile(Request $request)
        // {
        //     try {
        //         // Validate that the uploaded file is a CSV
        //         $request->validate([
        //             'csv_file' => 'required|mimes:csv,txt'
        //         ]);

        //         $file = $request->file('csv_file');
        //         $filePath = $file->getRealPath();
        //         $file = fopen($filePath, 'r');
        //         $header = fgetcsv($file);

        //         // Process the CSV file
        //         while ($row = fgetcsv($file)) {
        //             $data = array_combine($header, $row);

        //             // Validate the individual data before saving
        //             $validator = Validator::make($data, [
        //                 'name' => 'nullable',
        //                 'mobile_no' => 'required|unique:users,mobile_no',
        //                 'email' => 'required|unique:users,email',
        //                 'type' => 'nullable',
        //                 'password' => 'nullable',
        //             ]);

        //             if ($validator->fails()) {
        //                 return response()->json([
        //                     'success' => false,
        //                     'message' => $validator->errors()->first(),
        //                     'row' => $data
        //                 ]);
        //             }

        //             // Save the data to the database
        //             User::create([    
        //                 'name' => $data['name'],
        //                 'mobile_no' => $data['mobile_no'],
        //                 'type' => $data['type'],
        //                 'email' => $data['email'],  // Use 'email' field instead of 'mobile_no'
        //                 'password' => Hash::make($data['password']),
        //                 'hint' => $data['password'],
        //             ]);
        //         }
        //         fclose($file);

        //         return back()->with('success', 'CSV file uploaded successfully.');

        //     } catch (\Exception $e) {

        //         // Return an error message to the user
        //         return back()->with('error', 'An error occurred while uploading the CSV file. Please try again.');
        //     }
    // }
  
    public function uploadFile(Request $request)
    {
        try {
            // Validate that the uploaded file is a CSV
            $request->validate([
                'csv_file' => 'required|mimes:csv,txt'
            ]);
    
            $file = $request->file('csv_file');
            $filePath = $file->getRealPath();
            $file = fopen($filePath, 'r');
            $header = fgetcsv($file);
    
            // Start a DB transaction
            DB::transaction(function () use ($file, $header) {
    
                while ($row = fgetcsv($file)) {
                    $data = array_combine($header, $row);
    
                    // Validate each row
                    $validator = Validator::make($data, [
                        'name' => 'nullable',
                        'mobile_no' => 'required|unique:users,mobile_no',
                        'email' => 'required|unique:users,email',
                        'type' => 'nullable',
                        'password' => 'nullable',
                    ]);
    
                    // If validation fails, throw an exception to rollback the transaction
                    if ($validator->fails()) {
                        throw new \Exception('Validation failed for row: ' . json_encode($data) . '. Error: ' . $validator->errors()->first());

                        // throw new \Exception("Validation failed for row: " . json_encode($data) . '. Error: ' . $validator->errors()->first());
                    }
    
                    // Save the data to the database
                    User::create([    
                        'name' => $data['name'],
                        'mobile_no' => $data['mobile_no'],
                        'type' => $data['type'],
                        'email' => $data['email'],  // Use 'email' field instead of 'mobile_no'
                        'password' => Hash::make($data['password']),
                        'hint' => $data['password'],
                    ]);
                }
    
                fclose($file); // Close the file inside the transaction
    
            });
    
            return back()->with('success', 'CSV file uploaded successfully.');

    
        } catch (\Exception $e) {
            // Return a JSON response with the error
            return back()->with('error', $e->getMessage());

                   //     return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');

        }
    }
    

}
