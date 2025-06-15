{{-- resources/views/client/products/show.blade.php --}}

@extends('layouts.app')

@section('content')
    <h1>{{ $product->name }}</h1>
    <p>{{ $product->description }}</p>

    <!-- Form lựa chọn biến thể -->
    <form>
        <label>RAM:</label>
        <select id="ram">
            @foreach($rams as $ram)
                <option value="{{ $ram->id }}">{{ $ram->name }}</option>
            @endforeach
        </select>

        <label>Bộ nhớ:</label>
        <select id="storage">
            @foreach($storages as $storage)
                <option value="{{ $storage->id }}">{{ $storage->name }}</option>
            @endforeach
        </select>

        <label>Màu sắc:</label>
        <select id="color">
            @foreach($colors as $color)
                <option value="{{ $color->id }}">{{ $color->name }}</option>
            @endforeach
        </select>
    </form>

    <div id="variant-detail" class="mt-4">
        <!-- Dữ liệu biến thể hiển thị ở đây -->
    </div>

    <script>
    function fetchVariant() {
        const productId = {{ $product->id }};
        const ramId = document.getElementById('ram').value;
        const storageId = document.getElementById('storage').value;
        const colorId = document.getElementById('color').value;

        fetch(`{{ route('client.variant.get') }}?product_id=${productId}&ram_id=${ramId}&storage_id=${storageId}&color_id=${colorId}`)
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    document.getElementById('variant-detail').innerHTML = `<p>${data.error}</p>`;
                } else {
                    document.getElementById('variant-detail').innerHTML = `
                        <img src="/storage/${data.image}" width="200">
                        <p>Giá: ${data.discount_price ?? data.price} VNĐ</p>
                        <p>Tồn kho: ${data.quantity}</p>
                    `;
                }
            });
    }

    ['ram', 'storage', 'color'].forEach(id => {
        document.getElementById(id).addEventListener('change', fetchVariant);
    });

    fetchVariant();
    </script>
@endsection
