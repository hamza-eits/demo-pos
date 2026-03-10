@extends('template.tmp')
@section('title', 'Voucher')


@section('content')



<style type="text/css">
    .form-control {
        border-radius: 0 !important;


    }

    .select2 {
        border-radius: 0 !important;
        width: 100% !important;

    }


    .swal2-popup {
        font-size: 0.8rem;
        font-weight: inherit;
        color: #5E5873;
    }

    .select2-container--default .select2-search--dropdown {
        padding: 1px !important;
        background-color: #556ee6 !important;
    }
</style>



<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Voucher</h4>
                    <div class="page-title-right ">
                    </div>



                </div>
            </div>
            <form method="post" id="voucher-store" enctype="multipart/form-data">

                @csrf

                <div class="card shadow-sm">
                    <div class="card-body">

                        <div class="row">

                            <!-- <img src="{{ asset('assets/images/logo/ft.png') }}" alt=""> -->

                            <div class="col-6">

                                <textarea name="narration_main" id="narration_main" cols="30" rows="7"
                                    class="form-control" placeholder="Narration"></textarea>
                                <div class="clearfix mt-1"></div>
                            </div>

                            <div class="col-6">
                                <div class="row">
                                    <div class="col-12 d-none">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label">Invoice#</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" name="invoice_no" id="invoice-no" class="form-control">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label">Voucher Type</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <select name="voucher_type_code"  id="voucher-type" class="form-control select2">
                                                    <option value="">Choose..</option>
                                                    @foreach ($voucherTypes as $type)
                                                    <option value="{{ $type->code }}">
                                                        {{ $type->code . '-' . $type->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label">Account</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <select class="form-control select2" name="chart_of_account_id_main" id="chart_of_account_id_main">

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label">Date</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label">Attach File</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <input type="file" name="attachment"  class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                        <hr class="invoice-spacing">


                        <div class='row'>
                            <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
                                <table id="table-voucher" style="border-collapse: collapse;" cellspacing="0" cellpadding="0">
                                    <thead>
                                        <tr class="bg-light borde-1 border-light " style="height: 40px;">
                                            <th width="2%" class="p-1"><input id="select-all-checkboxes" type="checkbox"
                                                    style="margin-left: 13px;" /></th>
                                            <th width="10%">Account</th>
                                            <th width="12%">Customer</th>
                                            <th width="12%">Supplier</th>
                                            <th width="10%">Narration</th>


                                            <th width="5%">Reference No</th>
                                            <th width="5%">Amount</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="bg-light border-1 border-light">
                                            <td class=" bg-light border-1 border-light"><input class="item-checkbox"
                                                    type="checkbox" style="margin-left: 15px;" />
                                            </td>
                                            <td>
                                                <select name="chart_of_account_id[]" class="form-control select2 account-dropdown">
                                                    <option value="">Select Account</option>
                                                    @foreach ($chart_of_accounts as $account )
                                                        <option value="{{ $account->id }}" data-account-code="{{ $account->id }}" >{{ $account->id.'-'.$account->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="customer_id[]" class="form-control select2 customer-dropdown">
                                                    <option value="">Select Account</option>
                                                    @foreach ($customers as $customer )
                                                        <option value="{{ $customer->id }}">{{ $customer->business_name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="supplier_id[]" class="form-control select2 supplier-dropdown">
                                                    <option value="">Select Account</option>
                                                    @foreach ($suppliers as $supplier )
                                                        <option value="{{ $supplier->id }}">{{ $supplier->business_name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>

                                           
                                            <td>
                                                <input type="text" name="narration[]" class="form-control"
                                                    autocomplete="off">
                                            </td>


                                           
                                            <td>
                                                <input type="text" name="reference_no[]" class=" form-control"
                                                    autocomplete="off">
                                            </td>
                                            <td>
                                                <input type="number" name="amount[]" step="0.001" class="item-amount form-control"
                                                    autocomplete="off">
                                            </td>

                                        </tr>

                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-light border-1 border-light " style="height: 40px;">
                                            <th width="2%"> </th>
                                            <th width="10%"> </th>
                                            <th width="10%"> </th>
                                            <th width="12%"> </th>
                                            <th width="10%"> </th>


                                            <th width="5%"> </th>
                                            <th width="5%">
                                                <input type="number" readonly class="form-control" name="total_amount" id="total-amount"> 
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="row mt-1 mb-2" style="margin-left: 29px;">
                            <div class='col-xs-5 col-sm-3 col-md-3 col-lg-3  '>
                                <button id="bulk-delete" class="btn btn-danger" type="button"><i
                                        class="bx bx-trash align-middle font-medium-3 me-25"></i>Delete</button>
                                <button id="btn-add-more" class="btn btn-success addmore" type="button"><i
                                        class="bx bx-list-plus align-middle font-medium-3 me-25"></i> Add
                                    More</button>
                            </div>
                            <div class='col-xs-5 col-sm-3 col-md-3 col-lg-3  '>
                                <div id="result"></div>
                            </div>
                            <br>

                        </div>
                    </div>

                  
                    <div class="card-footer bg-light">
                        <div>
                            <div class="mt-2"><button type="submit" id="submit-voucher-store"
                                    class="btn btn-primary w-lg float-right">Save</button>

                                <a href="{{ route('voucher.index') }}" class="btn btn-secondary w-lg float-right">Cancel</a>

                              

                                <div class='d-none' id="resultdiv">
                                    <div class="well text-center">
                                        <h2>Last Voucher No: <span id="InvoiceMasterID" class="text-danger">
                                            </span> </h2>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            </form>







        </div>
        <!-- card end -->
    </div>
</div>

<!-- END: Content-->




<script>

   


    $(document).on('select2:select', '.customer-dropdown', function(e){
        // let code = $(this).val();
        let row = $(this).closest('tr');

        let code = 112100;
        row.find('.account-dropdown').val(code).trigger('change');
        
    });
    $(document).on('select2:select', '.supplier-dropdown', function(e){
        // let code = $(this).val();
        let row = $(this).closest('tr');

        let code = 211100;
        row.find('.account-dropdown').val(code).trigger('change');
        
    });

   $('#btn-add-more').on('click', function(e){
        e.preventDefault();

        appendNewRow();
       
    });



    $(document).on('keyup', '.item-amount', function(e){
        e.preventDefault();

        let total_amount = 0;
        $('.item-amount').each(function(){
            total_amount += parseFloat($(this).val()) || 0; 
        });

        $('#total-amount').val(total_amount.toFixed(2));
    });


    $(document).on('select2:select', '#voucher-type', function(e){
        let voucherTypeCode = $(this).val();
        getAccountsByCategory(voucherTypeCode);
        
    });

        function getAccountsByCategory(voucherTypeCode) {
            

            // Make the AJAX GET request
            $.get("{{ route('chart-of-account.getByCategory', ':code') }}".replace(':code', voucherTypeCode))
                .done(function(response) {
                    const $select = $('#chart_of_account_id_main');
                    $select.empty(); // Clear any existing options

                    $select.append(new Option('Choose...', ''));
                    response.forEach(account => {
                        $select.append(new Option(account.name, account.id));
                    });

                })
                .fail(function(xhr) {
                    // Display error message if available
                    const errorMessage = xhr.responseJSON ? xhr.responseJSON.message : xhr.responseText;
                    alert('Error fetching account options: ' + errorMessage);
                    
                    // Hide the progress bar in case of failure
                    $('#progressModal').modal('hide');
                });
        }


        function appendNewRow(){
        let tableBody = $('#table-voucher tbody');

        let row = `
              <tr class="bg-light border-1 border-light">
                <td class=" bg-light border-1 border-light"><input class="item-checkbox"
                        type="checkbox" style="margin-left: 15px;" />
                </td>
                <td>
                    <select name="chart_of_account_id[]" class="form-control select2 account-dropdown">
                        <option value="">Select Account</option>
                        @foreach ($chart_of_accounts as $account )
                            <option value="{{ $account->id }}" data-account-code="{{ $account->id }}" >{{ $account->id.'-'.$account->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select name="customer_id[]" class="form-control select2 customer-dropdown">
                        <option value="">Select Account</option>
                        @foreach ($customers as $customer )
                            <option value="{{ $customer->id }}">{{ $customer->business_name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select name="supplier_id[]" class="form-control select2 supplier-dropdown">
                        <option value="">Select Account</option>
                        @foreach ($suppliers as $supplier )
                            <option value="{{ $supplier->id }}">{{ $supplier->business_name }}</option>
                        @endforeach
                    </select>
                </td>

                
                <td>
                    <input type="text" name="narration[]" class="form-control"
                        autocomplete="off">
                </td>


                
                <td>
                    <input type="text" name="reference_no[]" class=" form-control"
                        autocomplete="off">
                </td>
                <td>
                    <input type="number" name="amount[]" step="0.001" class="item-amount form-control"
                        autocomplete="off">
                </td>

            </tr>
        `;
      
        tableBody.append(row);
        $('.select2', '#table-voucher').select2();

    }

  

</script>
<script>
     $('#voucher-store').on('submit', function(e) {
                e.preventDefault();
                var submit_btn = $('#submit-voucher-store');
                let createformData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('voucher.store') }}",
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
                        
                        submit_btn.prop('disabled', false).html('Create voucher');  

                        if(response.success == true){
                            $('#add-voucher').modal('hide'); 
                           // Redirect after success notification
                            setTimeout(function() {
                                window.location.href = '{{ route("voucher.create") }}';
                            }, 200); // Redirect after 3 seconds (same as notification duration)
                        
                            notyf.success({
                                message: response.message, 
                                duration: 3000
                            });
                        }else{
                            notyf.error({
                                message: response.message,
                                duration: 5000
                            });
                        }   
                    },
                    error: function(e) {
                        submit_btn.prop('disabled', false).html('Create voucher');
                    
                        notyf.error({
                            message: e.responseJSON.message,
                            duration: 5000
                        });
                    }
                });
            });
</script>

<script>
      $('#select-all-checkboxes').on('change', function() {
        if ($(this).prop('checked')) {
            $('.item-checkbox').prop('checked', true);  // Check all checkboxes
        } else {
            $('.item-checkbox').prop('checked', false);  // Uncheck all checkboxes
        }
    });


    $('#bulk-delete').on('click', function(e){
        e.preventDefault();

        $('.item-checkbox').each(function(){
            if($(this).prop('checked'))
            {
                $(this).closest('tr').remove();
            }
        });
        $('#select-all-checkboxes').prop('checked', false);
    })
</script>

@endsection