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
                                    <label class="col-form-label">Sales Tax</label>
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
                                    <label class="col-form-label">Purchases Tax</label>
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
                                    <label class="col-form-label">Expenses Tax</label>
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
                                    <label class="col-form-label">input Tax</label>
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
                                    <label class="col-form-label">Output Tax</label>
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
                                    <label class="col-form-label">Total Payable</label>
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



            <form id="tax-filing-detail-store">
                @csrf
                <input type="hidden" name="tax_filing_id" value="{{ $taxFiling->id }}">
                <div class="">
                    <div class="">

                        <table id="table" style="border-collapse: collapse;" cellspacing="0" cellpadding="0">
                            <thead>
                                <tr class="bg-light borde-1 border-light " style="height: 40px;">
                                    <tr class="bg-light borde-1 border-light " style="height: 40px;">
                                       
                                        <th width="10%">Account</th>
                                    
                                        <th width="10%">Narration</th>
    
    
                                        <th width="5%">Reference No</th>
                                        <th width="5%">Debit</th>
                                        <th width="5%">Credit</th>
    
                                    </tr>
    
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-light border-1 border-light">
                                   
                                    <td>
                                        <select name="chart_of_account_id[]" class="form-control select2 account-dropdown">
                                                <option value="{{ $tax_coa->id }}">{{ $tax_coa->id.'-'.$tax_coa->name }}</option>
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

                                
                                <tr class="bg-light border-1 border-light">
                                    
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

                                
    
                            </tbody>
                        
                        </table>
                       
                    </div>
                    <div class="card-footer bg-light text-end">
                        <div>
                            <div class="mt-2"><button type="submit" id="submit-tax-filing-detail-store"
                                    class="btn btn-primary w-lg float-right">Save</button>
            
                                <a href="{{ route('tax-filing.index') }}" class="btn btn-secondary w-lg float-right">Cancel</a>
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