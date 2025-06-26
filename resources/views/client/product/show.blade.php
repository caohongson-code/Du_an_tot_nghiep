@extends('client.layouts.app')

@section('content')
<div class="row">
    <div class="col-md-5">
        <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid" alt="{{ $product->product_name }}">
    </div>
    <div class="col-md-7">
        <h2>{{ $product->product_name }}</h2>
        <p><strong>Giá:</strong>
            @if($product->discount_price)
                <span class="text-muted"><s>{{ number_format($product->price, 0, ',', '.') }} đ</s></span>
                <span class="text-danger fw-bold">{{ number_format($product->discount_price, 0, ',', '.') }} đ</span>
            @else
                <span class="text-danger fw-bold">{{ number_format($product->price, 0, ',', '.') }} đ</span>
            @endif
        </p>
        <p><strong>Số lượng:</strong> {{ $product->quantity }}</p>
        <p><strong>Mô tả:</strong> {!! $product->description ?? 'Đang cập nhật' !!}</p>
    </div>
</div>
@endsection
