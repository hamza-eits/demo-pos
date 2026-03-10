@extends('template.tmp')
@section('title', 'Products')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h3 class="mb-sm-0 font-size-18">All Products</h3>

                            <div class="page-title-right d-flex">
                                <div class="page-btn">
                                    <a href="{{ route('product.export-csv') }}" class="btn btn-added btn-success mx-2" ><i class="fa fa-file-csv me-2 font-size"></i> Export CSV </a>
                                </div>  
                                <div class="page-btn">
                                    <a href="{{ route('product.create') }}" class="btn btn-added btn-primary" ><i class="fa fa-plus me-2"></i> Add </a>
                                </div>  
                                
                            </div>



                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">

                            <div class="card-body">
                                <table id="table" class="table table-striped table-sm " style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Barcode</th>
                                            <th>Name</th>
                                            <th>Purchase Price</th>
                                            <th>Selling Price</th>
                                            {{-- <th>Image</th> --}}
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        var table = null;
         $(document).ready(function(e) {
            table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('product.index') }}",
                columns: [
                    { data: 'id' },
                    { data: 'barcode' },
                    { data: 'name' },
                    { data: 'purchase_price' },
                    { data: 'selling_price' },
                    // { data: 'image', orderable: false, searchable: false },
                    
                    { data: 'action', orderable: false, searchable: false },
                ],
                order: [[0, 'desc']],
                
            });
        });

        function productDelete(productId) {
            if (confirm("Are you sure you want to delete this product all its variaiton will be deleted?")) {
                // Proceed to delete (e.g., send AJAX request)
                $.ajax({
                    url: "{{ route('product.destroy', ':id') }}".replace(':id',
                    productId), // Using route name
                    method: 'DELETE', // Or 'DELETE' depending on your backend
                    data: {
                        _token: "{{ csrf_token() }}" // Add CSRF token
                    },
                    success: function(response) {
                       if (response.success == true) {
                            notyf.success({
                                message: response.message,
                                duration: 3000
                            });
                            table.ajax.reload();


                        } else {
                            notyf.error({
                                message: response.message,
                                duration: 5000
                            });
                        }
                    },
                    error: function(e) {

                        notyf.error({
                            message: e.responseJSON.message,
                            duration: 5000
                        });
                    }
                });
            } else {
                // User cancelled the deletion
                console.log("Deletion cancelled.");
            }
        }

    </script>
@endsection    