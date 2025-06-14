@extends('client.layouts.app')

@section('content')
<div class="container-fluid my-5">
    <div class="sectionTitleTab clearfix mb-4">
        <h2><a class="tp_title" href="#">Sản phẩm bán chạy</a></h2>
    </div>

    <div class="row product-lists-home">
        @foreach($products as $product)
            <div class="product-resize col-6 col-sm-4 col-md-3 mb-4">
                <div class="product-block position-relative">

                    {{-- Hình ảnh sản phẩm --}}
                    <div class="product-img image-resize">
                        <a href="{{ route('product.show', $product->id) }}" class="p-img-box added d-block position-relative">
                            <picture>
                                <source media="(max-width: 767px)" srcset="{{ asset('storage/' . $product->image) }}">
                                <source media="(min-width: 768px)" srcset="{{ asset('storage/' . $product->image) }}">
                                <img 
                                    loading="lazy"
                                    class="img-loop w-100 rounded"
                                    src="{{ asset('storage/' . $product->image) }}"
                                    alt="{{ $product->product_name }}"
                                    style="height: 280px; object-fit: cover;"
                                >
                            </picture>
                            @if($product->hover_image)
                                <picture>
                                    <img loading="lazy"
                                         class="img-loop img-hover position-absolute top-0 start-0 w-100 h-100 rounded"
                                         src="{{ asset('storage/' . $product->hover_image) }}"
                                         alt="{{ $product->product_name }}"
                                         style="opacity: 0; transition: .3s;"
                                         onmouseover="this.style.opacity='1'"
                                         onmouseout="this.style.opacity='0'">
                                </picture>
                            @endif
                        </a>
                    </div>

                    {{-- Thông tin sản phẩm --}}
                    <div class="product-info mt-2">
                        <h3 class="pro-name mb-1">
                            <a href="{{ route('product.show', $product->id) }}" class="tp_product_name fw-bold text-dark">
                                {{ $product->product_name }}
                            </a>
                        </h3>
                        <div class="box-pro-prices mb-1">
                            <p class="pro-price highlight tp_product_price mb-0">
                                @if($product->discount_price)
                                    <span class="priceSale text-danger fw-bold">
                                        {{ number_format($product->discount_price, 0, ',', '.') }}₫
                                    </span>
                                    <span class="pro-price-del d-block">
                                        <del class="compare-price p-discout text-muted">
                                            {{ number_format($product->price, 0, ',', '.') }}₫
                                        </del>
                                    </span>
                                @else
                                    <span class="priceSale text-dark fw-bold">
                                        {{ number_format($product->price, 0, ',', '.') }}₫
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- Nhãn giảm giá --}}
                    @if($product->discount_price)
                        <div class="frameSale position-absolute top-0 start-0">
                            <div class="activeLabelStatus icpercent bg-danger text-white px-2 py-1 small">
                                Giảm {{ round(100 - ($product->discount_price / $product->price) * 100) }}%
                            </div>
                        </div>
                    @endif

                    {{-- Nút hành động --}}
                    <div class="actionLoop mt-2 d-flex justify-content-between">
                        <a class="quickView styleBtnBuy btn btn-sm btn-outline-primary" href="#">
                            <i class="fa fa-shopping-cart"></i> Mua nhanh
                        </a>
                        <a class="styleBtnBuy btn btn-sm btn-primary" href="{{ route('product.show', $product->id) }}">
                            <i class="fa fa-eye"></i> Xem chi tiết
                        </a>
                    </div>

                </div>
            </div>
        @endforeach
    </div>

    {{-- Phân trang --}}
    <div class="pagination-wrapper mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection
