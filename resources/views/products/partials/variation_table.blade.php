<table class="table" id="variant-table">
    <thead>

        <tr class="border">
            <th class="text-center border toggle-visibility"></th>
            <th class="text-center border toggle-visibility">Variaiton</th>
            <th class="text-center border">Name </th>
            <th class="text-center border">Barcode </th>
            <th class="text-center border">Purchase Price</th>
            <th class="text-center border">selling Price</th>
            <th class="text-center border d-none">Image</th>
            <th class="toggle-visibility"></th>
        </tr>
    </thead>
    <tbody>
       @if($product)

            @foreach ($product->variations as $variation)
                <tr>
                    <td class="border toggle-visibility">
                        <div class="add-product">
                            <input type="checkbox" class="form-check-input is-available" name="is_available[]" checked>
                        </div>
                        <input type="hidden" class="form-control variant-id" name="product_variation_id[]" value="{{ $variation->id }}">

                    </td>
                    <td class="border toggle-visibility">
                        <div class="add-product">
                            <input type="text" class="form-control variant-size" name="size[]" value="{{ $variation->size }}" placeholder="e.g S,M,L" readonly> 
                        </div>
                    </td>
                    <td class="border">
                        <div class="add-product">
                            <input type="text" class="form-control variant-name"  name="name[]" value="{{ $variation->name }}" placeholder="" readonly>
                        </div>
                    </td>
                    <td class="border">
                        <div class="add-product">
                            <input type="text" class="form-control variant-barocde"  name="barcode[]" value="{{ $variation->barcode }}" min="5" max="5" placeholder="Enter 5 Digits barcode e.g code:19 will be like 00019">
                        </div>
                    </td>
                    <td class="border">
                        <div class="add-product">
                            <input type="number" class="form-control text-end purchase-price" step="0.01"  name="purchase_price[]" value="{{ $variation->purchase_price }}">
                        </div>
                    </td>    
                    <td class="border">
                        <div class="add-product">
                            <input type="number" class="form-control text-end selling-price" step="0.01"  name="selling_price[]" value="{{ $variation->selling_price }}">
                        </div>
                    </td>    
                
                    <td class="border  d-none">
                        <div class="add-product">
                            <input type="file" class="form-control"  name="image[]">
                        </div>
                    </td>
                    <td class="border toggle-visibility">
                        @if($variation->is_purchased == true)
                        <button type="button" class="btn btn-danger delete-row" disabled>Delete </button>
                        @else
                        <button type="button" class="btn btn-danger delete-row" >Delete </button>

                        @endif
                    </td>

                </tr>
            @endforeach

       @endif

    </tbody>
</table>
