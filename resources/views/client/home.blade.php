@extends('client.layouts.app')

@section('content')

<section class="el2-trending-products bg-white overflow-hidden pt-115">
    <div class="container-1440">
        <div class="row align-items-center">
            <div class="col-lg-6 wow fadeInUp mb-4" style="visibility: visible; animation-name: fadeInUp;">
                <div class="el2-section-title text-center text-lg-start">
                    <span class="el2-section-subtitle">Khuyến mãi lớn</span>
                    <h2 class="fw-semibold">Sản phẩm nổi bật</h2>
                </div>
            </div>
        </div>

        <div class="row">
            @foreach ($products as $product)
                <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp mb-4" data-wow-delay=".3s">
                    <div class="el2-product-card position-relative">

                        {{-- Badge giảm giá --}}
                        @if ($product->discount_price)
                            <span class="el2-offer-badge">
                                -{{ round(100 - ($product->discount_price / $product->price) * 100) }}%
                            </span>
                        @endif

                        {{-- Hình ảnh --}}
                        <div class="feature-thumbnail">
                            <a href="{{ route('product.show', $product->id) }}">
                                <img loading="lazy" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}" class="img-fluid" style="height: 200px; object-fit: cover;">
                            </a>
                        </div>

                        {{-- Nội dung --}}
                        <div class="el2-product-content">
                            @if($product->category)
                                <span class="categories">
                                    <a href="#">{{ $product->category->name }}</a>
                                </span>
                            @endif
                            <a href="{{ route('product.show', $product->id) }}" class="el2-product-title">
                                {{ $product->product_name }}
                            </a>
                            <div class="el2-product-rating">
                                <div class="star-rating">
                                    <span style="width:{{ ($product->rating ?? 0) * 20 }}%">
                                        <strong class="rating">{{ $product->rating ?? 0 }}</strong> out of 5
                                    </span>
                                </div>
                            </div>
                            <div class="el2-stock-status mt-3">
                                <p class="mb-2">Có sẵn: <strong>{{ $product->stock ?? '' }}</strong></p>
                                <div class="stock">
                                    <span class="available" style="width: 91%;"></span>
                                </div>
                            </div>
                            <p class="mb-0 el2-product-price mt-3">
                                @if ($product->discount_price)
                                    <del><span class="woocommerce-Price-amount amount">
                                        <bdi>{{ number_format($product->price, 0, ',', '.') }} ₫</bdi>
                                    </span></del>
                                    <ins><span class="woocommerce-Price-amount amount">
                                        <bdi>{{ number_format($product->discount_price, 0, ',', '.') }} ₫</bdi>
                                    </span></ins>
                                @else
                                    <span class="woocommerce-Price-amount amount">
                                        <bdi>{{ number_format($product->price, 0, ',', '.') }} ₫</bdi>
                                    </span>
                                @endif
                            </p>
                        </div>

                        {{-- Quick view + compare --}}
                        <div class="el2-product-action-btns">
                            <a href="#" class="xpc-quick-view action-btn" data-product_id="{{ $product->id }}">
                                <span class="xpc-quick-view-text product__quick_view_text">Quick View</span>
                                <i class="ele-icon electio-search product__quick_view_icon"></i>
                            </a>
                            <div class="woocommerce product compare-button woosc-compare-button">
                                <a href="#" class="compare button woosc-btn woosc-btn-{{ $product->id }}" data-id="{{ $product->id }}" rel="nofollow"></a>
                            </div>
                        </div>

                        {{-- Thêm vào giỏ --}}
                        <div class="el2-product-bottom">
                            <div class="electio_product_style">
                                <a href="#" class="add-to-cart-btn electio-add-to-cart el2-product-cart-btn" data-product_id="{{ $product->id }}" data-name="{{ $product->product_name }}" data-img="{{ asset('storage/' . $product->image) }}">
                                    Thêm vào giỏ hàng
                                    <i class="ele-icon electio-shopping-bag"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Nút xem thêm --}}
        <div class="text-center mt-4">
            <a href="{{ route('product.all') }}" class="btn btn-outline-danger">
                Xem thêm
            </a>
        </div>
    </div>
</section>

@endsection
