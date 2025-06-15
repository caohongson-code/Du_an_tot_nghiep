@extends('client.layouts.app')

@section('content')
    <section id="sectionBannerHome" class="hidden-xs clearfix">
        <div class="sliderMobileBannerHome wrapper-new styleAnimteBanner styleArrowSlick">
            <div class="col-xs-12 col-sm-4 trackingBannerHome2" data-number="1">
                <div class="animateBanner marginTopbanner">
                    <a href="#">
                        <img loading="lazy" src="client/img/anh3.webp" alt="Điện thoại iPhone 15 Pro Max">
                    </a>
                </div>
                <div class="infoBannerHome">
                    <h2><a href="#">iPhone 16 Pro Max - Sang trọng, hiệu năng đỉnh</a></h2>
                    <a class="viewMore3Banner" href="#">XEM CHI TIẾT</a>
                </div>
            </div>

            <div class="col-xs-12 col-sm-4 trackingBannerHome2" data-number="2">
                <div class="animateBanner marginTopbanner">
                    <a href="#">
                        <img loading="lazy" src="client/img/anh4.webp" alt="Điện thoại Samsung Galaxy S24 Ultra">
                    </a>
                </div>
                <div class="infoBannerHome">
                    <h2><a href="#">Samsung Galaxy S24 Ultra - Chụp ảnh đỉnh cao</a></h2>
                    <a class="viewMore3Banner" href="#">XEM CHI TIẾT</a>
                </div>
            </div>

            <div class="col-xs-12 col-sm-4 trackingBannerHome2" data-number="3">
                <div class="animateBanner marginTopbanner">
                    <a href="#">
                        <img loading="lazy" src="client/img/anh1.jpg" alt="Điện thoại Xiaomi Redmi Note 13 Pro">
                    </a>
                </div>
                <div class="infoBannerHome">
                    <h2><a href="#">Xiaomi Redmi Note 1４ Pro - Hiệu năng vượt tầm giá</a></h2>
                    <a class="viewMore3Banner" href="#">XEM CHI TIẾT</a>
                </div>
            </div>
        </div>
    </section>
    <div>
    </div>
    </section>
    <section id="sectionHomeBannerProduct" class="clearfix">
        {{-- <div class="container-fluid"> --}}

        <div class="col-xs-12 col-sm-6 no-padding-l">
            <div class="innerLeftBannerProduct">
                <a href="javascript:void(0);" target="_self">
                    <img loading="lazy" src="client/img/anh1.webp" alt="Ưu đãi sốc">
                </a>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 no-padding-r">
            <div class="innerRightBannerProduct">
                <div class="sectionTitleBannerProduct animateText">
                    <h2>
                        <a href="#" class="tp_title">Công nghệ không thể bỏ lỡ</a>
                    </h2>
                    <p class="tp_title">Chiếc điện thoại là người bạn đồng hành không thể thiếu của mọi tín đồ hiện đại...
                    </p>
                </div>
            </div>
    </section>
    <div class="container-fluid my-5">
        <div class="sectionTitleTab clearfix mb-4">
            <h2><a class="tp_title" href="#">Sản phẩm bán chạy</a></h2>
        </div>
        <div class="row product-lists-home">
            @foreach ($products as $product)
                <div class="product-resize col-6 col-sm-4 col-md-3 mb-4">
                    <div class="product-block position-relative">
                        {{-- Hình ảnh sản phẩm --}}
                        <div class="product-img image-resize">
                            <a href="{{ route('product.show', $product->id) }}"
                                class="p-img-box added d-block position-relative">
                                <picture>
                                    <source media="(max-width: 767px)" srcset="{{ asset('storage/' . $product->image) }}">
                                    <source media="(min-width: 768px)" srcset="{{ asset('storage/' . $product->image) }}">
                                    <img loading="lazy" class="img-loop w-100 rounded"
                                        src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}"
                                        style="height: 280px; object-fit: cover;">
                                </picture>
                                @if ($product->hover_image)
                                    <picture>
                                        <img loading="lazy"
                                            class="img-loop img-hover position-absolute top-0 start-0 w-100 h-100 rounded"
                                            src="{{ asset('storage/' . $product->hover_image) }}"
                                            alt="{{ $product->product_name }}" style="opacity: 0; transition: .3s;"
                                            onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'">
                                    </picture>
                                @endif
                            </a>
                        </div>

                        {{-- Thông tin sản phẩm --}}
                        <div class="product-info mt-2">
                            <h3 class="pro-name mb-1">
                                <a href="{{ route('product.show', $product->id) }}"
                                    class="tp_product_name fw-bold text-dark">
                                    {{ $product->product_name }}
                                </a>
                            </h3>
                            <div class="box-pro-prices mb-1">
                                <p class="pro-price highlight tp_product_price mb-0">
                                    @if ($product->discount_price)
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
                        @if ($product->discount_price)
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
        <h2 class="lineHeight05"><a href="#" class="tp_title">Tin tức điện thoại</a></h2>
