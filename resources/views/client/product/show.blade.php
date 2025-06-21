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
                <h2 class="fw-bold">{{ $product->product_name }}</h2>

                {{-- Giá --}}
                <p>
                    <strong>Giá: </strong>
                    <span id="priceBlock">
                        @if ($product->discount_price)
                            <span class="text-muted"><s>{{ number_format($product->price, 0, ',', '.') }} đ</s></span>
                            <span
                                class="text-danger fw-bold ms-2">{{ number_format($product->discount_price, 0, ',', '.') }}
                                đ</span>
                        @else
                            <span class="text-danger fw-bold">{{ number_format($product->price, 0, ',', '.') }} đ</span>
                        @endif
                    </span>
                </p>

                <p><strong>Số lượng còn:</strong> <span id="stock">{{ $product->quantity }}</span></p>

                {{-- Thông số kỹ thuật --}}
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

                {{-- Chọn biến thể --}}
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

                {{-- Số lượng và hành động --}}
                <div class="mt-4 d-flex gap-3 align-items-end">
                    <form action="" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="quantityInput" class="form-label d-block">Số lượng:</label>
                            <div class="d-flex align-items-center gap-2" style="max-width: 200px;">
                                <button class="btn btn-outline-secondary" type="button" onclick="changeQty(-1)">-</button>
                                <input type="number" name="quantity" id="quantityInput" value="1" min="1"
                                    max="{{ $product->quantity }}" class="form-control text-center px-1"
                                    style="width: 60px;">
                                <button class="btn btn-outline-secondary" type="button" onclick="changeQty(1)">+</button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-outline-primary w-100 mt-1">
                            <i class="fa fa-cart-plus"></i> Thêm vào giỏ hàng
                        </button>
                    </form>

                    <form action="" method="POST">
                        @csrf
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-bolt"></i> Mua ngay
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Mô tả sản phẩm --}}
        <hr class="my-5">
        <h4 class="fw-bold mb-3">Mô tả chi tiết</h4>
        <div class="bg-light p-3 rounded">
            {!! $product->description ?? 'Đang cập nhật...' !!}
        </div>

        {{-- Đánh giá & bình luận --}}
        <hr class="my-5">
        <h4 class="fw-bold mb-3">Đánh giá & Bình luận</h4>

        @if (auth()->check())
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
            <p>Vui lòng <a href="">đăng nhập</a> để đánh giá và bình luận.</p>
        @endif

      <div class="mt-4">
    @forelse($comments as $comment)
        <div class="border-bottom pb-2 mb-2">
            <strong>{{ $comment->user->full_name ?? 'Ẩn danh' }}</strong> –
            <div>{{ $comment->content }}</div>
            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
        </div>
    @empty
        <p>Chưa có bình luận nào.</p>
    @endforelse
</div>


        {{-- Sản phẩm liên quan --}}
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
                                        <small
                                            class="text-muted text-decoration-line-through d-block">{{ number_format($item->price, 0, ',', '.') }}
                                            đ</small>
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
        }

        document.addEventListener('DOMContentLoaded', () => {
            const variantButtons = document.querySelectorAll('.variant-option');

            variantButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const image = this.dataset.image;
                    if (image) {
                        document.getElementById('mainImage').src = image;
                    }

                    const priceValue = parseInt(this.dataset.price || 0).toLocaleString('vi-VN') +
                        ' đ';
                    document.getElementById('priceBlock').innerHTML =
                        `<span class="text-danger fw-bold">${priceValue}</span>`;

                    document.getElementById('ram').innerText = this.dataset.ram || '-';
                    document.getElementById('storage').innerText = this.dataset.storage || '-';
                    document.getElementById('color').innerText = this.dataset.color || '-';
                    document.getElementById('stock').innerText = this.dataset.quantity || '-';

                    variantButtons.forEach(btn => btn.classList.remove('active', 'btn-primary'));
                    this.classList.add('active', 'btn-primary');
                });
            });
        });
    </script>
@endpush
