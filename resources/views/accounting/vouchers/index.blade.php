@extends('template.tmp')
@section('title', 'Vouchers')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h3 class="mb-sm-0 font-size-18">All Vouchers</h3>

                            <div class="page-title-right d-flex">

                                <div class="page-btn">
                                    <a href="{{ route('voucher.create') }}" class="btn btn-added btn-primary"><i class="me-2 bx bx-plus"></i>Voucher</a>
                                </div>  
                            </div>



                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div id="filterRow">
                                   <div class="row">
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

                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">Voucher Types</label>
                                                <select name="voucher_type_code" id="voucher_type_code" class="select2 form-control" autofocus>                                                
                                                    <option value="">Choose...</option>
                                                    @foreach ($voucherTypes as $type)
                                                        <option value="{{$type->code}}">{{ $type->code.'-'.$type->name }}</option>
                                                    @endforeach
                                                </select>
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
                                            <th  width="50" >ID</th>
                                            <th  width="100" >Date</th>
                                            <th  width="100" >Voucher No</th>
                                            <th  width="100" >Type</th>
                                            <th  width="350" >Narration</th>
                                            <th  width="150" >Total Amount</th>
                                            <th  width="50" >Action</th>
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
        <div class="modal fade" id="delete-voucher">
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
                                    <p class="text-center">Are you sure you want to delete this voucher?</p>
                                </div>
                                <div class="modal-footer-btn p-3 mt-2">
                                    <button type="button" class="btn btn-cancel me-2" data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-submit shadow-sm btn-danger" id="submit-voucher-destroy">Delete</button>
                                </div>
                                
                        </div>
                    </div>
                </div>
            </div>
        </div>
     
    <!-- /Delete Voucher -->



    <script>

        $(document).ready(function() {
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('voucher.index') }}",
                    data: function (d) {
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                        d.voucher_type_code = $('#voucher_type_code').val();
                    }
                },
                columns: [
                    { data: 'id' },
                    { data: 'date' },
                    { data: 'voucher_no' },
                    { data: 'type' },
                    { data: 'narration' },
                    { data: 'total_amount' },
                    
                    { data: 'action', orderable: false, searchable: false },
                ],
                order: [[0, 'desc']],
            });


            $('#filter-btn').on('click', function(){
                table.draw();
            });
            $('#reset-filter-btn').on('click', function(){
                $('#start_date').val('');
                $('#end_date').val('');
                $('#voucher_type_code').val('').trigger('change');
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


            $('#submit-voucher-destroy').click(function() {
                let voucher_id = $(this).data('id');
                var submit_btn = $('#submit-voucher-destroy');

                $.ajax({
                    type: 'DELETE',
                    url: "{{ route('voucher.destroy', ':id') }}".replace(':id', voucher_id), // Using route name
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
                            $('#delete-voucher').modal('hide'); 
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

        function deleteVoucher(id) {
            $('#submit-voucher-destroy').data('id', id);
            $('#delete-voucher').modal('show');
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