</div>

<div class="sectionContentTab styleArrowSlick">
    <div class="news-lists-home owl-carousel owl-theme owl-loaded">
        <div class="owl-stage-outer">
            <div class="owl-stage" style="transform: translate3d(0px, 0px, 0px); transition: all; width: 1405px;">
                
                <div class="owl-item active" style="width: 341.25px; margin-right: 10px;">
                    <div class="">
                        <div class="news-img">
                            <a href="#">
                                <img loading="lazy" src="client/img/anh3.jpg" alt="iPhone 15 chính thức ra mắt">
                            </a>
                        </div>
                        <div class="news-detail clearfix">
                            <div class="box-news-detail">
                                <h3 class="news-title tp_title">
                                    <a href="#">iPhone 15 chính thức ra mắt với chip A17 Bionic</a>
                                </h3>
                                <div class="news-desc">Apple ra mắt iPhone 15 với thiết kế mới, camera cải tiến và hiệu năng vượt trội nhờ chip A17 Bionic.</div>
                                <div class="news-btn">
                                    <a href="#" class="text-danger">Xem thêm</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="owl-item active" style="width: 341.25px; margin-right: 10px;">
                    <div class="">
                        <div class="news-img">
                            <a href="#">
                                <img loading="lazy" src="client/img/anh7.webp" alt="Samsung ra mắt Galaxy S25 Ultra">
                            </a>
                        </div>
                        <div class="news-detail clearfix">
                            <div class="box-news-detail">
                                <h3 class="news-title tp_title">
                                    <a href="#">Samsung Galaxy S25 Ultra lộ diện với camera 200MP</a>
                                </h3>
                                <div class="news-desc">Galaxy S25 Ultra hứa hẹn tạo nên đột phá với công nghệ chụp ảnh chuyên nghiệp và pin dung lượng lớn.</div>
                                <div class="news-btn">
                                    <a href="#" class="text-danger">Xem thêm</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="owl-item active" style="width: 341.25px; margin-right: 10px;">
                    <div class="">
                        <div class="news-img">
                            <a href="#">
                                <img loading="lazy"src="client/img/anh8.webp" alt="So sánh Xiaomi và Realme">
                            </a>
                        </div>
                        <div class="news-detail clearfix">
                            <div class="box-news-detail">
                                <h3 class="news-title tp_title">
                                    <a href="#">So sánh Xiaomi 14 và Realme GT6: Đâu là lựa chọn tốt?</a>
                                </h3>
                                <div class="news-desc">Hai mẫu flagship giá rẻ nổi bật nhất hiện nay: hiệu năng, camera và thời lượng pin – ai sẽ chiến thắng?</div>
                                <div class="news-btn">
                                    <a href="#" class="text-danger">Xem thêm</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="owl-item active" style="width: 341.25px; margin-right: 10px;">
                    <div class="">
                        <div class="news-img">
                            <a href="#">
                                <img loading="lazy" src="client/img/anh6.jpg" alt="Top điện thoại giá rẻ">
                            </a>
                        </div>
                        <div class="news-detail clearfix">
                            <div class="box-news-detail">
                                <h3 class="news-title tp_title">
                                    <a href="#">Top 5 điện thoại dưới 5 triệu đáng mua nhất 2025</a>
                                </h3>
                                <div class="news-desc">Danh sách những chiếc điện thoại giá rẻ nhưng vẫn mang lại hiệu năng mạnh mẽ và thiết kế đẹp mắt.</div>
                                <div class="news-btn">
                                    <a href="#" class="text-danger">Xem thêm</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="owl-controls">
            <div class="owl-nav">
                <div class="owl-prev" style="display: none;"><i class="fa fa-chevron-left"></i></div>
                <div class="owl-next" style="display: none;"><i class="fa fa-chevron-right"></i></div>
            </div>
            <div class="owl-dots" style="display: none;"></div>
        </div>
    </div>
</div>
        {{-- Phân trang --}}
        <div class="pagination-wrapper mt-4">
            {{ $products->links() }}
        </div>
    </div>
@endsection
