@extends('client.layouts.app')

@section('content')
<style>
/* Ảnh chính */
.product-main-image-wrapper {
    width: 100%;
    height: 500px;
    border: 1px solid #ddd;
    border-radius: 12px;
    background: #fff;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px;
    margin-bottom: 15px;
}

.product-main-image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    object-position: center;
}

/* Album ảnh nhỏ */
#albumWrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.variant-album-img-wrapper {
    width: 70px;
    height: 70px;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    cursor: pointer;
    transition: transform 0.3s;
    background-color: #fff;
}

.variant-album-img-wrapper:hover {
    transform: scale(1.05);
}

.variant-album-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}
</style>

<div class="container my-5">
    <div class="row g-4">
        <!-- CỘT ẢNH SẢN PHẨM -->
        <div class="col-md-5">
            <!-- Ảnh chính -->
            <div class="product-main-image-wrapper">
                <img id="mainImage" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}">
            </div>

            <!-- Album ảnh -->
            <div id="albumWrapper">
                <!-- Ảnh đại diện sản phẩm -->
                <div class="variant-album-img-wrapper">
                    <img src="{{ asset('storage/' . $product->image) }}" class="variant-album-img" data-image="{{ asset('storage/' . $product->image) }}">
                </div>

                <!-- Lấy 1 ảnh chính cho mỗi màu duy nhất -->
                @php
                    $colorShown = [];
                @endphp
                @foreach ($product->variants as $variant)
                    @if ($variant->image && !in_array($variant->color_id, $colorShown))
                        <div class="variant-album-img-wrapper">
                            <img src="{{ asset('storage/' . $variant->image) }}" class="variant-album-img" data-image="{{ asset('storage/' . $variant->image) }}">
                        </div>
                        @php $colorShown[] = $variant->color_id; @endphp
                    @endif
                @endforeach
            </div>
        </div>

        <!-- CỘT THÔNG TIN SẢN PHẨM -->
        <div class="col-md-7">
            <h2 class="fw-bold">{{ $product->product_name }}</h2>

            <div class="product-price mb-3">
                @if ($product->discount_price)
                    <div class="text-muted"><s>{{ number_format($product->price, 0, ',', '.') }} đ</s></div>
                    <div class="text-danger fw-bold">{{ number_format($product->discount_price, 0, ',', '.') }} đ</div>
                @else
                    <div class="text-danger fw-bold">{{ number_format($product->price, 0, ',', '.') }} đ</div>
                @endif
            </div>

            <div class="mb-2">
                <strong>Số lượng còn:</strong>
                <span id="stock">{{ $product->quantity }}</span>
            </div>

            <table class="table table-bordered table-sm w-75 mb-3">
                <tr><th>RAM</th><td id="ram">-</td></tr>
                <tr><th>Lưu trữ</th><td id="storage">-</td></tr>
                <tr><th>Màu</th><td id="color">-</td></tr>
            </table>

            <h5 class="mt-3">Chọn phiên bản:</h5>
            <div class="d-flex flex-wrap gap-2 mb-4">
                @foreach ($product->variants as $variant)
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

            <div class="d-flex flex-wrap gap-3">
                <form action="{{ route('cart.add') }}" method="POST" id="addToCartForm" class="d-flex flex-column gap-2">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="product_variant_id" id="addToCartVariantId">
                    <div class="input-group" style="max-width: 150px;">
                        <button class="btn btn-outline-secondary" type="button" onclick="changeQty(-1)">-</button>
                        <input type="number" name="quantity" id="quantityInput" value="1" min="1" class="form-control text-center">
                        <button class="btn btn-outline-secondary" type="button" onclick="changeQty(1)">+</button>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-cart-plus"></i> Thêm vào giỏ
                    </button>
                </form>

                <form action="{{ route('cart.buyNow') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="variant_id" id="selectedVariantId">
                    <input type="hidden" name="quantity" id="buyNowQuantity" value="1">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-bolt"></i> Mua ngay
                    </button>
                </form>
            </div>
        </div>
    </div>

    <hr class="my-5">
    <h4 class="fw-bold mb-3">Mô tả chi tiết</h4>
    <div class="bg-light p-3 rounded" style="white-space: pre-wrap;">
        {!! $product->description ?? 'Đang cập nhật...' !!}
    </div>

    <hr class="my-5">
    <h4 class="fw-bold mb-3">Đánh giá & Bình luận</h4>
    @auth
        <form method="POST">
            @csrf
            <div class="mb-2">
                <label class="form-label">Đánh giá sao:</label>
                <select name="rating" class="form-select" required>
                    <option value="">Chọn sao</option>
                    @for ($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}">{{ $i }} sao</option>
                    @endfor
                </select>
            </div>
            <div class="mb-2">
                <label class="form-label">Nội dung bình luận:</label>
                <textarea name="comment" rows="3" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-success">Gửi đánh giá</button>
        </form>
    @else
        <p>Vui lòng <a href="{{ route('taikhoan.login') }}">đăng nhập</a> để đánh giá.</p>
    @endauth

    <div class="mt-4">
        <p>Chưa có đánh giá nào.</p>
    </div>

    @if ($relatedProducts->count())
        <hr class="my-5">
        <h4 class="fw-bold mb-4">Sản phẩm liên quan</h4>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
            @foreach ($relatedProducts as $item)
                <div class="col">
                    <div class="card h-100 shadow-sm border-0">
                        <a href="{{ route('product.show', $item->id) }}">
                            <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $item->product_name }}">
                        </a>
                        <div class="card-body p-2">
                            <h6 class="card-title mb-1">
                                <a href="{{ route('product.show', $item->id) }}" class="text-dark text-decoration-none">{{ $item->product_name }}</a>
                            </h6>
                            <p class="mb-0 text-danger fw-semibold">
                                @if ($item->discount_price)
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
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const mainImage = document.getElementById('mainImage');
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('variant-album-img')) {
            mainImage.src = e.target.dataset.image;
        }
    });

    const variantButtons = document.querySelectorAll('.variant-option');
    const selectedVariantInput = document.getElementById('selectedVariantId');
    const addToCartVariantInput = document.getElementById('addToCartVariantId');
    const priceBlock = document.querySelector('.product-price');
    const ram = document.getElementById('ram');
    const storage = document.getElementById('storage');
    const color = document.getElementById('color');
    const stock = document.getElementById('stock');

    variantButtons.forEach(button => {
        button.addEventListener('click', function() {
            const variantId = this.dataset.id;
            mainImage.src = this.dataset.image;
            priceBlock.innerHTML = `<div class="text-danger fw-bold">${parseInt(this.dataset.price || 0).toLocaleString('vi-VN')} đ</div>`;
            ram.innerText = this.dataset.ram || '-';
            storage.innerText = this.dataset.storage || '-';
            color.innerText = this.dataset.color || '-';
            stock.innerText = this.dataset.quantity || '-';
            selectedVariantInput.value = variantId;
            addToCartVariantInput.value = variantId;

            variantButtons.forEach(btn => btn.classList.remove('active', 'btn-primary'));
            this.classList.add('active', 'btn-primary');
        });
    });
});

function changeQty(change) {
    const input = document.getElementById('quantityInput');
    let value = parseInt(input.value) || 1;
    value += change;
    if (value < 1) value = 1;
    input.value = value;
    document.getElementById('buyNowQuantity').value = value;
}
</script>
@endpush
