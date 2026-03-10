@extends('template.tmp')
@section('title', 'POS')
@section('content')


    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="">
                            <div class="">
                                <div id="filterRow">
                                   <div class="row">
                                        <div class="col-md-3" style="display: flex; align-items: center;">
                                            <h3 class="mb-0 font-size-18" style="margin-left: 10px;">All Orders</h3>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">Start Date</label>
                                                <div class="input-group">
                                                    <div class="input-group-text"><span class="bx bx-calendar" ></span> </div>
                                                    <input type="date" name="start_date" id="start_date" class="form-control" value="">
                                                </div>
                                            
                                            </div> 
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">End Date</label>
                                                <div class="input-group">
                                                    <div class="input-group-text"><span class="bx bx-calendar" ></span> </div>
                                                    <input type="date" name="end_date" id="end_date" class="form-control" value="">
                                                </div>
                                            
                                            </div> 
                                        </div>

                                      
                                        
                                        <div class="col-md-3 text-center">
                                            <button type="button" class="btn btn-danger  mt-4" id="filter-btn">
                                                <i class="mdi mdi-filter"></i> Filter
                                            </button>
                                            <button type="button" class="btn btn-primary  mt-4" id="reset-filter-btn">
                                                <i class="fas fa-sync-alt"></i> Reset
                                            </button>
                                        </div>  
                                    </div>
                                   </div>
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
                                            <th>Invoice Date</th>
                                            <th>Invoice No</th>
                                            <th>Customer Name</th>
                                            <th>Seller Name</th>
                                            <th>payment Status</th>
                                            <th>Payment Mode</th>
                                            <th>Card Ref. No</th>
                                            <th>Grand Total</th>
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
        var table= null;
        $(document).ready(function(e) {
            table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax:{
                    url : "{{ route('point-of-sale.index') }}",
                    data: function (d) {
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                       
                    }
                },
                columns: [
                    { data: 'id' },
                    { data: 'date' },
                    { data: 'invoice_no' },
                    { data: 'partyName' },
                    { data: 'billerName' },
                    { data: 'status' },
                    { data: 'paymentMode' },
                    { data: 'reference_no' },
                    { data: 'grand_total' },

                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                order: [
                    [0, 'desc']
                ],
            });


            $('#filter-btn').on('click', function(){
                table.draw();
            });
            $('#reset-filter-btn').on('click', function(){
                $('#start_date').val('');
                $('#end_date').val('');
                table.draw();
            });
            $('#start_date').on('change', function() {
                let startDate = $(this).val();
                
                // Set the end date to the start date if it's empty or less than the start date
                let endDate = $('#end_date').val();
                if (!endDate || endDate < startDate) {
                    $('#end_date').val(startDate);
                }
                
                // Set the min attribute of the end date to the start date
                $('#end_date').attr('min', startDate);
            });
        });
        /*
        function deleteRecord(id) {
            if (confirm("Are you sure you want to delete?")) {
                $.ajax({
                    type: 'DELETE',
                    url: "{{ route('point-of-sale.destroy', ':id') }}".replace(':id', id),
                    data: {
                        _token: "{{ csrf_token() }}" // CSRF token for Laravel
                    },
                    success: function(response) {
                        table.ajax.reload();
                        notyf.success({
                            message: response.message,
                            duration: 3000
                        });
                    },
                    error: function(e) {
                        notyf.error({
                            message: e.responseJSON?.message || 'An error occurred.',
                            duration: 5000
                        });
                    }
                });
            }
        } 
        */

        function deleteRecord(id) {

            const userInput = prompt(
                "⚠️ WARNING: This action will permanently delete the record and cannot be undone.\n\n" +
                "To confirm, please type DELETE:"
            );            
            
            if (userInput === "DELETE") {
                $.ajax({
                    type: 'DELETE',
                    url: "{{ route('point-of-sale.destroy', ':id') }}".replace(':id', id),
                    data: {
                        _token: "{{ csrf_token() }}" // CSRF token for Laravel
                    },
                    success: function(response) {
                        table.ajax.reload();
                        notyf.success({
                            message: response.message,
                            duration: 3000
                        });
                    },
                    error: function(e) {
                        notyf.error({
                            message: e.responseJSON?.message || 'An error occurred.',
                            duration: 5000
                        });
                    }
                });
            } else if (userInput !== null) {
                notyf.warning({
                    message: 'Deletion cancelled. You must type DELETE to proceed.',
                    duration: 4000
                });
            }
}

    </script>
   


@endsection
