@extends('template.tmp')
@section('title', 'Tax Filing')


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
           

            <div class="card">
                <div class="card-body">
                   
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-1 row">
                                <div class="col-sm-4">
                                    <label class="col-form-label">Filed On</label>
                                </div>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="date" class="form-control" value="{{ $taxFiling->filed_on }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-1 row">
                                <div class="col-sm-4">
                                    <label class="col-form-label">Start Date</label>
                                </div>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="date" class="form-control" value="{{ $taxFiling->start_month_year }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-1 row">
                                <div class="col-sm-4">
                                    <label class="col-form-label">End Date</label>
                                </div>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="date" class="form-control" value="{{ $taxFiling->end_month_year	}}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>  
                       
                    </div>    
                   
                   
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-1 row">
                                <div class="col-sm-4">
                                    <label class="col-form-label">Sales Tax (a)</label>
                                </div>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="number" step="0.01" class="form-control" value="{{ $taxFiling->sales_tax_amount }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-1 row">
                                <div class="col-sm-4">
                                    <label class="col-form-label">Purchases Tax (b)</label>
                                </div>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="number" step="0.01" class="form-control" value="{{ $taxFiling->purchases_tax_amount }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-1 row">
                                <div class="col-sm-4">
                                    <label class="col-form-label">Expenses Tax (c)</label>
                                </div>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="number" step="0.01" class="form-control" value="{{ $taxFiling->expenses_tax_amount }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>   
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-1 row">
                                <div class="col-sm-4">
                                    <label class="col-form-label">Output Tax (a)</label>
                                </div>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="number" step="0.01" class="form-control" value="{{ $taxFiling->output_tax }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-1 row">
                                <div class="col-sm-4">
                                    <label class="col-form-label">input Tax (b+c)</label>
                                </div>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="number" step="0.01" class="form-control" value="{{ $taxFiling->input_tax }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                        <div class="col-md-4">
                            <div class="mb-1 row">
                                <div class="col-sm-4">
                                    <label class="col-form-label">Total Payable [a-(b+c)]</label>
                                </div>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="number" step="0.01" class="form-control" value="{{ $taxFiling->total_payable }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                    
                </div>
            </div>



            @if($details->isNotEmpty())
               <div class="card">
                    <table class="table">
                        <thead>
                            <tr class="bg-light borde-1 border-light " style="height: 40px;">
                                <th>Account</th>
                                <th>Narration</th>
                                
                                <th>DEBIT</th>
                                <th>CREDIT</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($details as $detail)
                                <tr>
                                    <td>{{ $detail->chartOfAccount->name }}</td>
                                    <td>{{ $detail->narration }}</td>
                                    
                                    <td align="right">{{ ($detail->debit) ? number_format($detail->debit,2) : ''  }}</td>
                                    <td align="right">{{ ($detail->credit) ? number_format($detail->credit,2) : ''  }}</td>
                                </tr>   
                            @endforeach
                        </tbody>
                    
                    </table>  
               </div>
            @else
                <div class="text-warning text-center">
                    TAX UNPAID...! 

                </div>
            @endif

        </div>
        
        <!-- card end -->
    </div>
   
</div>

<!-- END: Content-->
<script>
     function appendNewRow(){
        let tableBody = $('#table tbody');

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
                <input type="text" name="narration[]" class="form-control"
                    autocomplete="off">
            </td>


            
            <td>
                <input type="text" name="reference_no[]" class=" form-control"
                    autocomplete="off">
            </td>
            <td>
                <input type="number" name="debit[]"  step="0.001" class="item-amount-debit form-control"
                    autocomplete="off">
            </td>
            <td>
                <input type="number" name="credit[]" step="0.001" class="item-amount-credit form-control"
                    autocomplete="off">
            </td>

        </tr>
        `;
    
        tableBody.append(row);
        $('.select2', '#table').select2();

    }
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



<script>
     $('#tax-filing-detail-store').on('submit', function(e) {
        
                e.preventDefault();
                var submit_btn = $('#submit-tax-filing-detail-store');
                let createformData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('tax-filing-detail.store') }}",
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
                        
                        submit_btn.prop('disabled', false).html('Create Tax Filing Voucher');  

                        if(response.success == true){
                            $('#add-tax-filing-detail').modal('hide'); 
                           // Redirect after success notification
                            setTimeout(function() {
                                window.location.href = '{{ route("tax-filing.index") }}';
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
                        submit_btn.prop('disabled', false).html('Create Tax Filing Voucher');
                    
                        notyf.error({
                            message: e.responseJSON.message,
                            duration: 5000
                        });
                    }
                });
            });
</script>


@endsection