@extends('client.layouts.app')

@section('content')
    <style>
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

        .product-title {
            margin-bottom: 20px;
            font-size: 1.5rem;
        }

        .product-price {
            margin-bottom: 20px;
            font-size: 1.5rem;
        }

        .container.my-5 {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
    </style>

    <div class="container my-5">
        <div class="row g-4">
            <div class="col-md-5">
                <div id="mainImageWrapper">
                    <img id="mainImage" src="{{ asset('storage/' . $product->image) }}" class="img-fluid"
                        alt="{{ $product->product_name }}">
                </div>
            </div>

            <div class="col-md-7">
                <h2 class="fw-bold product-title">{{ $product->product_name }}</h2>

                <div class="product-price">
                    <strong class="d-block text-muted mb-1">Giá:</strong>
                    <div id="priceBlock" class="d-flex flex-column align-items-start">
                        @if ($product->discount_price)
                            <span class="text-muted"><s>{{ number_format($product->price, 0, ',', '.') }} đ</s></span>
                            <span class="text-danger fw-bold">{{ number_format($product->discount_price, 0, ',', '.') }}
                                đ</span>
                        @else
                            <span class="text-danger fw-bold">{{ number_format($product->price, 0, ',', '.') }} đ</span>
                        @endif
                    </div>
                </div>

                <div class="mb-3">
                    <strong class="d-block mb-1">Số lượng còn:</strong>
                    <span id="stock" class="fs-6">{{ $product->quantity }}</span>
                </div>

                <table class="table table-bordered table-sm w-75">
                    <tbody>
                        <tr>
                            <th>RAM</th>
                            <td id="ram">-</td>
                        </tr>
                        <tr>
                            <th>Lưu trữ</th>
                            <td id="storage">-</td>
                        </tr>
                        <tr>
                            <th>Màu</th>
                            <td id="color">-</td>
                        </tr>
                    </tbody>
                </table>

                <h5 class="mt-4">Chọn phiên bản:</h5>
                <div class="d-flex flex-wrap gap-2">
                    @foreach ($product->variants as $variant)
                        <button type="button" class="btn btn-outline-secondary btn-sm variant-option"
                            data-id="{{ $variant->id }}"
                            data-image="{{ asset('storage/' . ($variant->image ?? $product->image)) }}"
                            data-price="{{ $variant->price }}" data-ram="{{ $variant->ram->value ?? '-' }}"
                            data-storage="{{ $variant->storage->value ?? '-' }}"
                            data-color="{{ $variant->color->value ?? '-' }}" data-quantity="{{ $variant->quantity }}">
                            {{ $variant->ram->value ?? '?' }} / {{ $variant->storage->value ?? '?' }} /
                            {{ $variant->color->value ?? '?' }}
                        </button>
                    @endforeach
                </div>

                <div class="mt-4 d-flex gap-3 align-items-end">
                    {{-- Thêm vào giỏ --}}
                    <form action="{{ route('cart.add') }}" method="POST" id="addToCartForm">
    @csrf
    <input type="hidden" name="product_id" value="{{ $product->id }}">
    <input type="hidden" name="product_variant_id" id="addToCartVariantId">
    
    <div class="input-group" style="max-width: 120px;">
        <button class="btn btn-outline-secondary" type="button" onclick="changeQty(-1)">-</button>
        <input type="number" name="quantity" id="quantityInput" value="1" min="1" class="form-control text-center">
        <button class="btn btn-outline-secondary" type="button" onclick="changeQty(1)">+</button>
    </div>

    <button type="submit" class="btn btn-primary mt-2">
        <i class="fa fa-cart-plus"></i> Thêm vào giỏ hàng
    </button>
</form>




                    {{-- Mua ngay --}}
                    <form action="{{ route('cart.buyNow') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="variant_id" id="selectedVariantId">
                        <input type="hidden" name="quantity" id="buyNowQuantity" value="1">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-bolt"></i> Mua ngay
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Mô tả & Đánh giá --}}
        <hr class="my-5">
        <h4 class="fw-bold mb-3">Mô tả chi tiết</h4>
        <div class="bg-light p-3 rounded">
            {!! $product->description ?? 'Đang cập nhật...' !!}
        </div>

        <hr class="my-5">
        <h4 class="fw-bold mb-3">Đánh giá & Bình luận</h4>

        @php $user = auth()->user(); @endphp

        @if ($user)
            <form action="" method="POST">
                @csrf
                <div class="mb-2">
                    <label for="rating" class="form-label">Đánh giá sao:</label>
                    <select name="rating" id="rating" class="form-select" required>
                        <option value="">Chọn sao</option>
                        @for ($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}">{{ $i }} sao</option>
                        @endfor
                    </select>
                </div>
                <div class="mb-2">
                    <label for="comment" class="form-label">Nội dung bình luận:</label>
                    <textarea name="comment" id="comment" rows="3" class="form-control" required></textarea>
                </div>
                <button type="submit" class="btn btn-success">Gửi đánh giá</button>
            </form>
        @else
            <p>Vui lòng <a href="{{ route('taikhoan.login') }}">đăng nhập</a> để đánh giá và bình luận.</p>
        @endif

        <div class="mt-4">
            <p>Chưa có đánh giá nào.</p>
        </div>

        @if ($relatedProducts->count())
            <hr class="my-5">
            <h4 class="fw-bold mb-4">Sản phẩm liên quan</h4>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
                @foreach ($relatedProducts as $item)
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm">
                            <a href="{{ route('product.show', $item->id) }}">
                                <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top"
                                    alt="{{ $item->product_name }}" style="height: 200px; object-fit: cover;">
                            </a>
                            <div class="card-body p-2">
                                <h6 class="card-title mb-1">
                                    <a href="{{ route('product.show', $item->id) }}"
                                        class="text-dark text-decoration-none">{{ $item->product_name }}</a>
                                </h6>
                                <p class="mb-0 text-danger fw-semibold">
                                    @if ($item->discount_price)
                                        {{ number_format($item->discount_price, 0, ',', '.') }} đ
                                        <small class="text-muted text-decoration-line-through d-block">
                                            {{ number_format($item->price, 0, ',', '.') }} đ
                                        </small>
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
    </div>
