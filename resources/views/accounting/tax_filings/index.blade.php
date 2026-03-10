@extends('template.tmp')
@section('title', 'Tax Filings')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h3 class="mb-sm-0 font-size-18">All Tax Filings</h3>

                            <div class="page-title-right d-flex">

                                <div class="page-btn">
                                    <a href="{{ route('tax-filing.create') }}" class="btn btn-added btn-primary"><i class="me-2 bx bx-plus"></i>Tax Filing</a>
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
                                            <th>Voucher No</th>
                                            <th>Start Month Year</th>
                                            <th>End Month Year</th>
                                            <th>Filied On</th>
                                            <th>Input Tax</th>
                                            <th>Output Tax</th>
                                            <th>Total Payable</th>
                                            <th>Voucher</th>
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


     <!-- Delete Voucher -->
        <div class="modal fade" id="delete-tax-filing">
            <div class="modal-dialog custom-modal-two">
                <div class="modal-content">
                    <div class="page-wrapper-new p-0">
                        <div class="content">
                            <div class="modal-header border-0 custom-modal-header">
                                <div class="page-title">
                                    <h4>Delete Voucher</h4>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    
                                </button>
                            </div>
                            
                                <div class="modal-body custom-modal-body pt-3 pb-0">
                                    <p class="text-center">Are you sure you want to delete this tax-filing?</p>
                                </div>
                                <div class="modal-footer-btn p-3 mt-2">
                                    <button type="button" class="btn btn-cancel me-2" data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-submit shadow-sm btn-danger" id="submit-tax-filing-destroy">Delete</button>
                                </div>
                                
                        </div>
                    </div>
                </div>
            </div>
        </div>
     
    <!-- /Delete Voucher -->

<!-- Post Item -->
<div class="modal fade" id="post-tax-filing">
    <div class="modal-dialog custom-modal-two">
        <div class="modal-content">
            <div class="page-wrapper-new p-0">
                <div class="content">
                    <div class="modal-header border-0 custom-modal-header">
                        <div class="page-title">
                            <h4>Post Purchase Invoice</h4>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            
                        </button>
                    </div>
                    
                        <div class="modal-body custom-modal-body pt-3 pb-0">
                            <p class="text-center">Are you sure you want to post this purchase invoice?</p>
                        </div>
                        <div class="modal-footer-btn p-3 mt-2">
                            <button type="button" class="btn btn-cancel me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-submit shadow-sm btn-success" id="submit-tax-filing-post">Post</button>
                        </div>
                        
                </div>
            </div>
        </div>
    </div>
</div>

<!-- /Post Item -->



    <script>

        $(document).ready(function() {
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('tax-filing.index') }}",
                },
                columns: [
                    { data: 'id' },
                    { data: 'voucher_no' },
                    { data: 'start_month_year' },
                    { data: 'end_month_year' },
                    { data: 'filed_on' },
                    { data: 'input_tax' },
                    { data: 'output_tax' },
                    { data: 'total_payable' },
                    { data: 'is_voucher_created' },
                    { data: 'action', orderable: false, searchable: false },
                ],
                order: [[0, 'desc']],
            });


            $('#submit-tax-filing-post').click(function (){
                let id = $(this).data('id');
                var submit_btn = $('#submit-tax-filing-post');

                $.ajax({
                    type: 'POST',
                    url: "{{ route('tax-filing.posting', ':id') }}".replace(':id', id), // Using route name
                    data: {
                        _token: "{{ csrf_token() }}" // Add CSRF token
                    },
                    beforeSend: function() {
                            submit_btn.prop('disabled', true);
                            submit_btn.html('Processing');
                        },
                    success: function(response) {
                        
                        submit_btn.prop('disabled', false).html('Post');  

                        if(response.success == true){
                            $('#post-tax-filing').modal('hide'); 
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
                        submit_btn.prop('disabled', false).html('Post');
                    
                        notyf.error({
                            message: e.responseJSON.message,
                            duration: 5000
                        });
                    }
                });
            });

            $('#submit-tax-filing-destroy').click(function() {
                let id = $(this).data('id');
                var submit_btn = $('#submit-tax-filing-destroy');

                $.ajax({
                    type: 'DELETE',
                    url: "{{ route('tax-filing.destroy', ':id') }}".replace(':id', id), // Using route name
                    data: {
                        _token: "{{ csrf_token() }}" // Add CSRF token
                    },
                    beforeSend: function() {
                            submit_btn.prop('disabled', true);
                            submit_btn.html('Processing');
                        },
                    success: function(response) {
                        
                        submit_btn.prop('disabled', false).html('Delete Voucher');  

                        if(response.success == true){
                            $('#delete-tax-filing').modal('hide'); 
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
                        submit_btn.prop('disabled', false).html('Delete Voucher');
                    
                        notyf.error({
                            message: e.responseJSON.message,
                            duration: 5000
                        });
                    }
                });
            });

        });

        // Handle the delete button click

        function deleteTaxFiling(id) {
            $('#submit-tax-filing-destroy').data('id', id);
            $('#delete-tax-filing').modal('show');
        }
        function posting(id) {
            $('#submit-tax-filing-post').data('id', id);
            $('#post-tax-filing').modal('show');
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
