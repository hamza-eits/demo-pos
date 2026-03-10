<tbody id="cash-invoice-list">
    @foreach ($data->where('mode','cash') as $value)
        <tr>
            <td class="text-start">{{ $loop->iteration }}</td>
            <td class="text-start">{{ date('d-m-Y h:i:s A', strtotime($value->created_at)) }}</td>
            <td class="text-center">{{ $value->invoiceMaster->invoice_no }}</td>
            <td class="text-start">{{ $value->invoiceMaster->party->business_name ?? 'N/A' }}</td>
            <td class="text-start">{{ $value->invoiceMaster->biller->name ?? 'N/A' }}</td>
            <td class="text-center">{{ $value->number_of_payments }}</td>
            <td class="text-center">{{ $value->amount }}</td>        
        </tr>   
    @endforeach
        <tr>
            <th colspan="6" class="text-start">Total Cash Sales</th>
            <th class="text-center">{{ $cashSale }}</th>
        </tr>
</tbody>