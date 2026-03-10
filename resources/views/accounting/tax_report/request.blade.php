@extends('template.tmp')

@section('title', 'Tax Report')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
        <div class="card">
            <form id="request-form" method="POST" target="_blank">
                @csrf

                <div class="card-body">
                    <h4 class="card-title">Tax Report</h4>

                    <div class="col-md-12">
                        <div class="col-md-4 mt-3">
                            <div class="form-group mt-2">
                                <label>Transaction Type</label>
                                <select name="type" class="select2 form-control" style="width: 100%">
                                    <option value="">All</option>
                                    <option value="PI">Purchase Invoice</option>
                                    <option value="SI">Sale Invoice</option>
                                    <option value="EXP">Expense</option>
                                </select>
                            </div>
                            <div class="form-group mt-2">
                                <label>Filing Status</label>
                                <select name="filingStatus" class="select2 form-control" style="width: 100%">
                                    <option value="">All</option>
                                    <option value="1">Filed</option>
                                    <option value="0">Unfiled</option>
                                  
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="StartDate">Start Date</label>
                                <input type="date" name="startDate"  id="StartDate" class="form-control" value="{{ date('Y-m-01') }}" required>
                                <div id="start"></div>
                            </div>
                            <div class="form-group mt-2">
                                <label for="EndDate">End Date</label>
                                <input type="date" name="endDate" value="{{ date('Y-m-d') }}" class="form-control" required>
                            </div>
                            
                           
                            <button id="view" class="btn btn-primary my-2 mt-3 mx-2" type="button">View</button>
                            <button id="pdf" class="btn btn-primary my-2 mt-3 mx-2" type="button">PDF</button>
                            <button id="excel-export" class="btn btn-primary my-2 mt-3 mx-2" type="button">Excel Export</button>

                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>        
<script>
    $('#view').on('click', function(e){
        e.preventDefault();
        $('#request-form').attr('action','{{ route('tax-report.show') }}');
        $('#request-form').submit();
    });
    $('#pdf').on('click', function(e){
        e.preventDefault();
        $('#request-form').attr('action','{{ route('tax-report.pdf') }}');
        $('#request-form').submit();
    });
    $('#excel-export').on('click', function(e){
        e.preventDefault();
        $('#request-form').attr('action','{{ route('tax-report.excel-export') }}');
        $('#request-form').submit();
    });
</script>
@endsection