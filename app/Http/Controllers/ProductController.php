<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Material;
use App\Models\Variation;
use App\Models\CustomField;
use App\Models\ProductType;
use App\Models\Startup\Tax;
use App\Models\ProductGroup;
use App\Models\ProductModel;
use App\Models\Startup\Unit;
use Illuminate\Http\Request;
use App\Models\Startup\Brand;
use App\Models\ProductVariation;
use App\Models\Startup\Category;
use App\Services\ProductService;
use Yajra\DataTables\DataTables;
use App\Models\Startup\Warehouse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;


class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       
        try {
            if ($request->ajax()) {
                $data = ProductVariation::select('id','name','product_id','barcode','selling_price','purchase_price')->get();

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
                                            <a href="' . route('product.edit', $row->product_id) . '"  class="dropdown-item">
                                                <i class="bx bx-pencil font-size-16 text-secondary me-1"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" onclick="productDelete(' . $row->product_id . ')" class="dropdown-item">
                                                <i class="bx bx-trash font-size-16 text-danger me-1"></i> Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>';


                        return $btn;
                    })
                    /* product
                        ->addColumn('image', function ($row) {
                            return $this->productService->getProductImageHtml($row->image);
                        })
                    */
                    ->rawColumns(['action']) // Mark these columns as raw HTML
                    ->make(true);
            }

            return view('products.index');
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

        $categories = Category::all();
        $brands = Brand::all();
        $taxes = Tax::all();
        $units = Unit::all();
        $variation = Variation::all();
        $productTypes = ProductType::where('id',3)->get();
        $productModels = ProductModel::all();
        $customFields = CustomField::all();
        $materials = Material::all();
        $productGroups = ProductGroup::all();


        return view('products.create', compact('taxes', 'categories', 'brands', 'units', 'variation', 'productTypes', 'productModels', 'customFields', 'materials', 'productGroups'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $validation = $this->productService->validateRequest($request->all());

            if (!$validation['success']) {
                return response()->json($validation);
            }

            $product = $this->productService->createOrUpdateProduct($request, null);

            $this->productService->createOrUpdateProductVariations($request, $product);


            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product added successfully.',
                'redirect_route' => route('product.index'),
            ], 200);
        } catch (\Exception $e) {

            DB::rollBack();

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
        $product = Product::with('variations')->find($id);

        $product->variations->each(function ($variation) {
            $variation->is_purchased = DB::table('invoice_details')
            ->where('product_variation_id',$variation->id)->exists(); // or your custom logic
        });

        $categories = Category::all();
        $brands = Brand::all();
        $taxes = Tax::all();
        $units = Unit::all();
        $variation = Variation::all();
        $productTypes = ProductType::where('id',3)->get();
        $productModels = ProductModel::all();
        $customFields = CustomField::all();
        $materials = Material::all();
        $productGroups = ProductGroup::all();

        return view('products.edit', compact(
            'product', 
            'taxes', 
            'categories', 
            'brands', 'units', 
            'variation', 
            'productTypes',
            'productModels',
            'customFields',
            'materials',
            'productGroups',
        ));
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


        // Start a transaction
        DB::beginTransaction();

        try {
            

            $validation = $this->productService->validateRequest($request->all());

            if (!$validation['success']) {
                return response()->json($validation);
            }

            $product = $this->productService->createOrUpdateProduct($request, $id);

            $this->productService->deleteRemovedProductVariations($id,$request->product_variation_id);

            $this->productService->createOrUpdateProductVariations($request, $product);




            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product added successfully.',
                'redirect_route' => route('product.index'),
            ], 200);
        } catch (\Exception $e) {

            DB::rollBack();

            dd($e->getMessage());

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
  
        DB::beginTransaction();// Start a transaction

        try {
            $product = Product::with('variations')->find($id);

            $variationIds = $product->variations->pluck('id')->toArray();

            $is_purchased = DB::table('invoice_details')
                ->whereIn('product_variation_id', $variationIds)
                ->exists();

            if($is_purchased)
            {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot Delete Prodcut beacuse its has record',
                ], 500);
            }
            else
            {

                $product->variations()->delete();

                $product->delete();// Delete the brand record

                DB::commit();// Commit the transaction

                return response()->json([
                'success' => true,
                'message' => 'Product Delete successfully.',
                ],200);

            }

          
                
        } catch (\Exception $e) {
          
            DB::rollBack();  // Rollback the transaction if there is an error

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function exportCsv()
    {
        $products = ProductVariation::all();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="products.csv"',
        ];

        $columns = ['ID', 'Name', 'Purchase Price', 'Selling Price'];

        $callback = function () use ($products, $columns) {
            $file = fopen('php://output', 'w');
            // Write the column headers
            fputcsv($file, $columns);

            // Write the data rows
            foreach ($products as $product) {
                fputcsv($file, [
                    $product->id,
                    $product->name,
                    $product->purchase_price,
                    $product->selling_price,
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
 
}
