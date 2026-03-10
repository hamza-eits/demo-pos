@extends('template.tmp')

@section('title', 'Balance Sheet')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
        <div class="card col-md-12">
            <form action="{{ route('balance-sheet.show') }}" method="POST" target="_blank">
                @csrf

                <div class="card-body">
                    <h4 class="card-title">Balance Sheet</h4>

                    <div class="col-md-12">
                        <div class="col-md-4 mt-3">

                            <div class="form-group">
                                <label for="StartDate">Start Date</label>
                                <input type="date" name="date"  id="date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                                <div id="start"></div>
                            </div>
                            
                             <div class="form-group">
                                <label for="EndDate">End Date</label>
                                <input type="date" name="date"  id="date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                                <div id="end"></div>
                            </div>
                            <br> 
                            <button  class="btn btn-primary my-2" type="submit">Submit</button>

                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>        

@endsection