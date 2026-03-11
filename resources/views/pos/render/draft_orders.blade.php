<table id="table" class="table table-striped table-sm " style="width:100%">
    <thead>
        <tr>
            <th>Invoice No</th>
            <th>Customer Name</th>
            <th>Customer Phone</th>
            <th>Serving Type</th>
            <th>Grand Total</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $order)
            <tr>
                <td>{{ $order->invoice_no }}</td>
                <td>{{ $order->party->business_name ?? 'N/A' }}</td>
                <td>{{ $order->party->mobile ?? 'N/A' }}</td>
                <td>{{ $order->serving_type }}</td>
                <td>{{ $order->grand_total }}</td> 
                <td>
                    <a href="{{ route('point-of-sale.edit', $order->id) }}">Edit</a>
                </td>
                <td>
                    <button class="btn btn-primary print-kot-btn" 
                        data-url="{{ route('pos.printKot', ['id' => $order->id]) }}"
                    >KOT</button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>