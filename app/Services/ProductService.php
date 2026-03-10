<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductService
{

    public function validateRequest($data)
    {
        $validator = Validator::make($data, [
            'product_name' => 'required',
            'product_type' => 'required',
            'barcode.*' => 'required|min:5|max:5',
            'selling_price.*' => 'required|min:0.01',
            // Add more fields as needed
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'message' => $validator->errors()->first()
            ];
        }

        return ['success' => true];
    }

    public function createOrUpdateProduct(Request $request, $id = null)
    {
        $data = [
            'name' => $request->product_name,
            'product_type' => $request->product_type,
            'variation_type' => $request->variation_type,
            
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'unit_id' => $request->unit_id,
            
            'product_group_id' => $request->product_group_id,
            'material_id' => $request->material_id,
            'custom_field_id' => $request->custom_field_id,
            'product_model_id' => $request->product_model_id,
            
            'description' => $request->description,
            
            'check_stock_qty' => $request->check_stock_qty,
            'is_price_editable' => $request->is_price_editable,
            'is_decimal_qty_allowed' => $request->is_decimal_qty_allowed,
            'is_active' => $request->is_active,
            'is_featured' => $request->is_featured,
        ];

        if ($request->has('product_image')) {
            $data['image'] = $this->imageUpload($request->product_image);
        }

        $product = null;
        if ($id) {
            $product = Product::find($id);
            $product->update($data);
        } else {
            $product = Product::create($data);
        }

        return $product;
    }



    public function createOrUpdateProductVariations(Request $request, $product)
    {
        for ($i = 0; $i < count($request->selling_price); $i++) {
            $data = [
                'product_id' => $product->id,
                'product_type' => $product->product_type,
                'size' => $request->size[$i],
                'name' => $request->name[$i], 
                'barcode' => $request->barcode[$i], 
                'selling_price' => $request->selling_price[$i],
                'purchase_price' => $request->purchase_price[$i],

                'check_stock_qty' => $product->check_stock_qty,
                'is_price_editable' => $product->is_price_editable,
                'is_decimal_qty_allowed' => $product->is_decimal_qty_allowed,
                'is_active' => $product->is_active,
                'is_featured' => $product->is_featured,
            ];


            $variation_id = $request->product_variation_id[$i];

            if (!empty($variation_id)) {
                // Update by ID
                ProductVariation::where('id', $variation_id)->update($data);
            } else {
                // Create new variation
                ProductVariation::create($data);
            }
        }
    }

   

    public function getProductImageHtml($image)
    {
        $imageUrl = $image
            ? asset('pos/products/' . $image)
            : asset('pos/products/default.png');

        return '
            <a href="javascript:void(0);" class="item-img stock-img">
                <img src="' . $imageUrl . '" alt="' . $image . '" width="50px" height="50px">
            </a>';
    }

   

    public function imageUpload($image)
    {
        $name = time() . '_' . mt_rand(1000, 9999) . '.' . $image->extension();
        $image->move(public_path('pos/products'), $name);
        return $name;
    }

    public function deleteRemovedProductVariations($productId,$submittedVariationIds)
    {


        $excludedVariationIds  = DB::table('product_variations')
            ->where('product_id',$productId)
            ->whereNotIn('id', $submittedVariationIds)
            ->pluck('id');



        DB::table('product_variations')
            ->whereIn('id', $excludedVariationIds)
            ->delete();
    }

    


    

}
