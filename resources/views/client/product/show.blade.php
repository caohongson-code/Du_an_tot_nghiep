@extends('client.layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Tổng thể container sản phẩm */
        .container.my-5 {
            background: #fff;
            padding: 32px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
            font-family: 'Segoe UI', sans-serif;
        }

        /* Ảnh sản phẩm chính */

        #mainImageWrapper {
            width: 100%;
            height: 420px;
            overflow: hidden;
            border-radius: 12px;

            border: 1px solid #e0e0e0;
            box-shadow: inset 0 0 4px rgba(0, 0, 0, 0.05);

        }

        #mainImage {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        #mainImageWrapper:hover #mainImage {
            transform: scale(1.05);

        }

        /* Tiêu đề sản phẩm */
        .product-title {

            margin-bottom: 16px;
            font-size: 24px;
            font-weight: 600;
            color: #1a1a1a;

        }

        /* Giá sản phẩm */
        .product-price {
            margin-bottom: 16px;
            font-size: 20px;
            color: #d70018;
        }

        .product-price s {
            color: #999;
            font-size: 16px;

        }

        /* Bảng thông số kỹ thuật */
        .table.table-sm th {
            width: 100px;
            background: #f8f9fa;
            font-weight: 500;
        }

        .table.table-sm td {
            background: #fff;
        }

        .table.table-sm {
            border-radius: 8px;
            overflow: hidden;
        }

        /* Phiên bản sản phẩm dạng chip */
        .variant-option {
            padding: 8px 14px;
            border-radius: 20px;
            border: 1px solid #ccc;
            background-color: #f8f9fa;
            font-size: 14px;
            transition: all 0.3s;
            min-width: 80px;
            text-align: center;
            cursor: pointer;
        }

        .variant-option:hover {
            background-color: #e0f0ff;
            border-color: #1a73e8;
            color: #1a73e8;
        }

        .variant-option.active {
            background-color: #1a73e8;
            color: #fff;
            border-color: #1a73e8;
        }

        /* Nút số lượng */
        .input-group input {
            font-size: 16px;
            font-weight: 600;
        }

        /* Nút hành động chính */
        button.btn-primary,
        button.btn-success {
            padding: 12px 24px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            min-width: 160px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
        }

        .btn-primary {
            background-color: #d70018;
            border: none;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #b30014;
        }

        .btn-success {
            background-color: #28a745;
            border: none;
            color: #fff;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        /* Phần mô tả */
        .bg-light.p-3 {
            background-color: #f8f9fa !important;
            border-left: 4px solid #1a73e8;
            padding: 20px;
            line-height: 1.6;
            border-radius: 8px;
        }

        /* Form đánh giá */
        form select.form-select,
        form textarea.form-control {
            border-radius: 8px;
            font-size: 14px;
        }

        /* Card sản phẩm liên quan */
        .card.h-100 {
            transition: 0.3s ease;
            border-radius: 12px;
        }

        .card.h-100:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        .card-img-top {
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            transition: 0.3s;
        }

        .card-img-top:hover {
            transform: scale(1.03);
        }

        .card-title a {
            font-size: 15px;
            font-weight: 500;
            color: #000;
            text-decoration: none;
        }

        /* Banner khuyến mãi cố định */
        .promo-fixed {
            position: fixed;
            right: 20px;
            bottom: 100px;
            width: 240px;
            background: linear-gradient(135deg, #fff9d6, #ffe8b3);
            border: 2px solid #ffc107;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            padding: 16px;
            z-index: 9999;
            animation: fadeInUp 0.8s ease;
        }

        .promo-fixed .promo-content {
            font-family: 'Segoe UI', sans-serif;
        }

        .promo-fixed h6 {
            margin: 8px 0;
            font-size: 18px;
        }

        .promo-fixed .btn-warning {
            border-radius: 8px;
        }

        @keyframes fadeInUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Responsive cho mobile */
        @media (max-width: 768px) {
            .product-title {
                font-size: 20px;
            }

            .product-price {
                font-size: 18px;
            }

            .variant-option {
                font-size: 13px;
                padding: 6px 12px;
                min-width: 70px;
            }

            .btn-primary,
            .btn-success {
                width: 100%;
            }

            .promo-fixed {
                display: none;
            }
        }

        #albumWrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }

        .variant-album-img-wrapper {
            width: 70px;
            height: 70px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            display: none;
            transition: transform 0.3s;
        }

        .variant-album-img-wrapper:hover {
            transform: scale(1.05);
        }

        .variant-album-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .promo-fixed {
            position: fixed;
            width: 200px;
            /* Chiều rộng thống nhất */
            height: 300px;
            /* Chiều cao thống nhất */
            z-index: 9999;
            padding: 0;
            background: none;
            border: none;
            box-shadow: none;
        }

        .promo-left {
            left: 20px;
            bottom: 100px;
        }

        .promo-right {
            right: 20px;
            bottom: 100px;
        }

        .promo-fixed img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Cắt đều, không méo, tràn khung */
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s;
        }

        .promo-fixed img:hover {
            transform: scale(1.05);
        }

        @media (max-width: 768px) {

            .promo-left,
            .promo-right {
                display: none;
            }
        }
        .product-price-main {
            font-size: 30px;
            color: #e11d48;
            font-weight: 670;
            display: inline-block;
            margin-right: 8px;
        }
        .product-price-old {
            font-size: 17px;
            color: #888;
            text-decoration: line-through;
            display: inline-block;
            margin-left: 2px;
            vertical-align: middle;
        }
    </style>

    <div class="container my-5">
        <div class="row g-4">
            <div class="col-md-5">
                <div id="mainImageWrapper">
                    <img id="mainImage" src="{{ asset('storage/' . $product->image) }}" class="img-fluid"
                        alt="{{ $product->product_name }}">
                </div>

                <div id="albumWrapper" class="d-flex flex-wrap gap-2 mt-3">
                    @foreach ($product->variants as $variant)
                        @foreach ($variant->images as $img)
                            <div class="variant-album-img-wrapper" data-variant="{{ $variant->id }}"
                                style="display: none;">
                                <img src="{{ asset('storage/' . $img->image) }}" alt="Ảnh phụ" class="variant-album-img"
                                    data-image="{{ asset('storage/' . $img->image) }}">
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>



            <div class="col-md-7">
                <h2 class="fw-bold product-title">{{ $product->product_name }}</h2>

                <div class="product-price">
                    <strong class="d-block text-muted mb-1">Giá:</strong>
                    <div id="priceBlock" class="d-flex flex-column align-items-start">
                        @if ($product->discount_price)
                            <span class="product-price-main">{{ number_format($product->discount_price, 0, ',', '.') }} đ</span>
                            <span class="product-price-old">{{ number_format($product->price, 0, ',', '.') }} đ</span>
                        @else
                            <span class="product-price-main">{{ number_format($product->price, 0, ',', '.') }} đ</span>
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
                    <form action="{{ route('cart.add') }}" method="POST" id="addToCartForm">

                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="product_variant_id" id="addToCartVariantId">
                        <div class="input-group" ;>
                            <button class="btn btn-outline-secondary" type="button" onclick="changeQty(-1)">-</button>
                            <input type="number" name="quantity" id="quantityInput" value="1" min="1" max="{{ $product->quantity }}"
                                class="form-control text-center">
                            <button class="btn btn-outline-secondary" type="button" onclick="changeQty(1)">+</button>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">
                            <i class="fa fa-cart-plus"></i> Thêm vào giỏ hàng
                        </button>
                    </form>


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

        <hr class="my-5">
        <h4 class="fw-bold mb-3">Mô tả chi tiết</h4>
        <pre class="bg-light p-3 rounded" style="white-space: pre-wrap; font-family: inherit;">
    {!! $product->description ?? 'Đang cập nhật...' !!}
</pre>


{{-- Form bình luận chung --}}
@if (Auth::check())
    <div class="card my-4">
        <div class="card-header bg-secondary text-white">
            <strong>Viết bình luận</strong>
        </div>
        <div class="card-body">
            <form action="{{ route('client.comments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="is_review" value="0"> {{-- đánh dấu là bình luận thường --}}

                <div class="mb-3">
                    <textarea name="content" class="form-control" rows="3" required placeholder="Viết bình luận..."></textarea>
                </div>

                <button type="submit" class="btn btn-secondary">Gửi bình luận</button>
            </form>
        </div>
    </div>
@else
    <p class="text-muted mt-3">Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để bình luận.</p>
@endif
<h5 class="mt-4">Bình luận</h5>
@php
    $comments = $product->comments->where('is_review', 0)->whereNull('parent_id');
@endphp

@forelse($comments as $comment)
    <div class="border rounded p-3 my-3">
        <div class="d-flex justify-content-between">
            <strong>{{ $comment->user->full_name ?? 'Ẩn danh' }}</strong>
            <small class="text-muted">{{ $comment->created_at->format('d/m/Y H:i') }}</small>
        </div>
        <p class="mb-1 mt-2">{{ $comment->content }}</p>

        {{-- Trả lời từ admin nếu có --}}
        @foreach ($comment->replies as $reply)
            <div class="mt-3 ms-4 p-2 bg-light border-start border-4 border-secondary">
                <div class="d-flex justify-content-between">
                    <strong>{{ $reply->user->full_name ?? 'Admin' }}</strong>
                    <small class="text-muted">{{ $reply->created_at->format('d/m/Y H:i') }}</small>
                </div>
                <p class="mb-1 mt-2">{{ $reply->content }}</p>
            </div>
        @endforeach
    </div>
@empty
    <p>Chưa có bình luận nào.</p>
@endforelse
<h5 class="mt-5">Đánh giá sản phẩm - khách hàng</h5>

@forelse ($product->reviews as $review)
    <div class="border rounded p-3 my-3">
        <div class="d-flex justify-content-between">
            <div>
                <strong>{{ $review->user->full_name ?? 'Khách hàng' }}</strong>
                <span class="text-warning">{{ str_repeat('⭐', $review->rating ?? 0) }}</span>
            </div>
            <small class="text-muted">{{ $review->created_at->format('d/m/Y H:i') }}</small>
        </div>

        @if ($review->image)
            <div class="mt-2">
                <img src="{{ asset('storage/' . $review->image) }}" alt="Ảnh đánh giá" style="max-width: 150px;">
            </div>
        @endif

        <p class="mt-2 mb-1">{{ $review->comment }}</p>
    </div>
@empty
    <p>Chưa có đánh giá nào.</p>
@endforelse


<h5 class="mt-4"></h5>
@php
    $reviews = $product->comments->where('is_review', 1)->whereNull('parent_id');
@endphp

@forelse($reviews as $review)
    <div class="border rounded p-3 my-3">
        <div class="d-flex justify-content-between">
            <strong>{{ $review->user->full_name ?? 'Ẩn danh' }}</strong>
            <small class="text-muted">{{ $review->created_at->format('d/m/Y H:i') }}</small>
        </div>
        <div class="text-warning">
            {{ str_repeat('⭐', $review->rating) }}
        </div>
        <p class="mb-1 mt-2">{{ $review->content }}</p>
    </div>
@empty
    <p></p>
@endforelse










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

    {{-- Nội dung trang sản phẩm giữ nguyên như bạn đã có --}}

    <div class="container my-5">
        {{-- Nội dung chi tiết sản phẩm, mô tả, đánh giá, sản phẩm liên quan... --}}
        {{-- Mình không lặp lại để tránh quá dài, bạn giữ nguyên nội dung sản phẩm như trước --}}




        {{-- Nút gọi nhanh cố định --}}
        <div class="call-fixed">
            <a href="tel:0123456789" class="btn btn-success shadow">
                <i class="fa fa-phone" style="font-size: 24px; color: #fff;"></i>
            </a>
        </div>
        <!-- Banner bên trái -->
        <div class="promo-fixed promo-left">
            <a href="#">
                <img src="https://png.pngtree.com/template/20200517/ourlarge/pngtree-summer-sale-banner-promotion-template-in-portrait-position-with-bright-design-image_372761.jpg"
                    alt="Summer Sale">
            </a>
        </div>

        <!-- Banner bên phải -->
        <div class="promo-fixed promo-right">
            <a href="#">
                <img src="https://img.pikbest.com/origin/09/06/37/13NpIkbEsTGT5.jpg!w700wp" alt="Flash Sale">
            </a>
        </div>

    @endsection

    @push('scripts')

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const variantButtons = document.querySelectorAll('.variant-option');
                const selectedVariantInput = document.getElementById('selectedVariantId');
                const addToCartVariantInput = document.getElementById('addToCartVariantId');
                const buyNowForm = document.querySelector('form[action="{{ route('cart.buyNow') }}"]');
                const addToCartForm = document.getElementById('addToCartForm');
                const albumImages = document.querySelectorAll('.variant-album-img-wrapper');
                const mainImage = document.getElementById('mainImage');

                variantButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const variantId = this.dataset.id;

                        // Cập nhật thông tin
                        mainImage.src = this.dataset.image;
                        document.getElementById('priceBlock').innerHTML =
                            `<span class="product-price-main">${parseInt(this.dataset.price || 0).toLocaleString('vi-VN')} đ</span>`;
                        document.getElementById('ram').innerText = this.dataset.ram || '-';
                        document.getElementById('storage').innerText = this.dataset.storage || '-';
                        document.getElementById('color').innerText = this.dataset.color || '-';
                        document.getElementById('stock').innerText = this.dataset.quantity || '-';
                        document.getElementById('quantityInput').max = this.dataset.quantity;
                        document.getElementById('quantityInput').value = 1;
                        document.getElementById('buyNowQuantity').value = 1;
                        selectedVariantInput.value = variantId;
                        addToCartVariantInput.value = variantId;

                        // Active nút
                        variantButtons.forEach(btn => btn.classList.remove('active', 'btn-primary'));
                        this.classList.add('active', 'btn-primary');

                        // Ẩn/hiện ảnh album đúng phiên bản
                        albumImages.forEach(img => {
                            img.style.display = (img.dataset.variant === variantId) ? 'block' :
                                'none';
                        });
                    });
                });

                // Click ảnh nhỏ -> đổi ảnh to
                document.addEventListener('click', function(e) {
                    if (e.target.classList.contains('variant-album-img')) {
                        mainImage.src = e.target.dataset.image;
                    }
                });

                // Validate khi mua ngay hoặc thêm giỏ hàng
                buyNowForm.addEventListener('submit', function(e) {
                    if (!selectedVariantInput.value) {
                        e.preventDefault();
                        alert('Vui lòng chọn phiên bản trước khi mua ngay.');
                    }
                });
                addToCartForm.addEventListener('submit', function(e) {
                    if (!addToCartVariantInput.value) {
                        e.preventDefault();
                        alert('Vui lòng chọn phiên bản trước khi thêm vào giỏ.');
                    }
                });
            });


            function changeQty(change) {
                const input = document.getElementById('quantityInput');
                let value = parseInt(input.value) || 1;
                const max = parseInt(input.max) || 9999; // fallback nếu max không hợp lệ
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
                        const priceValue = parseInt(this.dataset.price || 0).toLocaleString('vi-VN') +
                            ' đ';
                        document.getElementById('priceBlock').innerHTML =
                            `<span class="product-price-main">${priceValue}</span>`;
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

                // Thông báo nổi khi load trang
                setTimeout(() => {
                    alert('💡 Đăng ký tài khoản để nhận ngay voucher giảm giá hấp dẫn!');
                }, 3000);
            });
        </script>
    @endpush