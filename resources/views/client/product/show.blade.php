@extends('client.layouts.app')

@section('content')
<style>
    /* 👇 Style cố định kích thước và hiệu ứng phóng to */
    #mainImageWrapper {
        width: 100%;
        height: 400px;
        overflow: hidden;
        border-radius: 8px;
        box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
    }

    #mainImage {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    #mainImageWrapper:hover #mainImage {
        transform: scale(1.2);
    }
</style>

<div class="container my-5">
    <div class="row g-4">
        <div class="col-md-5">
            <div id="mainImageWrapper">
                <img id="mainImage" src="{{ asset('storage/' . $product->image) }}"
                     class="img-fluid"
                     alt="{{ $product->product_name }}">
            </div>
        </div>

        <div class="col-md-7">
            <h2 class="fw-bold" id="productName">{{ $product->product_name }}</h2>

            {{-- Giá --}}
            <p>
                <strong>Giá: </strong>
                <span id="priceBlock">
                    @if($product->discount_price)
                        <span class="text-muted"><s>{{ number_format($product->price, 0, ',', '.') }} đ</s></span>
                        <span class="text-danger fw-bold ms-2">{{ number_format($product->discount_price, 0, ',', '.') }} đ</span>
                    @else
                        <span class="text-danger fw-bold">{{ number_format($product->price, 0, ',', '.') }} đ</span>
                    @endif
                </span>
            </p>

            <p><strong>Số lượng còn:</strong> <span id="stock">{{ $product->quantity }}</span></p>

            {{-- Thông số kỹ thuật --}}
            <table class="table table-bordered table-sm w-75">
                <tbody>
                    <tr><th>RAM</th><td id="ram">-</td></tr>
                    <tr><th>Lưu trữ</th><td id="storage">-</td></tr>
                    <tr><th>Màu</th><td id="color">-</td></tr>
                </tbody>
            </table>

            {{-- Chọn biến thể --}}
            <h5 class="mt-4">Chọn phiên bản:</h5>
            <div class="d-flex flex-wrap gap-2">
                @foreach($product->variants as $variant)
                    <button type="button" class="btn btn-outline-secondary btn-sm variant-option"
                            data-id="{{ $variant->id }}"
                            data-image="{{ asset('storage/' . ($variant->image ?? $product->image)) }}"
                            data-price="{{ $variant->price }}"
                            data-ram="{{ $variant->ram->value ?? '-' }}"
                            data-storage="{{ $variant->storage->value ?? '-' }}"
                            data-color="{{ $variant->color->value ?? '-' }}"
                            data-quantity="{{ $variant->quantity }}">
                        {{ $variant->ram->value ?? '?' }} / {{ $variant->storage->value ?? '?' }} / {{ $variant->color->value ?? '?' }}
                    </button>
                @endforeach
            </div>

            {{-- Hành động --}}
            <div class="mt-4 d-flex gap-3">
                <form action="" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fa fa-cart-plus"></i> Thêm vào giỏ hàng
                    </button>
                </form>
                <form action="" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-bolt"></i> Mua ngay
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- Sản phẩm liên quan --}}
@if($relatedProducts->count())
<hr class="my-5">
<h4 class="fw-bold mb-4">Sản phẩm liên quan</h4>
<div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
    @foreach($relatedProducts as $item)
        <div class="col">
            <div class="card h-100 border-0 shadow-sm">
                <a href="{{ route('product.show', $item->id) }}">
                    <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top" alt="{{ $item->product_name }}" style="height: 200px; object-fit: cover;">
                </a>
                <div class="card-body p-2">
                    <h6 class="card-title mb-1">
                        <a href="{{ route('product.show', $item->id) }}" class="text-dark text-decoration-none">{{ $item->product_name }}</a>
                    </h6>
                    <p class="mb-0 text-danger fw-semibold">
                        @if($item->discount_price)
                            {{ number_format($item->discount_price, 0, ',', '.') }} đ
                            <small class="text-muted text-decoration-line-through d-block">{{ number_format($item->price, 0, ',', '.') }} đ</small>
                        @else
                            {{ number_format($item->price, 0, ',', '.') }} đ
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endif

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const variantButtons = document.querySelectorAll('.variant-option');

        variantButtons.forEach(button => {
            button.addEventListener('click', function () {
                console.log('Biến thể được chọn:', this.dataset);

                // Cập nhật ảnh
                const image = this.dataset.image;
                if (image) {
                    document.getElementById('mainImage').src = image;
                }

                // Cập nhật giá
                const priceValue = parseInt(this.dataset.price || 0).toLocaleString('vi-VN') + ' đ';
                document.getElementById('priceBlock').innerHTML = `<span class="text-danger fw-bold">${priceValue}</span>`;

                // Cập nhật thông số
                document.getElementById('ram').innerText = this.dataset.ram || '-';
                document.getElementById('storage').innerText = this.dataset.storage || '-';
                document.getElementById('color').innerText = this.dataset.color || '-';
                document.getElementById('stock').innerText = this.dataset.quantity || '-';

                // Active button
                variantButtons.forEach(btn => btn.classList.remove('active', 'btn-primary'));
                this.classList.add('active', 'btn-primary');
            });
        });
    });
</script>
@endpush
