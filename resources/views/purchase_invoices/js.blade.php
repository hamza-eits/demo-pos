<script>
    $('#add-row').on('click', function(e) {
        e.preventDefault();
        let row = `
        <tr>
            <td>
                <select name="product_variation_id[]" class="form-control row-item-select select2" style="width: 100%">
                    <option value="">Choose...</option>
                    @foreach ($productVariations as $variation)
                        <option 
                            value="{{ $variation->id }}"
                            data-variation-unit-id="{{ $variation->product->unit_id }}"
                            data-variation-type="{{ $variation->product_type }}"
                            data-variation-barcode ="{{ $variation->barcode }}"
                            data-selling-price="{{ $variation->selling_price }}"

                            >
                            {{ $variation->name }}
                        </option>
                    @endforeach
                </select> 
                <input type="hidden" name="variation_barcode[]" class="row-variation-barcode">
                <input type="hidden" name="product_type[]" class="row-product-type">
                <input type="hidden" name="selling_price[]" class="row-selling-price">

            </td>
            <td  class="d-none">
                <select name="unit_id[]" class="form-control row-unit-select select2" style="width: 100%">
                    @foreach ($units as $unit)
                        <option 
                            value="{{ $unit->id }}"
                            data-base-unit-value="{{ $unit->base_unit_value }}" 
                        >
                            {{ $unit->base_unit }}
                        </option>
                    @endforeach
                </select>   
            </td>
            <td class="d-none">
                <input type="number" name="base_unit_value[]" class="row-base-unit-value form-control row-calculation text-end" value="1" readonly>
            </td>
            
          
            <td>
                <input type="number" name="quantity[]" class="row-quantity row-calculation form-control text-end" step="0.01">
            </td>
              <td>
                <input type="number" name="base_unit_qty[]" class="row-base-unit-qty form-control text-end" value="1" readonly>
            </td>

            <td>
                <input type="number" name="unit_price[]" class="form-control text-end row-unit-price row-calculation" step="0.01">
            </td>
            <td class="d-none">
                <select name="purchase_category[]" class="form-control select2" style="width: 100%">
                    @foreach ($purchaseCategories as $category)
                        <option value="{{ $category->name }}">{{ $category->name }}</option>
                    @endforeach
                </select>   
            </td>
            <td>
                <input type="number" name="subtotal[]" class="form-control text-end row-subtotal" step="0.01" readonly>
            </td>
            <td>
                <input type="hidden" name="tax_type[]" value="exclusive"> 

                <select name="tax_value[]" class="form-control form-select row-tax-select">
                    @foreach ($taxes as $tax)
                        <option data-tax-value="{{ $tax->rate }}" value="{{ $tax->rate }}">{{ $tax->rate }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" name="tax_amount[]" class="form-control text-end row-tax-amount" step="0.01" readonly>
            </td>
            <td>
                <input type="number" name="subtotal_after_tax[]" class="form-control text-end row-subtotal-after-tax row-calculation" step="0.01" readonly>
            </td>
            
            <td class="text-center">  
                <a href="#"><span style="font-size:18px" class="bx bx-trash text-danger row-trash-btn"></span></a>
            </td>
        </tr>  `;

        $('#table tbody').append(row);
        $('.select2').select2();

    });
</script>

<script>
function calculateDueDate() {
    let creditLimit = $('#supplier_id').find('option:selected').data('credit-limit');
    let date = $('#date').val();

    if (date && creditLimit) {
        let dueDate = new Date(date);
        dueDate.setDate(dueDate.getDate() + parseInt(creditLimit));

        // Format the dueDate to YYYY-MM-DD
        let yyyy = dueDate.getFullYear();
        let mm = String(dueDate.getMonth() + 1).padStart(2, '0');
        let dd = String(dueDate.getDate()).padStart(2, '0');
        let formattedDueDate = `${yyyy}-${mm}-${dd}`;

        $('#due_date').val(formattedDueDate);
        $('#creditLimitNotes').text(`Credit limit of ${creditLimit} days.`);
    } else {
        $('#due_date').val('');
        $('#creditLimitNotes').text('');
    }
}

// Call function when either supplier or date changes
$('#supplier_id, #date').on('change', calculateDueDate);

</script>


<script>
    $(document).on('keyup', '.row-calculation', function(e) {

        e.preventDefault();
        let row = $(this).closest('tr');
        calculation(row);

    });

    $(document).on('click', '.row-trash-btn', function(e) {
        e.preventDefault();

        // Show a confirmation dialog
        if (confirm("Are you sure you want to remove this item?")) {
            $(this).closest('tr').remove();
            summaryCalculation(); // Recalculate the summary
        }
    });

    $(document).on('change', '.row-tax-select, .row-unit-select', function(e) {
        e.preventDefault();
        let row = $(this).closest('tr');
        calculation(row);
        summaryCalculation();

    });

    $(document).on('change', '.row-item-select', function(e) {
        e.preventDefault();
        let row = $(this).closest('tr');
        let variation_unit_id = $(this).find('option:selected').data('variation-unit-id');
        let product_type = $(this).find('option:selected').data('variation-type');
        let variation_barcode = $(this).find('option:selected').data('variation-barcode');
        let selling_price = $(this).find('option:selected').data('selling-price');

        if(variation_unit_id > 0){
            row.find('.row-unit-select').val(variation_unit_id).trigger('change');
        }

        row.find('.row-product-type').val(product_type);
        row.find('.row-variation-barcode').val(variation_barcode);
        row.find('.row-selling-price').val(selling_price);
        calculation(row);
    });



    function calculation(row) {
        // Extract values from form fields with validation
        const quantity = parseFloat(row.find('.row-quantity').val()) || 0;
        const unitPrice = parseFloat(row.find('.row-unit-price').val()) || 0;
        const taxPercent = parseFloat(row.find('.row-tax-select option:selected').data('tax-value')) || 0;
        const baseUnitValue = parseFloat(row.find('.row-base-unit-value').val()) || 1;
        // Calculate base unit quantity
        const baseUnitQuantity = baseUnitValue * quantity;

        // Calculate subtotal before tax
        const subtotal = quantity * unitPrice;

        // Calculate tax amount
        const taxAmount = (subtotal * taxPercent) / 100;

        // Calculate final total including tax
        const totalWithTax = subtotal + taxAmount;

        // Update form fields with calculated values
        row.find('.row-base-unit-value').val(baseUnitValue);
        row.find('.row-base-unit-qty').val(baseUnitQuantity.toFixed(2));
        row.find('.row-subtotal').val(subtotal.toFixed(2));
        row.find('.row-tax-amount').val(taxAmount.toFixed(2));
        row.find('.row-subtotal-after-tax').val(totalWithTax.toFixed(2));

        // Recalculate summary totals
        summaryCalculation();
    }

    function summaryCalculation() {
        recalculateColumnSummary('.row-quantity', '#total-quantity');
        recalculateColumnSummary('.row-subtotal', '#purchase-subtotal');
        recalculateColumnSummary('.row-tax-amount', '#purchase-tax-amount');
        recalculateColumnSummary('.row-subtotal-after-tax', '#purchase-grand-total');

    }


    function recalculateColumnSummary(className, id) {
        let value = 0;
        $(className).each(function() {
            let currentValue = parseFloat($(this).val()) || 0; // Ensure it's treated as a number
            value += currentValue;
        });

        $(id).val(value.toFixed(2));
    }
</script>
