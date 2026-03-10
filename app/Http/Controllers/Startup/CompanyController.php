<?php

namespace App\Http\Controllers\Startup;

use Illuminate\Http\Request;
use App\Models\Startup\Company;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company = Company::first();
        return view('startups.company.index', compact('company'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $company = Company::first();

        $company->name = $request->name;
        $company->email = $request->email;
        $company->address = $request->address;
        $company->contact_no = $request->contact_no;
        $company->contact_no = $request->contact_no;
        $company->whatsapp_no = $request->whatsapp_no;
        $company->trn_no = $request->trn_no;
        $company->website = $request->website;

        // Handle the image upload
           if ($request->hasFile('logo')) {

                $imageName = time() . '.' . $request->logo->extension();
                $request->logo->move(public_path('company'), $imageName);
                $company->logo = $imageName;           
            }

        $company->save();

        return response()->json([
            'success' => true,
            'message' => 'Company Information Updated successfully.',
        ],200);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
