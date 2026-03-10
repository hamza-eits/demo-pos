@extends('template.tmp')
@section('title', 'Recipe Create')

@section('content')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <style>
        /* Chrome, Safari, Edge, Opera : remove spin input type number*/
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;


        }

        .table>:not(caption)>*>* {
            padding: 0.15rem .15rem !important;
        }

        table tbody tr input.form-control {

            border-radius: 0rem !important;
            font-size: 11px;

        }

        #summary-table input.form-control {
            /* border: 0; */
            border-radius: 0.25rem !important;
        }

        .form-control:disabled,
        .form-control[readonly] {
            background-color: #eff2f780 !important;
            opacity: 1;
        }
    </style>
    <style>
        .ui-state-highlight {
            height: 40px;
            background-color: #f0f0f0;
        }
    </style>
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <form id="recipe-store" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    {{-- <h4 class="card-title mb-4">Purchase Order</h4> --}}
                                    <h4 class="card-title mb-4">Recipe</h4>

                                    <div class="row">


                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Date</label>
                                                <div class="input-group">
                                                    <input type="date" name="start_date" value="{{ date('Y-m-d') }}"
                                                        class="form-control" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Time</label>
                                                <div class="input-group">
                                                    <input type="time" name="start_time"
                                                        value="{{ now()->format('H:i') }}" class="form-control"
                                                        autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label">Dish List</label>
                                                <select name="variaiton_type_dish_id"
                                                    class="form-control select2 item-dropdown" style="width:100%">
                                                    <option value="">Choose...</option>
                                                    @foreach ($variaitonTypeDish as $variation)
                                                        <option value="{{ $variation->id }}">
                                                            {{ $variation->product->name . ' - ' . $variation->name }}
                                                        </option>
                                                    @endforeach

                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label">Name</label>
                                                <div class="input-group">
                                                    <div class="input-group-text"><span class="bx bx-cube"></span> </div>
                                                    <input type="text" name="name" class="form-control"
                                                        autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label">Description</label>
                                                <div class="input-group">
                                                    <div class="input-group-text"><span
                                                            class="bx bx-message-square-dots"></span> </div>
                                                    <input type="text" name="description" class="form-control"
                                                        autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card">

                                <div class="card-body">
                                    <h4 class="card-title mb-4">Recipe Details</h4>
                                    <div class="table-responsive">
                                        <table id="table" class="table table-border" style="border-collapse:collapse;">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" width="50"></th>
                                                    <th class="text-center" width="200">Material Name</th>
                                                    <th class="" width="150">Unit</th>
                                                    <th class="text-center" width="100">QTY</th>
                                                    <th class="text-center" width="50"></th>

                                                </tr>
                                            </thead>
                                            <tbody id="sortable-table">


                                            </tbody>

                                        </table>

                                        <button id="btn-add-more" class="btn btn-primary"><span class="bx bx-plus"></span>
                                            Add More</button>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-8">
                                        </div>

                                        <div class="col-md-4 d-flex align-productVariations-center">
                                            <table id="summary-table" class="table">
                                                <tr>
                                                    <th width="50%">Total Quantity KG's </th>
                                                    <td width="50%">
                                                        <input type="number" name="total_quantity" id="total_quantity"
                                                            value="0" class="form-control text-end" readonly>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>



                                </div>

                            </div>
                        </div>
                    </div>




                    <div class="row  mt-2">

                        <div class="col-md-12 text-end">
                            <button type="submit" id="submit-recipe-store" class="btn btn-success w-md">Save</button>
                            <a href="{{ route('recipe.index') }}"class="btn btn-secondary w-md ">Cancel</a>

                        </div>

                    </div>



                </form>

            </div>
        </div>
    </div>



    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>



    <script>
        $(document).ready(function() {
            appendNewRow();
        });
        //  Detect Enter key in input fields



        $(document).on('change', '.item-dropdown-list', function(e) {

            let selected_item = $(this).find('option:selected');

            let unit_id = selected_item.data('unit-id');

            let row = $(this).closest('tr');

            let unit_dropdown = row.find('.unit-dropdown-list');

            unit_dropdown.val(unit_id).trigger('change');

            $(this).select2('close');

            calculation(row);


        });


        //Quantity Value on blur make it upto four decimal 
        $(document).on('blur', '.item-quantity', function(e) {
            e.preventDefault(); // Prevent the default behavior (form submission)
            let qty = parseFloat($(this).val());
            $(this).val(qty.toFixed(4));
        });

        //Quantity Value on keyup sum the quantity
        $(document).on('keyup', '.item-quantity', function(e) {

            summaryCalculation();
        });

        function summaryCalculation() {
            let total_quantity = 0;
            $('.item-quantity').each(function() {
                total_quantity += parseFloat($(this).val()) || 0;
            });

            $('#total_quantity').val(total_quantity.toFixed(4));
        }


        $('#recipe-store').on('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // Prevent the default behavior (form submission)
            }
        });



        $('#btn-add-more').on('click', function(e) {
            e.preventDefault();

            appendNewRow();

        });

        $(document).on('click', '.remove-item', function(e) {
            e.preventDefault();

            // Show a confirmation dialog
            if (confirm("Are you sure you want to remove this item?")) {
                // If confirmed, remove the row
                $(this).closest('tr').remove();

                // Recalculate the summary
                summaryCalculation();
            }
        });



        function appendNewRow() {
            let tableBody = $('#table tbody');

            let row = `
        <tr>
            <td><a style="cursor:grab"><i style="font-size:25px" class="mdi mdi-drag handle text-dark"></i></a> </td>

            <td> 
                <select  name="variaiton_type_raw_id[]" class="form-control select2 item-dropdown-list" style="width:100%; border:1px solid red">                                                
                    <option >Choose...</option>
                    @foreach ($variaitonTypeRaw as $variation)
                        <option value="{{ $variation->id }}" data-unit-id="{{ $variation->unit_id }}">{{ $variation->name }}</option>
                    @endforeach
                    
                </select>
                
            </td> 
            <td class=""> 
                <select name="unit_id[]"  class="form-control select2 unit-dropdown-list" style="width:100%">                                                
                    <option>Choose...</option>
                    @foreach ($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->child_unit }}</option>
                    @endforeach
                </select>
                
            </td> 
            <td>
                <input type="number" name="quantity[]" step="0.0001" class="form-control item-quantity">  
            </td>
            <td class="text-center">  
                <a href="#"><span style="font-size:18px" class="bx bx-trash text-danger remove-item"></span></a>
            </td>
        </tr>
        `;

            tableBody.append(row);
            $('.select2', 'table').select2();

        }
    </script>


    <script>
        $(function() {
            // Enable full sorting for tbody rows, allowing drag and drop from bottom to top
            $("#sortable-table").sortable({
                handle: ".handle", // Set the 'handle' option to the bx-menu icon
                placeholder: "ui-state-highlight", // Placeholder while dragging
                axis: "y", // Restrict dragging to vertical movement
                update: function(event, ui) {
                    // This event is triggered when the row has been moved
                    console.log('Row moved');
                }
            }).disableSelection(); // Disable text selection while dragging
        });
    </script>



    <script>
        $('#recipe-store').on('submit', function(e) {
            e.preventDefault();
            var submit_btn = $('#submit-recipe-store');
            let createformData = new FormData(this);
            $.ajax({
                type: "POST",
                url: "{{ route('recipe.store') }}",
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

                    submit_btn.prop('disabled', false).html('Save');

                    if (response.success == true) {
                        $('#recipe-store')[0].reset(); // Reset all form data

                        notyf.success({
                            message: response.message,
                            duration: 3000
                        });

                        // Redirect after success notification
                        setTimeout(function() {
                            window.location.href = '{{ route('recipe.index') }}';
                        }, 200); // Redirect after 3 seconds (same as notification duration)


                    } else {
                        notyf.error({
                            message: response.message,
                            duration: 5000
                        });
                    }
                },
                error: function(e) {
                    submit_btn.prop('disabled', false).html('Save');

                    notyf.error({
                        message: e.responseJSON.message,
                        duration: 5000
                    });
                }
            });
        });
    </script>



@endsection
