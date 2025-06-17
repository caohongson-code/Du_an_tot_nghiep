@extends('admin.layouts.app')

@section('title', 'Chi tiết giỏ hàng')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Chi tiết giỏ hàng của: {{ $cart->account->full_name ?? 'Không rõ' }}</h2>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Sản phẩm</th>
                <th>Biến thể</th>
                <th>Số lượng</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cart->details as $item)
    <tr>
        <td>{{ $item->id }}</td>
        <td>{{ $item->product->product_name ?? 'Không rõ' }}</td>
        <td>
            @if ($item->variant)
                {{ $item->variant->ram->ram_name ?? '' }},
                {{ $item->variant->storage->storage_name ?? '' }},
                {{ $item->variant->color->color_name ?? '' }}
            @else
                Không có
            @endif
        </td>
        <td>{{ $item->quantity }}</td>
        <td>
            <form action="{{ route('cart-details.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Xoá sản phẩm này?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
            </form>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center">Giỏ hàng trống.</td>
    </tr>
@endforelse

        </tbody>
    </table>

    <a href="{{ route('carts.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Quay lại
    </a>
</div>
@endsection
