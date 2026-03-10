<?php

namespace App\Http\Controllers;

use App\Models\CashRegister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class CashRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        try {
            if ($request->ajax()) {
                $data = CashRegister::all();

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

                  


                    ->rawColumns(['action']) // Mark these columns as raw HTML
                    ->make(true);
            }

            return view('brands.index');
        } catch (\Exception $e) {

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
        $lastClosing = CashRegister::latest()->first();
        return view('cash_registers.create', compact('lastClosing'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'opening_cash' => 'required'
        ]);


        $cashRegister = CashRegister::create([
            'user_id' => Auth::id(),
            'opening_cash' => $request->input('opening_cash'),
            'opened_at' => now(),
            'status' => 1
        ]);

        return redirect()->route('point-of-sale.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function close(Request $request)
    {

        $user_id = Auth::id();
        $cash_register_id = $request->cash_register_id; //this is coming from middleware

        $cashRegister = CashRegister::find($cash_register_id);

        if ($cashRegister) {
            $cashRegister->update([
                'closing_cash' => $request->closing_cash,
                'closed_at' => now(),
                'status' => 0,
                'notes' => $request->notes
            ]);
        }

        Auth::logout();

        return redirect()->route('login');
    }
}
