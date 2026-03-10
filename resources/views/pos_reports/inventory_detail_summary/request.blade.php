@extends('template.tmp')

@section('title', 'Inventory Detail Summary')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/min/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.js"></script>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Inventory Detail Summary</h4>
                    </div>
                </div>
            </div>


           <div class="card">
            <div class="card-body">
                <div class="col-md-4">
                   
                    <form action="{{ route('pos-reports.inventoryDetailSummaryShow') }}" method="POST">
                        <div class="col-md-12">
                            @csrf
                            <div class="mt-3">
                               
                                <div class="form-group mt-2">
                                    <label for="EndDate">End Date</label>
                                    <input type="date" name="endDate" id="EndDate" value="{{ old('EndDate',date('Y-m-d')) }}" class="form-control" required>
                                    <div id="end"></div>
                                </div>
                                 
                                <div class="form-group mt-2">
                                    <label >Products</label>
                                    <select name="product_id" class="select2 form-control">
                                        <option value="">Select Product</option>
                                        @foreach ($data['products'] as $row)
                                            <option value="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mt-2">
                                    <label >Material</label>
                                    <select name="material_id" class="select2 form-control">
                                        <option value="">Select Material</option>
                                        @foreach ($data['materials'] as $row)
                                            <option value="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mt-2">
                                    <label >Product Model</label>
                                    <select name="product_model_id" class="select2 form-control">
                                        <option value="">Select Product</option>
                                        @foreach ($data['productModels'] as $row)
                                            <option value="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mt-2">
                                    <label >Custom Field</label>
                                    <select name="custom_field_id" class="select2 form-control">
                                        <option value="">Select Custom Field</option>
                                        @foreach ($data['customFields'] as $row)
                                            <option value="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mt-2">
                                    <label >Category</label>
                                    <select name="category_id" class="select2 form-control">
                                        <option value="">Select Category</option>
                                        @foreach ($data['categories'] as $row)
                                            <option value="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                        
                                
                            </div>
                        </div>
                            
                        <button type="reset" class="btn btn-warning w-lg float-right mt-4">Reset</button>
                        <button type="submit" class="btn btn-success w-lg float-right mt-4">Report</button>
                    </form>
    
    
                    
                </div>
            </div>
           </div>
        </div>
    </div>
</div>  



<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
crossorigin="anonymous"></script>


<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        $('button[type="reset"]').click(function(e) {
            // Prevent the default reset to avoid breaking select2
            e.preventDefault();

            // Reset the form fields
            $('form')[0].reset();

            // Reset all select2 dropdowns
            $('.select2').val(null).trigger('change');
        });
    });
</script>

@endsection            