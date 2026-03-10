@foreach ($variations as $variation)

    <div class="col-md-4 text-center mb-3">
        <div class="product-variation-img" 
            title="{{ $variation->name ?? '' }}" 
            data-id="{{ $variation->id }}"
            data-name="{{ $variation->name }}" 
            data-variation-barcode="{{ $variation->barcode }}"
            data-selling-price="{{ $variation->selling_price }}"
            data-product-type="{{ $variation->product_type }}"
            data-product-description="{{ $variation->product->description }}"
            data-product-is-price-editable="{{ $variation->product->is_price_editable }}"
            data-product-is-decimal-qty-allowed="{{ $variation->product->is_decimal_qty_allowed }}"
            data-unit-cost = "{{ $variation->purchase_price }}"  
            >


            <div class="p-3 bg-light rounded shadow-sm h-100" style="cursor: pointer;">
                <h5 class="mb-2">{{ $variation->barcode.' - '.$variation->name }}</h5>
                <div class="mb-2">
                    <strong class="text-primary">AED {{ $variation->selling_price }}</strong>
                </div>
            </div>
        </div>
    </div>

@endforeach
