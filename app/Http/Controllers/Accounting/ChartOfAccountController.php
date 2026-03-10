<?php

namespace App\Http\Controllers\Accounting;
use App\Http\Controllers\Controller; //import base controller
use Illuminate\Http\Request;

use App\Models\Accounting\ChartOfAccount;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ChartOfAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index1()
    // {
    //     $parent_id = 19;


    //     $parentAccount = ChartOfAccount::where('id',$parent_id)->first();
    //     $level = $parentAccount->level;
    //     $code = $parentAccount->id;
        
    //     $newCode = '';
    //     $newLevel = $level+1;
    //     $newName = "Equipment";
    //     $newType = $parentAccount->type;



    //     $childAccounts = ChartOfAccount::where('parent_id',$parent_id)->get();

    //     if($level == 2){

    //         if($childAccounts->isEmpty()){
    //             $newCode = 1000 + $code;
    //         }
    //         else{
    //             $maxChildCode = ChartOfAccount::where('parent_id',$parent_id)->max('id');
    //             $newCode = $maxChildCode + 1000; 
    //         }

    //     }
    //     if($level == 3){

    //         if($childAccounts->isEmpty()){
    //             $newCode = 1 + $code;
    //         }
    //         else{
    //             $maxChildCode = ChartOfAccount::where('parent_id',$parent_id)->max('id');
    //             $newCode = $maxChildCode + 1; 
    //         }

    //     }
    //   dd( $newCode);
    //    try{

    //         $newAccount = new ChartOfAccount;

    //         $newAccount->id = $newCode;
    //         $newAccount->account_name = $newName;
    //         $newAccount->level = $newLevel;
    //         $newAccount->parent_id = $parent_id;
    //         $newAccount->type = $newType;

    //         $newAccount->save();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Account added successfully.',
    //         ],200);

    //    }catch(\Exception $e){

    //         return response()->json([
    //             'success' => false,
    //             'message' => $e->getMessage(),
    //         ], 500);

    //    }

    // }
    public function index()
    {
       
        $accounts = ChartOfAccount::with('children')->whereNull('parent_id')->get();
        $categories = $this->categoryList();
        return view('accounting.chart_of_accounts.index', compact('accounts','categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        // Start a transaction
        DB::beginTransaction();

       

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'account_name' => 'required',
            
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $parent_id = $request->parent_id;


        $parentAccount = ChartOfAccount::where('id',$parent_id)->first();
        $level = $parentAccount->level;
        $code = $parentAccount->id;
        
        $newCode = '';
        $newLevel = $level+1;
        $newAccountName = $request->account_name;
        $newType = $parentAccount->type;


        $childAccounts = ChartOfAccount::where('parent_id',$parent_id)->get();


        if($level == 2){

            if($childAccounts->isEmpty()){
                $newCode = 1000 + $code;
            }
            else{
                $maxChildCode = ChartOfAccount::where('parent_id',$parent_id)->max('id');
                $newCode = $maxChildCode + 1000; 
            }

        }

        if($level == 3){

            if($childAccounts->isEmpty()){
                $newCode = 1 + $code;
            }
            else{
                $maxChildCode = ChartOfAccount::where('parent_id',$parent_id)->max('id');
                $newCode = $maxChildCode + 1; 
            }

        }
    
        try{

            $newAccount = new ChartOfAccount;

            $newAccount->id = $newCode;
            $newAccount->name = $newAccountName;
            $newAccount->level = $newLevel;
            $newAccount->parent_id = $parent_id;
            $newAccount->type = $newType;
            $newAccount->category = $request->category;


            $newAccount->save();
            
            DB::commit();// Commit the transaction

            return response()->json([
                'success' => true,
                'message' => 'Account added successfully.',
            ],200);

        }catch(\Exception $e){

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
        $validator = Validator::make($request->all(), [
            'account_name' => 'required',
            
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }
        try {
            $chart_of_account = ChartOfAccount::find($id);
            $chart_of_account->name = $request->account_name;
            $chart_of_account->category = $request->category;
            $chart_of_account->save();

            return response()->json([
                'success' => true,
                'message' => 'Account Update successfully.',
            ],200);


        } catch (\Exception $e) {
            
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
        //
    }

    public function getByCategory($type)
    {

        if ($type == "BR") {
            $data = ChartOfAccount::where('level', 4)->whereIn('category', ['bank', 'card'])->get();
        } elseif ($type == "BP") {
            $data = ChartOfAccount::where('level', 4)->whereIn('category', ['bank', 'card'])->get();
        } elseif ($type == "CR") {
            $data = ChartOfAccount::where('level', 4)->where('category', 'cash')->get();
        } elseif ($type == "CP") {
            $data = ChartOfAccount::where('level', 4)->where('category', 'cash')->get();
        }else {
            $data = ChartOfAccount::where('level', 4)->get();
            
        }
       return response()->json($data);
    }

    public function categoryList()
    {
        $data = [
            'cash',
            'card',
            'bank',
        ];
        return $data;
    }

   

}
