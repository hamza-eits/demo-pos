@extends('template.tmp')

@section('title', 'Biller Wise Sales Report')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/min/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.js"></script>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Biller Wise Sales Report</h4>
                    </div>
                </div>
            </div>

            <div class="">
                <div class="">
                    <div class="col-md-4">
                        <div class="col-md-12">
                            <div class="mt-3 mb-1 mt-1 text-start">
                                <div class="dropdown">
                                    <button class="btn btn-primary dropdown-toggle btn-block" type="button"
                                        id="dateRangeDropdown" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        Select Date Range <i class="mdi mdi-chevron-down"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dateRangeDropdown">
                                        <a class="dropdown-item" href="#" data-range="today">Today</a>
                                        <a class="dropdown-item" href="#" data-range="this_week">This Week</a>
                                        <a class="dropdown-item" href="#" data-range="this_month">This Month</a>
                                        <a class="dropdown-item" href="#" data-range="this_quarter">This Quarter</a>
                                        <a class="dropdown-item" href="#" data-range="this_year">This Year</a>
                                        <a class="dropdown-item" href="#" data-range="ytd">Year to Date</a>
                                        <a class="dropdown-item" href="#" data-range="yesterday">Yesterday</a>
                                        <a class="dropdown-item" href="#" data-range="previous_week">Previous Week</a>
                                        <a class="dropdown-item" href="#" data-range="previous_month">Previous Month</a>
                                        <a class="dropdown-item" href="#" data-range="previous_quarter">Previous Quarter</a>
                                        <a class="dropdown-item" href="#" data-range="previous_year">Previous Year</a>
                                        <a class="dropdown-item" href="#" data-range="custom">Custom Range</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('pos-reports.billerWiseSalesShow') }}" method="POST">
                            <div class="col-md-12">
                                @csrf
                                <div class="mt-3">
                                    <div class="form-group">
                                        <label for="StartDate">Start Date</label>
                                        <input type="date" name="startDate" value="{{ old('startDate', date('Y-m-01')) }}"
                                            id="StartDate" class="form-control" required>
                                    </div>

                                    <div class="form-group mt-2">
                                        <label for="EndDate">End Date</label>
                                        <input type="date" name="endDate" value="{{ old('endDate', date('Y-m-d')) }}"
                                            id="EndDate" class="form-control" required>
                                    </div>

                                    {{-- ✅ Biller dropdown — only users with type = 'user' --}}
                                    <div class="form-group mt-2">
                                        <label for="biller_id">Biller</label>
                                        <select name="biller_id" id="biller_id" class="select2 form-control" required>
                                            <option value="">Select Biller</option>
                                            @foreach ($billers as $biller)
                                                <option value="{{ $biller->id }}" {{ old('biller_id') == $biller->id ? 'selected' : '' }}>
                                                    {{ $biller->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>

                            <button type="submit" class="btn btn-success w-lg mt-4">Report</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        const startDateInput = $('#StartDate');
        const endDateInput   = $('#EndDate');

        function setDateRange(start, end) {
            startDateInput.val(start.format('YYYY-MM-DD'));
            endDateInput.val(end.format('YYYY-MM-DD'));
        }

        $('.dropdown-menu a').click(function() {
            let range = $(this).data('range');
            let start, end;

            switch (range) {
                case 'today':           start = end = moment(); break;
                case 'this_week':       start = moment().startOf('week');      end = moment().endOf('week');      break;
                case 'this_month':      start = moment().startOf('month');     end = moment().endOf('month');     break;
                case 'this_quarter':    start = moment().startOf('quarter');   end = moment().endOf('quarter');   break;
                case 'this_year':       start = moment().startOf('year');      end = moment().endOf('year');      break;
                case 'ytd':             start = moment().startOf('year');      end = moment();                    break;
                case 'yesterday':       start = end = moment().subtract(1, 'days'); break;
                case 'previous_week':   start = moment().subtract(1,'weeks').startOf('week');   end = moment().subtract(1,'weeks').endOf('week');   break;
                case 'previous_month':  start = moment().subtract(1,'months').startOf('month'); end = moment().subtract(1,'months').endOf('month'); break;
                case 'previous_quarter':start = moment().subtract(1,'quarters').startOf('quarter'); end = moment().subtract(1,'quarters').endOf('quarter'); break;
                case 'previous_year':   start = moment().subtract(1,'years').startOf('year');   end = moment().subtract(1,'years').endOf('year');   break;
                case 'custom':
                    $('#StartDate').daterangepicker({ opens: 'right', locale: { format: 'YYYY-MM-DD' } },
                        function(s, e) { setDateRange(s, e); });
                    return;
            }
            setDateRange(start, end);
        });
    });
</script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
@endsection