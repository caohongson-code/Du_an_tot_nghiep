@extends('client.layouts.app')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@section('content')
    <section id="sectionBannerHome" class="hidden-xs clearfix">
        <div class="sliderMobileBannerHome wrapper-new styleAnimteBanner styleArrowSlick">
            <div class="col-xs-12 col-sm-4 trackingBannerHome2" data-number="1">
                <div class="animateBanner marginTopbanner">
                    <a href="#">
                        <img loading="lazy" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTLs3myrDMvH0egv4vobTrzgc0zS5W_PWs9iQ&s" alt="Điện thoại iPhone 15 Pro Max">
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
                        <img loading="lazy" src="https://admin.vdo.vn/Content/Upload/News-upload/3010_SamsungS24.jpg" alt="Điện thoại Samsung Galaxy S24 Ultra">
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
                        <img loading="lazy" src="https://lh7-rt.googleusercontent.com/docsz/AD_4nXfulyOwRID_BEcQ_xns0s7k4jQkp9lidGKjcTNzI4fyJERtanJYyXijGeTZU6y4fXpm_w9rjrinXIbPL-b9NbQPer2NMB7pArCqVh8UjCF44NelqGhrQLbUuTEpErv1WQ?key=dsvps7X0BpJfsdaByEA69mnX" alt="Điện thoại Xiaomi Redmi Note 13 Pro">
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
                    <img loading="lazy" src="https://cdni.dienthoaivui.com.vn/x,webp,q100/https://dashboard.dienthoaivui.com.vn/uploads/wp-content/uploads/images/may-cu-dien-thoai-cu-01.png" alt="Ưu đãi sốc">
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
                                <img loading="lazy" src="https://image.fili.vn/2023/09/13/apple.png" alt="iPhone 15 chính thức ra mắt">
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
                                <img loading="lazy" src="data:image/jpeg;base64,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" alt="Samsung ra mắt Galaxy S25 Ultra">
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
                                <img loading="lazy"src="https://cdn.mobilecity.vn/mobilecity-vn/images/2024/10/so-sanh-xiaomi-redmi-note-14-pro-plus-vs-realme-gt-neo-6.jpg.webp" alt="So sánh Xiaomi và Realme">
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
                                <img loading="lazy" src="https://news.khangz.com/wp-content/uploads/2025/01/realme-gia-5-trieu-1.jpg" alt="Top điện thoại giá rẻ">
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
