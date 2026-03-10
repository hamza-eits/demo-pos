@extends('template.tmp')
@section('title', 'kohisar')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="container">
                    <h2>Chart of Accounts</h2>

                        <div class="card">

                            <div class="card-body">
                                <table id="table" class="table table-striped table-sm " style="width:100%">
                                    <tr>
                                        <th>Level 1</th>
                                        <th>Level 2</th>
                                        <th>Level 3</th>
                                        <th>Level 4</th>
                                        <th></th>
                                    </tr>
                                    @foreach ($accounts as $level_1)
                                        
                                
                                    <tr>
                                    <td><strong>{{ $level_1->account_name }}</strong> ({{ $level_1->account_code }})</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><span class="fas fa-lock text-primary"></span></td>
                                    </tr>

                                        @if ($level_1->children->isNotEmpty())
                                            @foreach ($level_1->children as $level_2 )
                                                <tr>
                                                    <td></td>
                                                    <td>
                                                        <a href="#" class="add-new-account-btn text-primary" id="{{ 'level-2-account-'. $level_2->id }}" 
                                                            data-id = {{ $level_2->id }}
                                                            data-code = {{ $level_2->account_code }}
                                                            data-name = {{ $level_2->account_name }} >
                                                            <i class="fas fa-plus-square fs-5 pt-1"></i>
                                                        </a>
                                                        {{ $level_2->account_name }} ({{ $level_2->account_code }})
                                                    </td>
                                                        
                                                    <td></td>
                                                    <td></td>
                                                    <td><span class="fas fa-lock text-primary"></span></td>
                                                </tr>


                                                @if ($level_2->children->isNotEmpty())
                                                    @foreach ($level_2->children as $level_3 )
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td>{{ $level_3->account_name }} ({{ $level_3->account_code }})</td>
                                                            <td></td>
                                                            <td>
                                                                <a href="#" class="edit-account-btn text-warning" id="{{ 'level-3-account-'. $level_3->id }}" 
                                                                    data-id = {{ $level_3->id }}
                                                                    data-code = {{ $level_3->account_code }}
                                                                    data-name = {{ $level_3->account_name }} >
                                                                    <i class=" fas fa-pencil-alt"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
        
                                                        
                                                        
                                                    @endforeach
                                                @endif        

                                                
                                            @endforeach
                                        @endif        

                                    @endforeach
                                
                                </table>
                            </div>    
                        </div>    



                        {{-- @foreach ($accounts as $account)



                            <li class="list-group-item">
                                <strong>{{ $account->account_name }}</strong> ({{ $account->account_code }})

                                @if ($account->children->isNotEmpty())
                                    <ul class="list-group mt-2">
                                        @foreach ($account->children as $child)
                                            <ul class="list-group-item">
                                                {{ $child->account_name }} ({{ $child->account_code }})
                                                <button class=" add-new-account-btn btn btn-primary btn-sm mx-2"
                                                
                                                    id="{{ 'sub-account_'. $child->id }}" 
                                                    data-id = {{ $child->id }}
                                                    data-code = {{ $child->account_code }}
                                                    data-name = {{ $child->account_name }}
                                                    
                                                    >
                                                
                                                    
                                                    <i class=" mdi mdi-plus"></i></button>


                                            </ul>
                                                @foreach ($child->children as $child)
                                                <li class="list-group-item">
                                                    {{ $child->account_name }} ({{ $child->account_code }})
                                                </li>

                                                @endforeach
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach --}}
                    </ul>
                </div>
            </div>
        </div>
    </div>



    <!-- Add Chart Of Account -->
    <div class="modal fade" id="add-chart-of-account">
        <div class="modal-dialog custom-modal-two">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0 custom-modal-header">
                            <div class="page-title">
                                <h4>Create Chart Of Account</h4>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                
                            </button>
                        </div>
                        <div class="modal-body custom-modal-body">
                            <form id="chart-of-account-store" enctype="multipart/form-data" >
                                @csrf
                                <input type="hidden" name="parent_id" id="parent_id">

                                <div class="mb-3">
                                    <label class="form-label">Code</label>
                                    <input type="text" name="parent_account_code" id="parent_account_code" class="form-control" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="parent_account_name" id="parent_account_name" class="form-control" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Account Name</label>
                                    <input type="text" name="account_name" class="form-control">
                                </div>
                              
    
                            
                                <div class="modal-footer-btn">
                                    <button type="button" class="btn btn-cancel me-2 btn-dark" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" id="submit-chart-of-account-store" class="btn btn-submit btn-primary">Create Chart Of Account</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Chart Of Account -->





    <!-- Edit Chart Of Account -->
    <div class="modal fade" id="edit-chart-of-account">
        <div class="modal-dialog custom-modal-two">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0 custom-modal-header">
                            <div class="page-title">
                                <h4>Edit Chart Of Account</h4>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                
                            </button>
                        </div>
                        <div class="modal-body custom-modal-body">
                            <form id="chart-of-account-update" enctype="multipart/form-data">
                                @csrf
                                @method('PUT') <!-- For PUT method -->
                                <input type="hidden" name="id" id="edit_id">
                                <div class="mb-3">
                                    <label class="form-label">Code</label>
                                    <input type="text" id="edit_account_code" class="form-control" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="account_name" id="edit_account_name" class="form-control">
                                </div>

                                <div class="modal-footer-btn">
                                    <button type="button" class="btn btn-cancel me-2 btn-dark" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" id="submit-chart-of-account-update" class="btn btn-submit btn-primary">Update Chart Of Account</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- /Edit Chart Of Account -->

    <!-- Loading Modal -->
    <div id="loadingModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p>Loading, please wait...</p>
                </div>
            </div>
        </div>
    </div>

    


    <script>

        $(document).ready(function () {
            $('.add-new-account-btn').on('click', function(e){
                e.preventDefault();

                let id = $(this).data('id');
                let code = $(this).data('code');
                let name = $(this).data('name');

                $('#parent_id').val(id);
                $('#parent_account_code').val(code);
                $('#parent_account_name').val(name);

                $('#add-chart-of-account').modal('show');

            });




            $('.edit-account-btn').on('click', function(e){
                e.preventDefault();

                let id = $(this).data('id');
                let code = $(this).data('code');
                let name = $(this).data('name');

                $('#edit_id').val(id);
                $('#edit_account_code').val(code);
                $('#edit_account_name').val(name);

                $('#edit-chart-of-account').modal('show');

            });



            $('#chart-of-account-store').on('submit', function(e) {
                e.preventDefault();
                var submit_btn = $('#submit-chart-of-account-store');
                let createformData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('chart-of-account.store') }}",
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
                        
                        submit_btn.prop('disabled', false).html('Create Chart Of Account');  

                        if(response.success == true){
                            $('#add-chart-of-account').modal('hide'); 
                            $('#chart-of-account-store')[0].reset();  // Reset all form data
                            location.reload(true);                        
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
                        submit_btn.prop('disabled', false).html('Create Chart Of Account');
                    
                        notyf.error({
                            message: e.responseJSON.message,
                            duration: 5000
                        });
                    }
                });
            });



            $('#chart-of-account-update').on('submit', function(e) {
                e.preventDefault();
                var submit_btn = $('#submit-chart-of-account-update');
                let chart_of_account_id = $('#edit_id').val(); // Get the ID of the chart-of-account being edited

                let editFormData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('chart-of-account.update', ':id') }}".replace(':id', chart_of_account_id), // Using route name
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
                        
                        submit_btn.prop('disabled', false).html('Update Chart of Account');  

                        if(response.success == true){
                            $('#edit-chart-of-account').modal('hide'); 
                            $('#chart-of-account-update')[0].reset();  // Reset all form data
                            location.reload(true);                        
                        
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
                        submit_btn.prop('disabled', false).html('Update Chart of Account');
                    
                        notyf.error({
                            message: e.responseJSON.message,
                            duration: 5000
                        });
                    }
                });
            });
        });
    </script>

<!-- /Add Chart Of Account -->
@endsection