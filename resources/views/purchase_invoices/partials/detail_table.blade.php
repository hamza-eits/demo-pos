

<table id="table" class="table table-border" style="border-collapse:collapse;">
    <thead>
        <tr>
            <th width="20%" class="text-start">Item</th>
            <th width="10%" class="text-center d-none">Unit</th>
            <th width="5%" class="text-center d-none">Base Unit</th>
            <th width="5%" class="text-center">{{ __('file.Quantity') }}</th>
            <th width="5%" class="text-center">Base Unit Qty</th>
            <th width="10%" class="text-center">Cost Price</th>
            <th width="5%" class="text-center d-none">Purchase <br> Category</th>
            <th width="10%" class="text-center">Subtotal</th>
            <th width="10%" class="text-center">{{ __('file.Tax') }} %</th>
            <th width="10%" class="text-center">{{ __('file.Tax') }} Amount</th>
            <th width="10%" class="text-center">Total</th>
            <th width="5%" class="text-center"></th>
        </tr>
    </thead>
    <tbody>
        @if($purchaseInvoice)
            @foreach ($purchaseInvoice->details as $detail)
                <tr>
                    <td>
                        <select name="product_variation_id[]"
                            class="form-control row-item-select select2" style="width: 100%">
                            <option value="">Choose...</option>
                            @foreach ($productVariations as $variation)
                                <option @selected($variation->id == $detail->product_variation_id) value="{{ $variation->id }}"
                                    data-variation-unit-id="{{ $variation->product->unit_id }}"
                                    data-variation-type="{{ $variation->product_type }}"
                                    data-variation-barcode="{{ $variation->barcode }}"
                                    data-selling-price="{{ $variation->selling_price }}"
                                    >
                                    {{ $variation->name }}
                                </option>
                            @endforeach
                        </select>
                        
                        <input type="hidden" name="product_type[]" class="row-product-type" value="{{ $detail->product_type }}">
                        <input type="hidden" name="variation_barcode[]" class="row-variation-barcode"  value="{{ $detail->variation_barcode }}">
                        <input type="hidden" name="selling_price[]" class="row-selling-price"  value="{{ $detail->selling_price }}">
                    </td>
                    <td  class="d-none">
                        <select name="unit_id[]" class="form-control row-unit-select select2"
                            style="width: 100%">
                            @foreach ($units as $unit)
                                <option @selected($unit->id == $detail->unit_id) value="{{ $unit->id }}"
                                    data-base-unit-value="{{ $unit->base_unit_value }}"
                                    data-variation-barcode ="{{ $variation->barcode }}"
                                    >
                                    {{ $unit->base_unit }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="d-none">
                        <input type="number" name="base_unit_value[]"
                            class="row-base-unit-value form-control text-end"
                            value="{{ $detail->base_unit_value }}" readonly>
                    </td>
                
                    <td>
                        <input type="number" name="quantity[]"
                            class="row-quantity row-calculation form-control text-end"
                            step="0.01" value="{{ $detail->quantity }}">
                    </td>
                    <td>
                        <input type="number" name="base_unit_qty[]"
                            class="row-base-unit-qty form-control text-end"
                            value="{{ $detail->base_unit_qty }}" readonly>
                    </td>
                    <td>
                        <input type="number" name="unit_price[]"
                            class="form-control text-end row-unit-price row-calculation"
                            step="0.01" value="{{ $detail->unit_price }}">
                    </td>
                    <td class="d-none">
                        <select name="purchase_category[]" class="form-control select2" style="width: 100%">
                            <option value="">Choose...</option>
                            @foreach ($purchaseCategories as $category)
                                <option @selected($category->name == $detail->purchase_category) value="{{ $category->name }}">{{ $category->name }}</option>
                            @endforeach
                        </select>   
                    </td>
                
                    <td>
                        <input type="number" name="subtotal[]"
                            class="form-control text-end row-subtotal" step="0.01" readonly
                            value="{{ $detail->subtotal }}">
                    </td>
                    <td>
                        <input type="hidden" name="tax_type[]" value="exclusive">

                        <select name="tax_value[]"
                            class="form-control form-select row-tax-select">
                            @foreach ($taxes as $tax)
                                <option @selected($tax->rate == $detail->tax_value)
                                    data-tax-value="{{ $tax->rate }}"
                                    value="{{ $tax->rate }}">{{ $tax->rate }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="tax_amount[]"
                            class="form-control text-end row-tax-amount" step="0.01" readonly
                            value="{{ $detail->tax_amount }}">
                    </td>
                    <td>
                        <input type="number" name="subtotal_after_tax[]"
                            class="form-control text-end row-subtotal-after-tax row-calculation"
                            step="0.01" readonly value="{{ $detail->subtotal_after_tax }}">
                    </td>

                    <td class="text-center">
                        <a href="#"><span style="font-size:18px"
                                class="bx bx-trash text-danger row-trash-btn"></span></a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
    <tfoot>
        <tr>
            <td class="fw-bold">Total</td>
            <td class="d-none"></td>
            <td class="d-none"></td>
            <td>
                <input type="number" name="total_quantity" id="total-quantity"
                    class="form-control border-0 fw-bold text-end" value="0"
                    step="0.01">
            </td>
            <td></td>
            <td></td>
            
            <td>
                <input type="number" name="purchase_subtotal" id="purchase-subtotal"
                    class="form-control border-0 fw-bold text-end" value="0"
                    step="0.01">
            </td>
            <td></td>

            <td>
                <input type="number" name="purchase_tax_amount" id="purchase-tax-amount"
                    class="form-control border-0 fw-bold text-end" value="0"
                    step="0.01">
            </td>
            <td>
                <input type="number" name="purchase_grand_total" id="purchase-grand-total"
                    class="form-control border-0 fw-bold text-end" value="0"
                    step="0.01">
            </td>
            <td></td>
        </tr>
    </tfoot>

</table>
<button class="btn btn-primary btn-sm" id="add-row">
    <span class="bx bx-plus"></span>
    Add Row
</button>
