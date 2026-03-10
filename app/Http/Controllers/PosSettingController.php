<?php

namespace App\Http\Controllers;

use App\Models\PosSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PosSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posSetting = PosSetting::first();
        return view('pos_settings.index' ,compact('posSetting'));
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
            'tax_type' => 'required|string|max:100',
            'tax_value' => 'required|min:0', // Validation for image
        ],
        [
            'tax_value.required' => 'Tax Value is Required (0 or greater)'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $posSetting = PosSetting::first();

        $posSetting->tax_type = $request->tax_type;
        $posSetting->tax_value = $request->tax_value;

        $posSetting->save();

        return response()->json([
            'success' => true,
            'message' => 'Setting Update successfully.',
        ],200);

    }

    /**
     * Display the specified resource.
     */
    public function show(PosSetting $posSetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PosSetting $posSetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PosSetting $posSetting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PosSetting $posSetting)
    {
        //
    }
}
