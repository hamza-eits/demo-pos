<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Startup\Unit;
use Illuminate\Http\Request;
use App\Models\ProductVariation;
use App\Models\RecipeDetail;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */



    public function index(Request $request)
    {

        $productVariations =  ProductVariation::all();

        try {
            if ($request->ajax()) {


                $query = Recipe::orderBy('id', 'desc');

                if ($request->has('is_active') && $request->is_active != null) {
                    $query->where('is_active', $request->is_active);
                } else {
                    $query->where('is_active', 1); // by default show only active one
                }

                if ($request->product_variation_id) {
                    $query->where('product_variation_id', $request->product_variation_id);
                }
                if ($request->start_date && $request->end_date) {
                    $query->whereBetween('start_date', [$request->start_date, $request->end_date]);
                }


                $data = $query->get();

                return Datatables::of($data)
                    ->addIndexColumn()
                    // Status toggle column
                    ->addColumn('item_name', function ($row) {
                        return $row->productVariation->product->name . '-' . $row->productVariation->name ?? 'N/A';
                    })


                    ->addColumn('action', function ($row) {
                        $btn = '
                            <div class="d-flex align-items-center col-actions">
                                <div class="dropdown">
                                    <a class="action-set show" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="true">
                                        <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a href="' . route('recipe.show', $row->id) . '"  class="dropdown-item">
                                                <i class="bx bx-show font-size-16 text-primary me-1"></i> Show
                                            </a>
                                        </li>
                                        <li>
                                            <a href="' . route('recipe.edit', $row->id) . '" class="dropdown-item">
                                                <i class="bx bx-pencil font-size-16 text-secondary me-1"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a href="' . route('recipe.createVersion', $row->id) . '" class="dropdown-item">
                                                <i class="bx bx-cog font-size-16 text-warning me-1"></i> Version
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" onclick="deleteRecipe(' . $row->id . ')" class="dropdown-item">
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

            return view('recipes.index', compact('productVariations'));
        } catch (\Exception $e) {

            return back()->with('flash-danger', $e->getMessage());
        }
    }

    public function create()
    {
        $variaitonTypeRaw  = ProductVariation::where('product_type', 'raw')->get();
        $units = Unit::all();
        $variaitonTypeDish = ProductVariation::getDishWithoutRecipe();


        return view('recipes.create', compact('variaitonTypeRaw', 'units', 'variaitonTypeDish'));
    }

    public function makeOldRecipeInactive($recipe_id)
    {

        $recipe = Recipe::find($recipe_id);
        $recipe->update([
            'is_active' => 0,
            'end_date' => now()->format('Y-m-d'), // Use Carbon's now() for consistency
            'end_time' => now()->format('H:i'),
        ]);
    }

    public function store(Request $request)
    {
        if ($request->has('old_recipe_id')) {
            $this->makeOldRecipeInactive($request->old_recipe_id);
        }


        // Start a transaction
        DB::beginTransaction();

        try {

            // Validate the request data
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'description' => 'nullable', // Validation for image
                'variaiton_type_dish_id' => 'required',
                'variaiton_type_raw_id.*' => 'required',
                'unit_id.*' => 'required',
                'quantity.*' => 'required',

            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ]);
            }


            $recipe = Recipe::create([
                'start_date' => $request->input('start_date'),
                'start_time' => $request->input('start_time'),
                'product_variation_id' => $request->input('variaiton_type_dish_id'),
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'total_quantity' => $request->input('total_quantity'),
                'created_by' => Auth::id(),
            ]);




            for ($i = 0; $i < count($request->variaiton_type_raw_id); $i++) {
                RecipeDetail::create([
                    'recipe_id' => $recipe->id,
                    'product_variation_id'    => $request->variaiton_type_raw_id[$i],
                    'unit_id'    => $request->unit_id[$i],
                    'quantity'   => $request->quantity[$i],
                ]);
            }

            DB::commit(); // Commit the transaction

            // Return a JSON response with a success message
            return response()->json([
                'success' => true,
                'message' => 'Recipe added successfully.',
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

    /**
     * Show the form for editing the specified resource.
     */
    public function show($id)
    {
        $items  = ProductVariation::all();
        $units = Unit::all();

        try {
            $recipe = Recipe::with('details')->findOrFail($id);
            return view('recipes.show', compact('recipe', 'items', 'units'));
        } catch (\Exception $e) {
            // Return a JSON response with an error message
            return response()->json([
                'message' => $e->getMessage(),
                'success' => false,
            ], 500);
        }
    }

    public function edit($id)
    {
        $variaitonTypeRaw  = ProductVariation::where('product_type', 'raw')->get();
        $units = Unit::all();
        $variaitonTypeDish = ProductVariation::getDishWithRecipe();

        try {
            $recipe = Recipe::with('details')->findOrFail($id);
            return view('recipes.edit', compact('recipe', 'variaitonTypeRaw', 'units', 'variaitonTypeDish'));
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
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'nullable', // Validation for image
            'is_active' => 'required',
            'item_id.*' => 'required',
            'unit_id.*' => 'required',
            'quantity.*' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }



        DB::beginTransaction();


        try {


            $recipe = Recipe::find($id);

            $recipe->update([
                'start_date' => $request->input('start_date'),
                'start_time' => $request->input('start_time'),
                'product_variation_id' => $request->input('variaiton_type_raw_id'),
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'total_quantity' => $request->input('total_quantity'),
                'end_date' => $request->input('end_date') ?? null,
                'end_time' => $request->input('end_time') ?? null,
                'is_active' => $request->input('is_active'),
                'updated_by' => Auth::id(),
            ]);


            $recipe->details()->delete(); // Delete existing recipe details

            for ($i = 0; $i < count($request->variaiton_type_raw_id); $i++) {
                RecipeDetail::create([
                    'recipe_id' => $recipe->id,
                    'product_variation_id' => $request->variaiton_type_raw_id[$i],
                    'unit_id'    => $request->unit_id[$i],
                    'quantity'   => $request->quantity[$i],
                ]);
            }

            DB::commit(); // Commit the transaction

            // Return a JSON response with a success message
            return response()->json([
                'success' => true,
                'message' => 'Recipe Update successfully.',
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
            $recipe = Recipe::find($id);

            $recordExists = DB::table('invoice_master')->where('recipe_id', $id)->first();
            if ($recordExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Recipe Record Exists.',
                ], 200);
            }

            DB::table('recipe_detail')->where('recipe_id', $id)->delete();

            $recipe->delete(); // Delete the recipe record

            DB::commit(); // Commit the transaction

            return response()->json([
                'success' => true,
                'message' => 'Recipe Delete successfully.',
            ], 200);
        } catch (\Exception $e) {

            DB::rollBack();  // Rollback the transaction if there is an error

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }





    public function getRecipeDetailWithStock($id)
    {

        try {
            $recipe = Recipe::findOrFail($id);

            $recipeDetails = [];

            foreach ($recipe->recipeDetails as $detail) {
                $averageCost = ProductVariation::averageCost($detail->item_id);

                //bcdiv() ensures higher precision during division, and rounding is done at the final step.
                $quantity = round($detail->quantity, 2);
                $balance = round(bcdiv($averageCost['balance'], 1, 2), 2);
                $purchase_unit_price = round(bcdiv($averageCost['avg_cost'], 1, 2), 2);

                $recipeDetails[] = [
                    'item_id' => $detail->item_id,
                    'name' => $detail->item->name,
                    'base_unit' => $detail->item->unit->base_unit,
                    'quantity' => $quantity,
                    'balance' => $balance,
                    'purchase_unit_price' => $purchase_unit_price,
                ];
            }




            // $recipeDetails = DB::table('v_recipe_detail_stock')->where('recipe_id',$id)->get();

            return response()->json([
                'recipe' => $recipe,
                'recipeDetails' => $recipeDetails,
            ]);
        } catch (\Exception $e) {
            // Return a JSON response with an error message
            return response()->json([
                'message' => $e->getMessage(),
                'success' => false,
            ], 500);
        }
    }

    public function createVersion($id)
    {

        $old_recipe_id = $id;
        $items  = ProductVariation::where('product_type', 'raw')->get();
        $units = Unit::all();
        $productVariations = ProductVariation::where('product_type', 'Good')->get();
        try {
            $recipe = Recipe::findOrFail($id);
            $recipeDetails = $recipe->recipeDetails;
            return view('recipes.create_version', compact('recipe', 'recipeDetails', 'items', 'units', 'productVariations', 'old_recipe_id'));
        } catch (\Exception $e) {
            // Return a JSON response with an error message
            return response()->json([
                'message' => $e->getMessage(),
                'success' => false,
            ], 500);
        }
    }
}
