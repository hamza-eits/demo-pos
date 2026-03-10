{{-- store order, update order --}}
<script>
    function storeOrder(status, clickedButton, is_invoice_print = false) {

        let clickedButtonName = clickedButton.html();
        let mainForm = new FormData($('#pos-screen-store')[0]);
        let shippingForm = new FormData($('#shipping-form')[0]);
        let discountForm = new FormData($('#discount-form')[0]);
        let multiplePaymentsForm = new FormData($('#multiple-payments-form')[0]);

        shippingForm.forEach((value, key) => mainForm.append(key, value));
        discountForm.forEach((value, key) => mainForm.append(key, value));
        multiplePaymentsForm.forEach((value, key) => mainForm.append(key, value));

        mainForm.append('status', status);

        $.ajax({
            type: "POST",
            url: "{{ route('point-of-sale.store') }}",
            dataType: 'json',
            contentType: false,
            processData: false,
            cache: false,
            data: mainForm,
            enctype: "multipart/form-data",
            beforeSend: function() {
                clickedButton.prop('disabled', true);
                clickedButton.html('Processing');
            },
            success: function(response) {


                clickedButton.prop('disabled', false).html(clickedButtonName);

                if (response.success == true) {
                    resetOrderPage();

                    notyf.success({
                        message: response.message,
                        duration: 3000
                    });

                    // If is_invoice_print is parameter is ture show print popup
                    if (is_invoice_print) {
                        let printUrl = "{{ route('pos.printInvoice', ':id') }}".replace(':id', response
                            .id);
                        loadPrintPopup(printUrl);

                    }



                } else {
                    notyf.error({
                        message: response.message,
                        duration: 5000
                    });
                }
            },
            error: function(e) {
                clickedButton.prop('disabled', false).html(clickedButtonName);

                notyf.error({
                    message: e.responseJSON.message,
                    duration: 5000
                });
            }
        });

    }

    function updateOrder(status, clickedButton) {
        let invoice_master_id = $('#invoice_master_id').val();

        let clickedButtonName = clickedButton.html();
        let mainForm = new FormData($('#pos-screen-update')[0]);
        let shippingForm = new FormData($('#shipping-form')[0]);
        let discountForm = new FormData($('#discount-form')[0]);
        let multiplePaymentsForm = new FormData($('#multiple-payments-form')[0]);

        shippingForm.forEach((value, key) => mainForm.append(key, value));
        discountForm.forEach((value, key) => mainForm.append(key, value));
        multiplePaymentsForm.forEach((value, key) => mainForm.append(key, value));

        mainForm.append('status', status);

        $.ajax({
            type: "POST",
            url: "{{ route('point-of-sale.update', ':id') }}".replace(':id', invoice_master_id),
            dataType: 'json',
            contentType: false,
            processData: false,
            cache: false,
            data: mainForm,
            enctype: "multipart/form-data",
            beforeSend: function() {
                clickedButton.prop('disabled', true);
                clickedButton.html('Processing');
            },
            success: function(response) {


                clickedButton.prop('disabled', false).html(clickedButtonName);

                if (response.success == true) {
                    resetOrderPage();
                    notyf.success({
                        message: response.message,
                        duration: 3000
                    });
                    window.location.href = '{{ route('point-of-sale.create') }}';

                } else {
                    notyf.error({
                        message: response.message,
                        duration: 5000
                    });
                }
            },
            error: function(e) {
                clickedButton.prop('disabled', false).html(clickedButtonName);

                notyf.error({
                    message: e.responseJSON.message,
                    duration: 5000
                });
            }
        });

    }
</script>



{{-- Add New Customer  --}}
<script>
    $('#submit-customer-store').on('click', function(e) {
        e.preventDefault();
        var submit_btn = $('#submit-customer-store');
        let createformData = new FormData($('#customer-store')[0]);
        $.ajax({
            type: "POST",
            url: "{{ route('party.store') }}",
            dataType: 'json',
            contentType: false,
            processData: false,
            cache: false,
            data: createformData,
            enctype: "multipart/form-data",
            beforeSend: function() {
                submit_btn.prop('disabled', true);
                submit_btn.html('Processing');
            },
            success: function(response) {

                submit_btn.prop('disabled', false).html('Create');

                if (response.success == true) {
                    $('#add-customer-modal').modal('hide');
                    $('#customer-store')[0].reset(); // Reset all form data
                    fetchCustomerList();



                    notyf.success({
                        message: response.message,
                        duration: 3000
                    });
                } else {
                    notyf.error({
                        message: response.message,
                        duration: 5000
                    });
                }
            },
            error: function(e) {
                submit_btn.prop('disabled', false).html('Create');

                notyf.error({
                    message: e.responseJSON.message,
                    duration: 5000
                });
            }
        });
    });



    $('#add-expense-form').on('submit', function(e) {
        e.preventDefault();
        var submit_btn = $('#submit-expense-store-form-btn');
        let createformData = new FormData(this);
        $.ajax({
            type: "POST",
            url: "{{ route('pos.storeExpense') }}",
            dataType: 'json',
            contentType: false,
            processData: false,
            cache: false,
            data: createformData,
            enctype: "multipart/form-data",
            beforeSend: function() {
                submit_btn.prop('disabled', true);
                submit_btn.html('Processing');
            },
            success: function(response) {

                submit_btn.prop('disabled', false).html('Create Expense');

                if (response.success == true) {
                    $('#add-expense-form')[0].reset(); // Reset all form data
                    $('#add-expense-form').find('.select2').val('').trigger('change');
                    $('#add-expense-modal').modal('hide');

                    notyf.success({
                        message: response.message,
                        duration: 3000
                    });
                } else {
                    notyf.error({
                        message: response.message,
                        duration: 5000
                    });
                }
            },
            error: function(e) {
                submit_btn.prop('disabled', false).html('Create Expense');

                notyf.error({
                    message: e.responseJSON.message,
                    duration: 5000
                });
            }
        });
    });
</script>