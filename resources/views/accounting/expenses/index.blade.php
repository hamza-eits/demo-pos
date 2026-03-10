@extends('template.tmp')
@section('title', 'Expenses')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h3 class="mb-sm-0 font-size-18">All Expenses</h3>

                            <div class="page-title-right d-flex">

                                <div class="page-btn">
                                    <a href="{{ route('expense.create') }}" class="btn btn-added btn-primary"><i class="me-2 mdi mdi-plus"></i>Expense</a>
                                </div>  
                            </div>



                        </div>
                    </div>
                </div>
                 <div class="row">
                    <div class="col-12">
                        <div class="">
                            <div class="">
                                <div id="filterRow">
                                   <div class="row">
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label">Start Date</label>
                                                <div class="input-group">
                                                    <div class="input-group-text"><span class="bx bx-calendar" ></span> </div>
                                                    <input type="date" name="start_date" id="start_date" class="form-control" value="">
                                                </div>
                                            
                                            </div> 
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label">End Date</label>
                                                <div class="input-group">
                                                    <div class="input-group-text"><span class="bx bx-calendar" ></span> </div>
                                                    <input type="date" name="end_date" id="end_date" class="form-control" value="">
                                                </div>
                                            
                                            </div> 
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label">Spend Thru</label>

                                                <select name="chart_of_account_id" id="chart_of_account_id" class="select2 form-control" style="width: 100%">
                                                <option value="">Select</option>
                                                @foreach ($spendThruAccounts as $account)
                                                    <option value="{{ $account->id }}">{{ $account->id.'-'.$account->name }}</option>
                                                @endforeach
                                            </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                           <div class="mb-3">
                                            <label class="form-label">Spend By</label>

                                             <select name="user_id" id="user_id" class="select2 form-control" style="width: 100%">
                                                <option value="">Select</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                           </div>
                                        </div>

                                      
                                        
                                        <div class="col-md-2 text-center">
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
                                            <th >#</th>
                                            <th>Expense No</th>
                                            <th>Expense Type</th>
                                            <th>Expense Account</th>
                                            <th>Ref No</th>
                                            <th>Date</th>
                                            <th>Spend By</th>
                                            <th>Paid Thru</th>
                                            <th>Notes</th>
                                            <th>Amount</th>
                                            <th>Tax</th>
                                            <th>Total</th>
                                            <th></th>
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


    <!-- Delete Expense -->
        <div class="modal fade" id="delete-expense">
            <div class="modal-dialog custom-modal-two">
                <div class="modal-content">
                    <div class="page-wrapper-new p-0">
                        <div class="content">
                            <div class="modal-header border-0 custom-modal-header">
                                <div class="page-title">
                                    <h4>Delete Expense</h4>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    
                                </button>
                            </div>
                            
                                <div class="modal-body custom-modal-body pt-3 pb-0">
                                    <p class="text-center">Are you sure you want to delete this expense?</p>
                                </div>
                                <div class="modal-footer-btn p-3 mt-2">
                                    <button type="button" class="btn btn-cancel me-2" data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-submit shadow-sm btn-danger" id="submit-expense-destroy">Delete</button>
                                </div>
                                
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
    <!-- /Delete Expense -->


 


    <!-- END: Content-->

    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <!-- JS for Buttons -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

<!-- JSZip (required for Excel export) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script>
        // Create an instance of Notyf
        let notyf = new Notyf({
            duration: 3000,
            position: {
                x: 'right',
                y: 'top',
            },
        });
    </script>


    <script>
 var table= null;
        $(document).ready(function() {
            table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                paging: false,
                 ajax:{
                    url : "{{ route('expense.index') }}",
                    data: function (d) {
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                        d.chart_of_account_id = $('#chart_of_account_id').val();
                        d.user_id = $('#user_id').val();
                       
                    }
                },
                columns: [
                    { data: 'id' },
                    { data: 'expense_no' },
                    { data: 'expense_type' },
                    { data: 'expense_account' },
                    { data: 'reference_no' },
                    { data: 'date' },
                    { data: 'spendBy' },
                    { data: 'COA_name' },
                    { data: 'description' },
                    { data: 'total_amount' },
                    { data: 'total_tax_amount' },
                    { data: 'grand_total' },
                    
                    { data: 'action', orderable: false, searchable: false },
                ],
                order: [[0, 'desc']],
                dom: 'Bfrtip', // Add Buttons
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: 'Expenses Export',
                        exportOptions: {
                            columns: ':not(:last-child)' // exclude 'action' column
                        }
                    }
                ]
            });


            
            $('#filter-btn').on('click', function(){
                table.draw();
            });
            $('#reset-filter-btn').on('click', function(){
                $('#start_date').val('');
                $('#end_date').val('');
                $('#chart_of_account_id').val('').trigger('change');
                $('#user_id').val('').trigger('change');
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

          
     

            $('#submit-expense-destroy').click(function() {
                let brand_id = $(this).data('id');
                var submit_btn = $('#submit-expense-destroy');

                $.ajax({
                    type: 'DELETE',
                    url: "{{ route('expense.destroy', ':id') }}".replace(':id', brand_id), // Using route name
                    data: {
                        _token: "{{ csrf_token() }}" // Add CSRF token
                    },
                    beforeSend: function() {
                            submit_btn.prop('disabled', true);
                            submit_btn.html('Processing');
                        },
                    success: function(response) {
                        
                        submit_btn.prop('disabled', false).html('Delete Expense');  

                        if(response.success == true){
                            $('#delete-expense').modal('hide'); 
                            table.ajax.reload();
                        
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
                        submit_btn.prop('disabled', false).html('Delete Expense');
                    
                        notyf.error({
                            message: e.responseJSON.message,
                            duration: 5000
                        });
                    }
                });
            });

        });

        // Handle the delete button click
       

        function editBrand(id) {
            $.get("{{ route('expense.edit', ':id') }}".replace(':id', id), function(response) {
                $('#brand_id').val(response.id);
                $('#edit_name').val(response.name);
                $('#edit_is_active').val(response.is_active).trigger('change');              


                $('#edit-expense').modal('show');
            }).fail(function(xhr) {
                alert('Error fetching expense details: ' + xhr.responseText);
            });
        }

        function deleteExpense(id) {
            $('#submit-expense-destroy').data('id', id);
            $('#delete-expense').modal('show');
        }

    </script>

    <script>
        $(document).ready(function() {
            $('#table thead tr').clone(true).appendTo('#table thead');
            $('#table thead tr:eq(1) th').each(function(i) {
                var title = $(this).text();
                $(this).html('<input type="text" placeholder="  ' + title +
                    '"  class="form-control form-control-sm" />');


                // hide text field from any column you want too
                if (title == 'Action') {
                    $(this).hide();
                }





                $('input', this).on('keyup change', function() {
                    if (table.column(i).search() !== this.value) {
                        table
                            .column(i)
                            .search(this.value)
                            .draw();
                    }
                });

            });
            var table = $('#table').DataTable({
                orderCellsTop: true,
                fixedHeader: true,
                retrieve: true,
                paging: false

            });
        });
    </script>
@endsection

{{-- 
Brands

brand_id

editBrand
deleteBrand
expense
Expense 
--}}