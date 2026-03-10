<div id="order-items-table-div" class="table-responsive" style="height:400px;max-height: 400px; overflow-y: auto;">
    <table class="table table-hover align-middle text-center" id="order-items-table">
        <thead class="table-light">
            <tr>
                <th width="10%">Product</th>
                <th width="10%">Price</th>
                <th width="2%">−</th>
                <th width="5%">{{ __('file.Quantity') }}</th>
                <th width="2%">+</th>
                <th width="10%">Subtotal</th>
                <th width="10%">Discount</th>
                <th width="10%">After Dis.</th>
                <th width="5%">Actions</th>
                <th class="d-none"></th>
            </tr>
        </thead>
        <tbody id="order-items-tbody">
            @if ($order)
                @foreach ($order->details as $detail)
                    @php
                        $order_addons = DB::table('invoice_details')
                            ->where('invoice_master_id', $id)
                            ->where('child_no', $detail->parent_no)
                            ->get();
                    @endphp

                    <tr @if ($order_addons->isEmpty()) data-id="{{ $detail->product_variation_id }}" @endif>


                        <td class="text-start">
                            {{ $detail->productVariation->name }}
                            <input type="hidden" name="product_type[]" value="{{ $detail->product_type }}">

                            <input type="hidden" name="parent_no[]" value="{{ $detail->parent_no }}">

                            <input type="hidden" name="product_variation_id[]"
                                value="{{ $detail->product_variation_id }}">
                            <input type="hidden" name="stock_barcode[]" value="{{ $detail->stock_barcode }}">
                            <input type="hidden" name="variation_barcode[]" value="{{ $detail->variation_barcode }}">
                            <input type="hidden" name="description[]" value="{{ $detail->variation_barcode }}">

                            <br>
                            <span class="addon-item-name text-warning"></span>
                        </td>
                        
                        <td>
                            <input type="hidden" name="unit_cost[]" value="{{ $detail->unit_cost }}">
                            <input 
                                type="number" 
                                name="unit_price[]" 
                                class="row-unit-price js-event-auto-highlight row-calculation form-control form-control-sm text-center" step="0.01" min="0" 
                                value="{{ $detail->unit_price }}" 
                                {{ $detail->productVariation->is_price_editable == 1 ? '' : 'readonly'}}
                            >
                        </td>
                                {{-- Minus --}}
                        <td>
                            <a class="row-minus-btn text-danger" style="cursor: pointer;" title="Decrease quantity">
                                <i class="fa fa-minus-square fa-lg"></i>
                            </a>
                        </td>
                        {{-- Quantity --}}
                     
                         <td>
                            <input 
                                type="number" 
                                name="quantity[]" 
                                class="row-quantity row-calculation form-control form-control-sm text-center" 
                                min="{{ $detail->productVariation->is_decimal_qty_allowed == 1 ? '0.01' : '1' }}"
                                step="{{ $detail->productVariation->is_decimal_qty_allowed == 1 ? '0.01' : '1' }}"
                                data-product-is-decimal-qty-allowed="{{ $detail->productVariation->is_decimal_qty_allowed }}"
                                value="{{ $detail->quantity }}"

                            > 
                        </td>
                        {{-- Plus --}}
                        <td>
                            <a class="row-plus-btn text-success" style="cursor: pointer;" title="Increase quantity">
                                <i class="fa fa-plus-square fa-lg"></i>
                            </a>
                        </td>
                        {{-- subtotal --}}
                        <td>
                            <input type="number" name="subtotal[]"
                                class="row-subtotal form-control form-control-sm text-center"
                                value="{{ $detail->grand_total }}" readonly>


                            <input type="hidden" name="cost_amount[]" class="row-cost-amount form-control form-control-sm text-center" value="{{ $detail->cost_amount }}">

                        </td>
                        {{-- Discount --}}
                        <td>
                            <div class="input-group">
                                <input type="number" name="discount_value[]"
                                    class="row-discount-value form-control form-control-sm text-center"
                                    value="{{ $detail->discount_value }}" readonly step="0.01">

                                <input type="hidden" name="discount_type[]" class="row-discount-type"
                                    value="{{ $detail->discount_type }}">

                                <input type="hidden" name="discount_amount[]" class="row-discount-amount"
                                    value="{{ $detail->discount_amount }}" step="0.01">

                                <input type="hidden" name="discount_unit_price[]" class="row-discount-unit-price"
                                    value="{{ $detail->discount_unit_price }}" step="0.01">
                            </div>
                        </td>

                        <td>
                            <input type="number" name="subtotal_after_discount[]"
                                class="row-subtotal-after-discount form-control form-control-sm text-center"
                                value="{{ $detail->subtotal_after_discount }}" readonly>
                        </td>

                        <td>
                            <a class="row-remove-btn text-danger" style="cursor: pointer;" title="Remove item">
                                <i class="far fa-times-circle fa-lg"></i>
                            </a>
                        </td>

                        <td class="d-none">
                            <table>
                                <tbody class="row-addon-tbody">
                                    @forelse ($order_addons as $order_addon)
                                        <tr>
                                            <td>
                                                <input type="hidden" name="type[]" value="addon">
                                                <input type="hidden" name="parent_no[]" value="">

                                                <select name="product_variation_id[]"
                                                    class="form-control form-select addon-dropdown">
                                                    <option value="">Choose</option>
                                                    @foreach ($addons as $addon)
                                                        <option value="{{ $addon->id }}"
                                                            data-selling-price="{{ $addon->selling_price }}"
                                                            @if ($order_addon->product_variation_id == $addon->id) selected @endif>
                                                            {{ $addon->name . ' - ' . number_format($addon->selling_price, 2) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td>
                                                <input type="number" step="0.01"
                                                    class="form-control addon-price text-end" name="price[]"
                                                    value="{{ $order_addon->unit_price }}" readonly>
                                            </td>

                                            <td>
                                                <input type="number" step="0.01"
                                                    class="form-control addon-qty text-center" name="quantity[]"
                                                    value="{{ $order_addon->quantity }}">
                                            </td>

                                            <td>
                                                <input type="number" step="0.01"
                                                    class="form-control addon-total text-end" name="item_subtotal[]"
                                                    value="{{ $order_addon->grand_total }}" readonly>
                                            </td>

                                            <td class="text-center">
                                                <a href="#" class="remove-addon-item">
                                                    <span class="bx bx-trash text-danger"></span>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <!-- Optional: Display message or empty row if no addons -->
                                    @endforelse
                                </tbody>
                            </table>
                        </td>

                    </tr>
                @endforeach


            @endif
        </tbody>

    </table>
</div>
