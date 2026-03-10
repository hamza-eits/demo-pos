@extends('template.tmp')
@section('title', 'Company Information')

@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">


    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
               
                <form id="company-store" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-header"><h3>Company Information</h3></div>
                            <div class="card-body col-12">
                                <div class="row">
                                    <div class="col-4 mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" name="name" class="form-control" value="{{ $company->name }}" placeholder="Enter Name">
                                    </div>

                                    <div class="col-4 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" value="{{ $company->email }}" placeholder="Enter Email">
                                    </div>

                                    <div class="col-4 mb-3">
                                        <label class="form-label">Address</label>
                                        <input type="text" name="address" class="form-control" value="{{ $company->address }}" placeholder="Enter Address">
                                    </div>

                                    <div class="col-4 mb-3">
                                        <label class="form-label">Contact Number</label>
                                        <input type="text" name="contact_no" class="form-control" value="{{ $company->contact_no }}" placeholder="Enter Contact Number">
                                    </div>

                                    <div class="col-4 mb-3">
                                        <label class="form-label">WhatsApp Number</label>
                                        <input type="text" name="whatsapp_no" class="form-control" value="{{ $company->whatsapp_no }}" placeholder="Enter WhatsApp Number">
                                    </div>

                                    <div class="col-4 mb-3">
                                        <label class="form-label">TRN Number</label>
                                        <input type="text" name="trn_no" class="form-control" value="{{ $company->trn_no }}" placeholder="Enter TRN Number">
                                    </div>

                                    <div class="col-4 mb-3">
                                        <label class="form-label">Website</label>
                                        <input type="text" name="website" class="form-control" value="{{ $company->website }}" placeholder="Enter Website URL">
                                    </div>

                                    <div class="col-4 mb-3">
                                        <label class="form-label">Logo</label>
                                        <input type="file" name="logo" class="form-control">
                                        
                                    </div>
                                    <div class="col-4 text-center ">
                                        @if($company->logo)
                                            <a tag href="{{ asset('company/' . $company->logo) }}" data-lightbox="company-logo" data-title="Company Logo">
                                                <img src="{{ asset('company/' . $company->logo) }}" alt="Logo"
                                                    style="max-height: 100px; border:1px solid black; border-radius: 5px; cursor: zoom-in;">
                                            </a>
                                        @endif
                                    </div>
                                
                                </div>    
                                

                                

                                
                            </div> 
                        </div>
                    </div>
                    <div class="modal-footer-btn text-end">
                        <button type="button" href="{{ route('admin-dashboard') }}" class="btn btn-cancel me-2 btn-dark">Cancel</button>
                        <button type="submit" id="submit-company-store"  class="btn btn-submit btn-primary">Update</button>
                    </div>
                </form>
                    
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

    <script>
        $('#company-store').on('submit', function(e) {
            e.preventDefault();

            // Show confirmation dialog
            if (!confirm('Are you sure you want to update the Company Information?')) {
                return; // Cancel the update if the user clicks "Cancel"
            }

            var submit_btn = $('#submit-company-store');
            let createformData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{ route('company-info.store') }}",
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
                    submit_btn.prop('disabled', false).html('Update');

                    if (response.success == true) {
                        location.reload();

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
                    submit_btn.prop('disabled', false).html('Update');

                    notyf.error({
                        message: e.responseJSON.message,
                        duration: 5000
                    });
                }
            });
        });
    </script>

    

@endsection
