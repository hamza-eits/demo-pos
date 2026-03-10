@extends('template.tmp')
@section('title', 'Product')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->

                <form id="product-create-form" method="POST">
                    @csrf
                    <div class="row">
                        <div class="card">
                            <div class="card-body">
                                @include('products.partials.information', ['product' => null])
                            </div>

                        </div>
                        <h4 class="card-title mb-4">Mode {{ env('CURRENCY') }}</h4>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    @include('products.partials.mode', ['product' => null])
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    @include('products.partials.variation_table', ['product' => null])
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer-btn text-end">
                        <a href="{{ route('product.index') }}" type="button" class="btn btn-cancel me-2 btn-dark">Cancel</a>
                        <button type="submit" id="submit-product-btn" class="btn btn-submit btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @include('products.js')
    <script>
        $(document).ready(function(e) {
            $('#single-product-radio').prop('checked', true);
            $('#single-product-radio').trigger('change');

        });
    </script>

    <script>
        $(document).ready(function(e) {

            $('#product-create-form').on('submit', function(e) {
                e.preventDefault();
                var submit_btn = $('#submit-product-btn');
                let createformData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('product.store') }}",
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
                            $('#product-create-form')[0].reset(); // Reset all form data

                            notyf.success({
                                message: response.message,
                                duration: 3000
                            });
                            window.location.href = response.redirect_route;


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
        });
    </script>


@endsection
