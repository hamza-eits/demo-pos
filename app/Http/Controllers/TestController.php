<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceDetail;
use App\Models\InvoiceMaster;
use App\Models\InvoicePayment;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ProductVariation;
use App\Models\Accounting\Journal;
use App\Models\Accounting\Voucher;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Picqer\Barcode\BarcodeGeneratorPNG;

class TestController extends Controller
{
    /**
     * Handle the incoming request.
     */


    
    public function __invoke(Request $request)
    {
        


        dd("stop");
        


        // DB::beginTransaction();
        // try {

           
        // $productVariations = ProductVariation::all();

        // foreach($productVariations as $variation)
        // {
        //     $barcode = $variation->barcode;
        //     $newbarcode = substr($barcode, 2); // trims the first two digits

        //     DB::table('product_variations')->where('id', $variation->id)->update([
        //         'barcode' => $newbarcode
        //     ]);

        // }
            
        //     DB::commit();

        //     return response()->json('done');
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     throw $e;
        // }
    }
    
    

    
  
}

