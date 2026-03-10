@foreach (array_chunk($products->all(), 3) as $chunk)
    <tr>
        @foreach ($chunk as $product)
            <td width="20%" class="text-center p-2" 
                title="{{ $product->variation_barcode ?? '' }}">

                <img src="{{ asset('pos/products/' . ($product->image ?? 'default.png')) }}" alt="{{ $product->variation_barcode }}"
                    class="rounded mb-1" width="200px" height="200px" />
                <div>
                    <strong class="d-block text-truncate">{{ $product->variation_barcode }}</strong>
                    <small class="text-muted">{{ $product->stock }}</small>
                </div>
            </td>
        @endforeach
        @for ($i = count($chunk); $i < 3; $i++)
            <td></td>
        @endfor
    </tr>
@endforeach
