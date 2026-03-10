<div class="row">

    <div class="col-md-3 mb-3 d-none">
        <label class="form-label">Product Type</label>
        <select name="product_type" class="select2 form-select" style="width:100%">
            <!--<option value="">Choose...</option>-->
            @foreach ($productTypes as $type)
                <option @selected($product ? $product->product_type == $type->name : false) value="{{ $type->name }}">{{ $type->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3 mb-3">
        <label class="form-label">Product Name</label>
        <input id="product_name" name="product_name" type="text" class="form-control"
            value="{{ $product ? $product->name : '' }}">
    </div>

    <div class="col-md-3 mb-3">
        <label class="form-label">Category</label>
        <select name="category_id" class="select2 form-select" style="width:100%">
            <option value="">Choose...</option>
            @foreach ($categories as $category)
                <option @selected($product ? $product->category_id == $category->id : false) value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Brand</label>
        <select name="brand_id" class="select2 form-select" style="width:100%">
            <option value="">Choose...</option>
            @foreach ($brands as $brand)
                <option @selected($product ? $product->brand_id == $brand->id : false) value="{{ $brand->id }}">{{ $brand->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 mb-3 d-none">
        <label class="form-label">Product Model</label>
        <select name="product_model_id" class="select2 form-select" style="width:100%">
            <option value="">Choose...</option>
            @foreach ($productModels as $productModel)
                <option @selected($product ? $product->product_model_id == $productModel->id : false) value="{{ $productModel->id }}">{{ $productModel->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 mb-3 d-none">
        <label class="form-label">Custom Field</label>
        <select name="custom_field_id" class="select2 form-select" style="width:100%">
            <option value="">Choose...</option>
            @foreach ($customFields as $customField)
                <option @selected($product ? $product->custom_field_id == $customField->id : false) value="{{ $customField->id }}">{{ $customField->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 mb-3 d-none">
        <label class="form-label">Material</label>
        <select name="material_id" class="select2 form-select" style="width:100%">
            <option value="">Choose...</option>
            @foreach ($materials as $material)
                <option @selected($product ? $product->material_id == $material->id : false) value="{{ $material->id }}">{{ $material->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 mb-3 d-none">
        <label class="form-label">Product Group</label>
        <select name="product_group_id" class="select2 form-select" style="width:100%">
            <option value="">Choose...</option>
            @foreach ($productGroups as $productGroup)
                <option @selected($product ? $product->product_group_id == $productGroup->id : false) value="{{ $productGroup->id }}">{{ $productGroup->name }}
                </option>
            @endforeach
        </select>
    </div>


    <div class="col-md-3 mb-3 d-none">
        <label class="form-label">Unit</label>
        <select name="unit_id" class="select2 form-select" style="width:100%">
            <option value="">Choose...</option>
            @foreach ($units as $unit)
                <option @selected($product ? $product->unit_id == $unit->id : false) value="{{ $unit->id }}">
                    {{ 'Purchase: ' . $unit->base_unit . ' | Sale: ' . $unit->child_unit }}
                </option>
            @endforeach
        </select>
    </div>
    
    
    <div class="col-md-3 mb-3 d-none">
        <label class="form-label">Check Stock Qty</label>
        <select name="check_stock_qty" class="form-select form-control" style="width:100%">
            <option @selected($product ? $product->check_stock_qty == 1 : true) value="1">Yes</option>
            <option @selected($product ? $product->check_stock_qty == 0 : false) value="0">No</option>
        </select>
    </div>
    <div class="col-md-3 mb-3 d-none">
            <label class="form-label">Price Editable
               <span class="fa fa-question-circle" title="Price can be edited in POS order list"></span> 
            </label>        
            <select name="is_price_editable" class="form-select form-control" style="width:100%">
                <option @selected($product ? $product->is_price_editable == 0 : true) value="0">No</option>
                <option @selected($product ? $product->is_price_editable == 1 : false) value="1">Yes</option>
        </select>
    </div>
    <div class="col-md-3 mb-3 d-none">
          <label class="form-label">Qty in Decimal Allowed
               <span class="fa fa-question-circle" title="in POS qunatity can be in deciaml"></span> 
            </label>     
        <select name="is_decimal_qty_allowed" class="form-select form-control" style="width:100%">
            <option @selected($product ? $product->is_decimal_qty_allowed == 0 : true) value="0">No</option>
            <option @selected($product ? $product->is_decimal_qty_allowed == 1 : false) value="1">Yes</option>
        </select>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Is Active</label>
        <select name="is_active" class="form-select form-control" style="width:100%">
            <option @selected($product ? $product->is_active == 1 : true) value="1">Yes</option>
            <option @selected($product ? $product->is_active == 0 : false) value="0">No</option>
        </select>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Is Featured</label>
        <select name="is_featured" class="form-select form-control" style="width:100%">
            <option @selected($product ? $product->is_featured == 0 : true) value="0">No</option>
            <option @selected($product ? $product->is_featured == 1 : false) value="1">Yes</option>
        </select>
    </div>
    <div class="col-md-8 mb-3 d-none">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ $product ? $product->description : '' }}</textarea>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Image</label>
        <input type="file" class="form-control" name="product_image">
    </div>
    
    @if($product)
        <div class="col-md-4 mb-3 d-flex justify-content-center align-items-center">
            <img src="{{ asset('pos/products/' . $product->image)  }}" alt="Product Image" class="img-fluid " style="width: auto; height: 100px;">
        </div>
    @endif

</div>
