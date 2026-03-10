<div width="100%">
    <table id="order-summary-table" class="table">
        <thead>
            <tr>
                <td width="10%"></td>
                <td width="10%"></td>
                <td width="10%"></td>
                <td width="10%"></td>
                <td width="10%"></td>
                <td width="10%"></td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-start">{{ __('file.Quantity') }}</td>
                <td><input type="number" step="0.01" value="0" name="total_quantity" id="total-quantity" readonly></td>

                <td class="text-end">Discount</td>
                <td>
                    <div class="form-group">
                        <span data-bs-toggle="modal" data-bs-target="#invoice-discount-modal" class="fa fa-edit"></span>
                        <input type="number" id="invoice-discount-amount" name="invoice_discount_amount"
                            value="{{ $order ? $order->discount_amount : 0 }}" step="0.01" readonly>

                    </div>

                </td>

                <td class="text-end">Shipping</td>
                <td>
                    <div class="form-group">
                        <span data-bs-toggle="modal" data-bs-target="#shipping-modal" class="fa fa-edit"></span>
                        <input type="number" class="show-shipping-fee" step="0.01" readonly>

                    </div>

                </td>
            </tr>
            <tr>
                <td class="text-start">Subtotal</td>
                <td>
                    <input type="number" step="0.01" value="0" name="subtotal_items" id="subtotal" readonly>
                    <input type="hidden" step="0.01" value="0" name="total_cost_amount" id="total-cost-amount"
                        readonly>

                </td>

                <td class="text-end"><span>{{ $formatTaxLabel }}</span></td>
                <td>
                    <input type="hidden" step="0.01" value="{{ $taxValue }}" name="tax_value" id="tax-value"
                        readonly>
                    <input type="hidden" step="0.01" value="{{ $taxType }}" name="tax_type" id="tax-type"
                        readonly>
                    <input type="number" step="0.01" value="0" name="tax_amount" id="tax-amount" readonly>
                </td>

                <td class="text-end">Addon</td>
                <td><input type="number" name="subtotal_addons" id="addon-total-amount" step="0.01" value="0"
                        readonly></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-end">
                    <h5 class="text-primary">Grand Total</h5>
                </td>
                <td colspan="3">
                    <h5><input class="text-start text-primary" type="number" name="grand_total" step="0.01"
                            id="grand-total" value="0" readonly></h5>
                </td>
            </tr>
        </tfoot>
    </table>
</div>
