@extends('template.tmp')
@section('title', 'Pos Settings')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                    <div class="card">
                        <div class="card-header"><h3>Pos {{ __('file.Tax') }} Settings</h3></div>
                        <div class="card-body col-md-6">
                             <form id="pos-setting-store" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">{{ __('file.Tax') }} Type</label>
                                    <select name="tax_type" class="for-control form-select">
                                        <option value="inclusive"
                                            @selected($posSetting ? $posSetting->tax_type == 'inclusive' : true)>  inclusive</option>
                                        <option value="exclusive"
                                            @selected($posSetting ? $posSetting->tax_type == 'exclusive' : true)>  exclusive</option>


                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('file.Tax') }} Value</label>
                                    <input type="number" name="tax_value" class="form-control" min="0" value="{{ $posSetting ? $posSetting->tax_value : 0 }}" placeholder="Enter tax value">
                                </div>
                                

                                <div class="modal-footer-btn">
                                    <button type="button" href="{{ route('admin-dashboard') }}" class="btn btn-cancel me-2 btn-dark">Cancel</button>
                                    <button type="submit" id="submit-pos-setting-store"  class="btn btn-submit btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
             
            </div>
         </div>
    </div>


    <script>
        $('#pos-setting-store').on('submit', function(e) {
            e.preventDefault();

            // Show confirmation dialog
            if (!confirm('Are you sure you want to update the POS settings?')) {
                return; // Cancel the update if the user clicks "Cancel"
            }

            var submit_btn = $('#submit-pos-setting-store');
            let createformData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{ route('pos-setting.store') }}",
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
