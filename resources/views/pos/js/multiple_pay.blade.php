{{-- multiple payments table row function --}}
<script>
    $(document).on('keyup', '.multiple-payments-row-amount', function() {

        multiplePayCalculation();

    });


    $(document).on('click', '.multiple-pay-remove-row', function() {
        $(this).closest('tr').remove();
        multiplePayCalculation();
    });


    function multiplePayCalculation() {
        let paid_amount = 0;
        let total_amount = parseFloat($('#multiple-payments-total-amount').val());
        let change_return = 0;


        //Multiple Payments
        $('.multiple-payments-row-amount').each(function() {
            paid_amount += parseFloat($(this).val()) || 0;
        });
        $('#multiple-payments-paid-amount').val(paid_amount.toFixed(2));

        change_return_amount = total_amount - paid_amount;

        if (change_return_amount < 0) {
            $('#multiple-payments-change-return-amount')
                .val(change_return_amount.toFixed(2))
                .removeClass('text-success')
                .addClass('text-danger');
        } else {
            $('#multiple-payments-change-return-amount')
                .val(change_return_amount.toFixed(2))
                .removeClass('text-danger')
                .addClass('text-success');
        }
    }

    function addRowtoMutipayTable() {
        let table = $('#multiple-payments-table tbody');
        let newRow = `
            <tr >
                <td>
                    <select class="form-control form-select" name="payment_coa_id[]">
                        <option value="">Choose...</option>
                        @foreach ($chart_of_accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->name . '-' . $account->category }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" step="0.01" name="payment_amount[]" class="form-control text-end multiple-payments-row-amount">
                </td>
               <td>
                    <button type="button" class="btn btn-danger btn-sm multiple-pay-remove-row">
                        <span class="fa fa-trash"></span>
                    </button>
                </td>      
            </tr>`;
        table.append(newRow);
    }


    function validateMultipayTable()
    {
        let isValid = true;

        let totalAmount = parseFloat($('#multiple-payments-total-amount').val());
        if (isNaN(totalAmount) || totalAmount <= 0) {
            alert('Please enter a valid total amount.');
            return false;
        }

        let paidAmount = parseFloat($('#multiple-payments-paid-amount').val());
        if (isNaN(paidAmount) || paidAmount <= 0) {
            alert('Please enter a valid paid amount.');
            return false;
        }

        if (paidAmount > totalAmount) {
            alert('Paid amount cannot be greater than total amount.');
            return false;
        }
        if (paidAmount < totalAmount) {
            alert('Paid amount must be equal to total amount.');
            return false;
        }


        $('#multiple-payments-table tbody tr').each(function(index) {
            const accountVal = $(this).find('select[name="payment_coa_id[]"]').val();
            const amountVal = parseFloat($(this).find('input[name="payment_amount[]"]').val());

            if (!accountVal) {
                alert(`Row ${index + 1}: Please select an account.`);
                isValid = false;
                return false; // Breaks out of `.each()` loop
            }

            if (isNaN(amountVal) || amountVal <= 0) {
                alert(`Row ${index + 1}: Amount must be greater than 0.`);
                isValid = false;
                return false;
            }
        });

       return isValid;
    }
</script>