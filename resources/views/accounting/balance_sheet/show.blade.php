@extends('template.tmp')
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid col-lg-10 offset-lg-1">

            <!-- Page Title -->
            <div class="mb-4 text-center">
                <h2 class="fw-bold">Balance Sheet</h2>
                {{-- <p><strong>As of:</strong> {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</p> --}}
                <hr>
            </div>

            <!-- Assets Section -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h4 class="mb-0">Assets</h4>
                </div>
                <div class="card-body table-responsive">
                    @php $totalAssets = 0; @endphp
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assetData as $group)
                                <tr style="background:#f9faff">
                                    <td><strong>{{ $group['level3Name'] }}</strong></td>
                                    <td></td>
                                </tr>
                                @foreach($group['level4'] as $child)
                                    <tr>
                                        <td class="ps-4">{{ $child['name'] }}</td>
                                        <td class="text-end">{{ number_format($child['debit'] - $child['credit'], 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="fw-bold">
                                    <td class="ps-4">Total {{ $group['level3Name'] }}</td>
                                    <td class="text-end">{{ $group['level3Balance'] }}</td>
                                </tr>
                                @php $totalAssets += floatval(str_replace(',', '', $group['level3Balance'])); @endphp
                            @endforeach
                            <tr class="table-primary fw-bold">
                                <td>Total Assets</td>
                                <td class="text-end">{{ number_format($totalAssets, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Liabilities Section -->
            <div class="card shadow mb-4">
                <div class="card-header ">
                    <h4 class="mb-0">Liabilities</h4>
                </div>
                <div class="card-body table-responsive">
                    @php $totalLiability = 0; @endphp
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($liabilityData as $group)
                                <tr style="background:#f9faff">
                                    <td><strong>{{ $group['level3Name'] }}</strong></td>
                                    <td></td>
                                </tr>
                                @foreach($group['level4'] as $child)
                                    <tr>
                                        <td class="ps-4">{{ $child['name'] }}</td>
                                        <td class="text-end">{{ number_format($child['credit'] - $child['debit'], 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="fw-bold">
                                    <td class="ps-4">Total {{ $group['level3Name'] }}</td>
                                    <td class="text-end">{{ $group['level3Balance'] }}</td>
                                </tr>
                                @php $totalLiability += floatval(str_replace(',', '', $group['level3Balance'])); @endphp
                            @endforeach
                            <tr class="table-danger fw-bold">
                                <td>Total Liabilities</td>
                                <td class="text-end">{{ number_format($totalLiability, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Equity Section -->
            <div class="card shadow mb-5">
                <div class="card-header">
                    <h4 class="mb-0">Equity</h4>
                </div>
                <div class="card-body table-responsive">
                    @php $totalEquity = 0; @endphp
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($equityData as $group)
                                <tr style="background:#f9faff">
                                    <td><strong>{{ $group['level3Name'] }}</strong></td>
                                    <td></td>
                                </tr>
                                @foreach($group['level4'] as $child)
                                    <tr>
                                        <td class="ps-4">{{ $child['name'] }}</td>
                                        <td class="text-end">{{ number_format($child['credit'] - $child['debit'], 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="fw-bold">
                                    <td class="ps-4">Total {{ $group['level3Name'] }}</td>
                                    <td class="text-end">{{ $group['level3Balance'] }}</td>
                                </tr>
                                @php $totalEquity += floatval(str_replace(',', '', $group['level3Balance'])); @endphp
                            @empty
                                <tr><td colspan="2" class="text-center text-muted">No Equity Data Available</td></tr>
                            @endforelse
                            <tr class="table-success fw-bold">
                                <td>Total Equity</td>
                                <td class="text-end">{{ number_format($totalEquity, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
