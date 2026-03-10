<script>
    // Declare a global variable to store the selected row for item discount
    var rowItem = null;

    // Event listener: Open the item discount modal when the discount column is clicked
    $(document).on('click', '#order-items-tbody tr td:nth-child(7)', function () {
        rowItem = $(this).closest('tr');

        openItemDiscountModal(rowItem);
    });

    // Event listener: Apply changes from the modal back to the selected row
    $('#apply-item-discount-btn').on('click', function () {
        applyItemDiscountToRow();

        $('#item-discount-modal').modal('hide');
    });

    // Event listener: Recalculate the discount amount when the discount value is updated
    $('#modal-item-discount-value').on('keyup', function () {
        calculateModalItemDiscount();
    });

    // Event listener: Recalculate the discount amount when the discount type is changed
    $('.modal-item-discount-type').on('change', function () {
        calculateModalItemDiscount();
    });

    // Function: Open the item discount modal and populate it with data from the selected row
    function openItemDiscountModal(row) {
        // Retrieve discount type and value from the selected row
        let type = row.find('.row-discount-type').val();
        let value = parseFloat(row.find('.row-discount-value').val()) || 0;

        // Set the modal fields with the retrieved values
        $('.modal-item-discount-type[value="' + type + '"]').prop('checked', true);
        $('#modal-item-discount-value').val(value);

        // Recalculate the discount amount in the modal
        calculateModalItemDiscount();

        // Show the item discount modal
        $('#item-discount-modal').modal('show');
    }

    // Function: Calculate the discount amount based on the modal inputs
    function calculateModalItemDiscount() {
        // Get the selected discount type and value from the modal
        let selectedType = $('.modal-item-discount-type:checked').val() || 'fixed';
        let value = parseFloat($('#modal-item-discount-value').val()) || 0;
        let amount = 0;

        // Retrieve the current subtotal value from the selected row
        let subtotal = parseFloat(rowItem.find('.row-subtotal').val());

        // Calculate the discount amount based on the type (fixed or percentage)
        if (value > 0) {
            if (selectedType == 'fixed') {
                amount = value;
            } else if (selectedType == 'percentage') {
                amount = (value / 100) * subtotal;
            }
        }

        // Update the discount amount field in the modal
        $('#modal-item-discount-amount').val(amount);
    }

    // Function: Apply the discount values from the modal to the selected row
    function applyItemDiscountToRow() {
        // Retrieve the discount type, value, and amount from the modal
        let selectedType = $('.modal-item-discount-type:checked').val();
        let amount = parseFloat($('#modal-item-discount-amount').val());
        let value = parseFloat($('#modal-item-discount-value').val());

        // Calculate the discount unit price based on the quantity
        let qty = parseFloat(rowItem.find('.row-quantity').val());
        let discountUnitPrice = amount / qty;

        // Update the selected row with the discount values
        rowItem.find('.row-discount-type').val(selectedType);
        rowItem.find('.row-discount-value').val(value);
        rowItem.find('.row-discount-amount').val(amount);
        rowItem.find('.row-discount-unit-price').val(discountUnitPrice);

        // Recalculate the row totals after applying the discount
        rowCalculation(rowItem);
    }
</script>
