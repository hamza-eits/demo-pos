<div class="row">
    <div class="col-md-3">
        <label class="form-label">Product Type</label>
        <div class="d-flex">
            <div class="form-check me-3">
                <input 
                    class="form-check-input" 
                    type="radio" 
                    name="variation_type" 
                    value="single"
                    id="single-product-radio"
                    @checked($product ? $product->variation_type == 'single' : false)
                >
                <label class="form-check-label" for="single-product-radio">Single
                    Product</label>
            </div>
        </div>
        <div class="d-flex mt-2">
            <div class="form-check me-3">
                <input 
                    class="form-check-input" 
                    type="radio" 
                    name="variation_type" 
                    value="variable"
                    id="variable-product-radio"
                    @checked($product ? $product->variation_type == 'variable' : false)
                >
                <label class="form-check-label" for="variable-product-radio">Variable
                    Product</label>
            </div>
        </div>
    </div>

    <div class="col-md-3 toggle-visibility">
        <label class="form-label">Variation</label>
        <div class="input-group">
            <select class="form-control form-select" id="variation-select">
                <option value="">Choose Variation...</option>
                @foreach ($variation as $varient)
                    <option data-id="{{ $varient->id }}" data-values="{{ $varient->values }}"
                        value="{{ $varient->id }}">{{ $varient->name }}</option>
                @endforeach
            </select>
            <button type="button" id="add-custom-variant" class="btn btn-primary btn-sm">+</button>
        </div>
    </div>

    <div class="col-md-3 toggle-visibility">
        <label class="form-label">Common Purchase Price</label>
        <input type="number" class="form-control" step="0.01" name="common_purchase_price" id="common-purchase-price">
    </div>

    <div class="col-md-3 toggle-visibility">
        <label class="form-label">Common Selling Price</label>
        <input type="number" class="form-control" step="0.01" name="common_selling_price" id="common-selling-price">
    </div>
</div>
