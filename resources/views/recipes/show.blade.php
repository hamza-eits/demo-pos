@extends('template.tmp')
@section('title', 'Recipe Show')

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
            <div class="container-fluid col-md-6">
                <!-- start page title -->
                <form>
                    <input type="hidden" name="recipe_id" id="recipe_id" value="{{ $recipe->id }}">
                    <!-- Hidden field to store the brand ID -->
                    <div class="card">
                        <div class="card-body">
                            {{-- <h4 class="card-title mb-4">Purchase Order</h4> --}}
                            <h4 class="card-title mb-4">Recipe: {{ $recipe->name }} </h4>

                            <div class="row">



                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Name</label>
                                        <div class="input-group">
                                            <div>{{ $recipe->name }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Description</label>
                                        <div class="input-group">
                                            <div>{{ $recipe->description }}</div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>


                    <div class="card">

                        <div class="card-body">
                            <h4 class="card-title mb-4">Recipe Details</h4>
                            <div class="table-responsive">
                                <table id="table" class="table table-border" style="border-collapse:collapse;">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Material Name</th>
                                            <th>Unit</th>
                                            <th class="text-center">QTY <sub>KG's</sub></th>

                                        </tr>
                                    </thead>
                                    <tbody id="sortable-table">
                                        @php
                                            $i = 1;
                                        @endphp
                                        @foreach ($recipe->details as $detail)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td> {{ $detail->productVariation->name }}</td>
                                                <td> {{ $detail->unit->base_unit }}</td>
                                                <td class="text-end"> {{ $detail->quantity }} </td>

                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-end fw-bold">{{ $recipe->total_quantity }}</td>
                                        </tr>

                                    </tbody>
                                </table>

                            </div>



                        </div>

                    </div>
                    <div class="row  mt-2">

                        {{-- <div class="col-md-12 text-end">
                            <button type="submit" id="submit-recipe-update" class="btn btn-success w-md">Save</button>
                            <a href="{{ route('recipe.index') }}"class="btn btn-secondary w-md ">Cancel</a>
        
                        </div> --}}

                    </div>



                </form>

            </div>
        </div>
    </div>









@endsection
