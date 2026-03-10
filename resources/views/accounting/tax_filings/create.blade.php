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

            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Tax Filing  </h4>
                    <div class="page-title-right ">
                    </div>



                </div>
            </div>
            {{-- <form method="post" action="{{ route('tax-filing.store') }}"  enctype="multipart/form-data"> --}}
            <form method="post" action="{{ route('tax-filing.store') }}" id="tax-filing-store" enctype="multipart/form-data">

                @csrf

                <div class="card shadow-sm">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-1 row">
                                    <div class="col-sm-3">
                                        <label class="col-form-label">Start Date</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <input type="date" name="startDate" class="form-control" value="{{ date('Y-m-01') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>    
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-1 row">
                                    <div class="col-sm-3">
                                        <label class="col-form-label">End Date</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <input type="date" name="endDate" class="form-control" value="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>   
                           
                    </div>

                       
                </div>

                  
                <div class="card-footer bg-light">
                    <div class="mt-2"><button type="submit" id="submit-tax-filing-store"
                            class="btn btn-primary w-lg float-right">Save</button>
                        <a href="" class="btn btn-secondary w-lg float-right">Cancel</a>   

                    </div>
                </div>

                   

            </form>







        </div>
        <!-- card end -->
    </div>
</div>

<!-- END: Content-->

<script>
     $('#tax-filing-store').on('submit', function(e) {
                e.preventDefault();
                var submit_btn = $('#submit-tax-filing-store');
                let createformData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('tax-filing.store') }}",
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
                        
                        submit_btn.prop('disabled', false).html('Create Tax Filing');  

                        if(response.success == true){
                            $('#add-tax-filing').modal('hide'); 
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
                        submit_btn.prop('disabled', false).html('Create Tax Filing');
                    
                        notyf.error({
                            message: e.responseJSON.message,
                            duration: 5000
                        });
                    }
                });
            });
</script>


@endsection