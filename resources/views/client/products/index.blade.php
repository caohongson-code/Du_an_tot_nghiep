{{-- resources/views/client/products/index.blade.php --}}

@extends('admin.layouts.app')

@section('content')
<h2>Danh sách sản phẩm</h2>

<div class="grid grid-cols-3 gap-4">
    @foreach($products as $product)
        <div class="border p-4 rounded">
            <h3 class="text-lg font-semibold">{{ $product->name }}</h3>

            @php
                $thumbnail = $product->variants->first()?->image;
            @endphp

            @if($thumbnail)
                <img src="{{ asset('storage/' . $thumbnail) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
            @endif

            <p>
               <a href="{{ route('client.products.show', $product->slug) }}">Xem chi tiết</a>

            </p>
        </div>
    @endforeach
</div>
@endsection
