<?php

namespace App\Http\Controllers\Startup;

use App\Http\Controllers\Controller; //import base controller

use App\Models\Startup\Party;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class PartyController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request, $type = null)
    {

        try {
            if ($request->ajax()) {


                if ($type == 'supplier') {
                    $data = Party::where('party_type', 'supplier')->get();
                } elseif ($type == 'customer') {
                    $data = Party::whereIn('party_type', ['customer', 'both'])->get();
                } else {
                    $data = Party::all();
                }

                return Datatables::of($data)
                    ->addIndexColumn()
                    // Status toggle column
                    ->addColumn('credit_limit', fn($row) => $row->credit_limit >1 ? number_format($row->credit_limit).' days' : '-' )
                    ->addColumn('action', function ($row) {
                        $btn = '
                            <div class="d-flex align-items-center col-actions">
                                <div class="dropdown">
                                    <a class="action-set show" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="true">
                                        <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a href="javascript:void(0)" onclick="editParty(' . $row->id . ')" class="dropdown-item">
                                                <i class="bx bx-pencil font-size-16 text-secondary me-1"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a href="' . route('party.show', $row->id) . '" class="dropdown-item">
                                                <i class="mdi mdi-eye font-size-16 text-secondary me-1"></i> View
                                            </a>
                                        </li>
                                         <li>
                                            <a href="javascript:void(0)" onclick="deleteParty(' . $row->id . ')" class="dropdown-item">
                                                <i class="bx bx-trash font-size-16 text-danger me-1"></i> Delete
                                            </a>
                                        </li>
                                       
                                    </ul>
                                </div>
                            </div>';


                        return $btn;
                    })
                    ->rawColumns(['action']) // Mark these columns as raw HTML
                    ->make(true);
            }

            return view('startups.parties.index', compact('type'));
        } catch (\Exception $e) {
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
                'party_type' => 'nullable',
                'type' => 'required',
                'business_name' => 'nullable',
                'contact_person' => 'nullable',
                'prefix' => 'nullable',
                'first_name' => 'nullable',
                'middle_name' => 'nullable',
                'last_name' => 'nullable',
                'mobile' => 'nullable',
                'alternate_number' => 'nullable',
                'landline' => 'nullable',
                'email' => 'nullable',
                'tax_number' => 'nullable',
                'balance' => 'nullable',
                'pay_term_type' => 'nullable',
                'credit_limit' => 'nullable',
                'address_line_1' => 'nullable',
                'address_line_2' => 'nullable',
                'city' => 'nullable',
                'state' => 'nullable',
                'country' => 'nullable',
                'zip_code' => 'nullable',
                'shipping_address' => 'nullable',
                'is_active' => 'nullable',
            ]);



            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ]);
            }

            $data = $request->all(); // storing request data in array
            if ($request->type == 'individual') {
                $data['business_name'] = $request->prefix . ' ' . $request->first_name . ' ' . $request->middle_name . ' ' . $request->last_name;
            }



            Party::create($data);

            DB::commit(); // Commit the transaction

            // Return a JSON response with a success message
            return response()->json([
                'success' => true,
                'message' => 'Party added successfully.',
            ], 200);
        } catch (\Exception $e) {

            DB::rollBack(); // Rollback the transaction if there's an error

            // Return a JSON response with an error message
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $party = Party::find($id);

        return view('startups.parties.show', compact('party'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

        try {
            $data = Party::findOrFail($id);
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
            'party_type' => 'nullable',
            'type' => 'required',
            'business_name' => 'nullable',
            'contact_person' => 'nullable',
            'prefix' => 'nullable',
            'first_name' => 'nullable',
            'middle_name' => 'nullable',
            'last_name' => 'nullable',
            'mobile' => 'nullable',
            'alternate_number' => 'nullable',
            'landline' => 'nullable',
            'email' => 'nullable',
            'tax_number' => 'nullable',
            'balance' => 'nullable',
            'pay_term_type' => 'nullable',
            'credit_limit' => 'nullable',
            'address_line_1' => 'nullable',
            'address_line_2' => 'nullable',
            'city' => 'nullable',
            'state' => 'nullable',
            'country' => 'nullable',
            'zip_code' => 'nullable',
            'shipping_address' => 'nullable',
            'is_active' => 'nullable',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }


        $data = $request->all(); // storing request data in array
        if ($request->type == 'individual') {
            $data['business_name'] = $request->prefix . ' ' . $request->first_name . ' ' . $request->middle_name . ' ' . $request->last_name;
        }

        try {
            $party = Party::findOrFail($id);


            $party->update($data);

            DB::commit(); // Commit the transaction

            // Return a JSON response with a success message
            return response()->json([
                'success' => true,
                'message' => 'Party Update successfully.',
            ], 200);
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


        DB::beginTransaction(); // Start a transaction

        try {
            $party = Party::find($id);

            $invoiceMasterExists = DB::table('invoice_masters')
                ->where('party_id', $id)
                ->exists();

            $journalExists = DB::table('journals')
                ->where(function($query) use ($id) {
                    $query->where('customer_id', $id)
                        ->orWhere('supplier_id', $id);
                })
                ->exists();

            if ($invoiceMasterExists || $journalExists) {
                return response()->json([
                    'success' => false,
                    'message' => "This cannot be deleted because it is associated with an invoice or journal entry.",
                ], 500); // 403 = Forbidden
            }

          

           

            $party->delete(); // Delete the party record

            DB::commit(); // Commit the transaction

            return response()->json([
                'success' => true,
                'message' => 'Party Delete successfully.',
            ], 200);
        } catch (\Exception $e) {

            DB::rollBack();  // Rollback the transaction if there is an error

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function fetchCustomerList()
    {
        $customers = Party::select('id','party_type','business_name')
        ->where('party_type', 'customer')->orderBy('id', 'desc')->get();
        return response()->json($customers);
    }
}