@endsection
@push('scripts')
<script>
function changeQty(change) {
    const input = document.getElementById('quantityInput');
    let value = parseInt(input.value) || 1;
    const max = parseInt(input.max);
    value += change;
    if (value < 1) value = 1;
    if (value > max) value = max;
    input.value = value;
    document.getElementById('buyNowQuantity').value = value;
}

document.addEventListener('DOMContentLoaded', () => {
    const variantButtons = document.querySelectorAll('.variant-option');
    const selectedVariantInput = document.getElementById('selectedVariantId');
    const addToCartVariantInput = document.getElementById('addToCartVariantId');
    const buyNowForm = document.querySelector('form[action="{{ route('cart.buyNow') }}"]');
    const addToCartForm = document.getElementById('addToCartForm');

    variantButtons.forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('mainImage').src = this.dataset.image;
            const priceValue = parseInt(this.dataset.price || 0).toLocaleString('vi-VN') + ' đ';
            document.getElementById('priceBlock').innerHTML = `<span class="text-danger fw-bold">${priceValue}</span>`;
            document.getElementById('ram').innerText = this.dataset.ram || '-';
            document.getElementById('storage').innerText = this.dataset.storage || '-';
            document.getElementById('color').innerText = this.dataset.color || '-';
            document.getElementById('stock').innerText = this.dataset.quantity || '-';
            
            selectedVariantInput.value = this.dataset.id;
            addToCartVariantInput.value = this.dataset.id;

            variantButtons.forEach(btn => btn.classList.remove('active', 'btn-primary'));
            this.classList.add('active', 'btn-primary');
        });
    });

    buyNowForm.addEventListener('submit', function(e) {
        if (!selectedVariantInput.value) {
            e.preventDefault();
            alert('Vui lòng chọn phiên bản trước khi mua ngay.');
        }
    });

    addToCartForm.addEventListener('submit', function(e) {
        if (!addToCartVariantInput.value) {
            e.preventDefault();
            alert('Vui lòng chọn phiên bản trước khi thêm vào giỏ hàng.');
        }
    });
});
</script>
@endpush
