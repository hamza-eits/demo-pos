@extends('template.tmp')
@section('title', 'Product')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->

                <form id="product-update-form" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="card">
                            <div class="card-body">
                                @include('products.partials.information', ['product' => $product])
                            </div>

                        </div>
                        <h4 class="card-title mb-4">Mode</h4>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    @include('products.partials.mode', ['product' => $product])
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    @include('products.partials.variation_table', ['product' => $product])
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer-btn">
                        <a href="{{ route('product.index') }}" type="button" class="btn btn-cancel me-2 btn-dark">Cancel</a>
                        <button type="submit" id="submit-product-update-btn"
                            class="btn btn-submit btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @include('products.js')

    <script>
        $(document).ready(function () {

               

            let variationType = @json($product->variation_type);
            if (variationType == 'single') {
                showOrHideElement('#variation-select', false, '');
                showOrHideElement('#add-custom-variant', false);
                showHideTableColumn(false);

            } else {
                showOrHideElement('#variation-select', true, '');
                showOrHideElement('#add-custom-variant', true);
                showHideTableColumn(true);
            }
        });


        $(document).ready(function() {

            $('#product-update-form').on('submit', function(e) {
                e.preventDefault();
                var submit_btn = $('#submit-product-update-btn');
                let id = @json($product->id);


                let editFormData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('product.update', ':id') }}".replace(':id',
                        id), // Using route name
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    cache: false,
                    data: editFormData,
                    enctype: "multipart/form-data",
                    beforeSend: function() {
                        submit_btn.prop('disabled', true);
                        submit_btn.html('Processing');
                    },
                    success: function(response) {

                        submit_btn.prop('disabled', false).html('Update Product');

                        if (response.success == true) {

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
                        submit_btn.prop('disabled', false).html('Update Product');

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
