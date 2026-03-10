@foreach (array_chunk($products->all(), 3) as $chunk)
    <tr>
        @foreach ($chunk as $product)
            <td width="20%" class="product-img text-center p-2" 
            title="{{ $product->name ?? '' }}"
                data-id="{{ $product->id }}" 
                data-name="{{ $product->name }}" 
                data-variation-type="{{ $product->variation_type }}">
                <img src="{{ asset('pos/products/' . ($product->image ?? 'default.png')) }}" alt="{{ $product->name }}"
                    class=" rounded mb-1" width="200px" height="200px" />
                <div>
                    <strong class="d-block text-truncate">{{ $product->name }}</strong>
                    <small class="text-muted">{{ $product->description }}</small>
                </div>
            </td>
        @endforeach
        @for ($i = count($chunk); $i < 3; $i++)
            <td></td>
        @endfor
    </tr>
@endforeach
