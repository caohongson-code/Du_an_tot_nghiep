@extends('client.layouts.app')

@section('content')
<div class="container py-5">
    <h3 class="mb-4">🛒 Xác nhận đơn hàng "Mua ngay"</h3>

    @if (session('buy_now'))
        <form method="POST" action="{{ route('checkout.store') }}">
            @csrf

            <div class="row">
                {{-- Thông tin người nhận --}}
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">📌 Thông tin người nhận</div>
                        <div class="card-body">
                            <p><strong>Họ tên:</strong> {{ Auth::user()->full_name }}</p>
                            <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                            <p><strong>Số điện thoại:</strong> {{ Auth::user()->phone ?? 'Chưa có' }}</p>
                            <p><strong>Địa chỉ:</strong> {{ Auth::user()->address ?? 'Chưa có' }}</p>
                            <a href="{{ route('user.profile.edit') }}" class="btn btn-sm btn-warning mt-2">✏️ Cập nhật thông tin</a>
                        </div>
                    </div>
                </div>

                {{-- Thông tin sản phẩm --}}
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">📦 Thông tin sản phẩm</div>
                        <div class="card-body">
                            <p><strong>Tên sản phẩm:</strong> {{ $product->name }}</p>
                            @if ($variant)
                                <p><strong>Phiên bản:</strong>
                                    {{ $variant->ram->value ?? '' }} /
                                    {{ $variant->storage->value ?? '' }} /
                                    {{ $variant->color->value ?? '' }}
                                </p>
                                @php $price = $variant->price; @endphp
                            @else
                                <p><strong>Phiên bản:</strong> Không chọn</p>
                                @php $price = $product->price; @endphp
                            @endif

                            <p><strong>Giá:</strong> {{ number_format($price, 0, ',', '.') }} VND</p>
                            <p><strong>Số lượng:</strong> {{ $buyNow['quantity'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Chọn voucher --}}
            @php
                $subtotal = $price * $buyNow['quantity'];
                $shippingFee = 30000;
            @endphp

            <div class="card mb-4">
                <div class="card-header bg-warning">🎁 Chọn voucher (nếu có)</div>
                <div class="card-body">
                    <select name="voucher_id" class="form-select" id="voucher-select">
                        <option value="" data-type="" data-value="0">-- Không sử dụng --</option>
                        @foreach ($vouchers as $voucher)
                            <option value="{{ $voucher->id }}"
                                data-type="{{ $voucher->discount_type }}"
                                data-value="{{ $voucher->discount_value }}">
                                {{ $voucher->name }} - Mã: {{ $voucher->code }} 
                                ({{ $voucher->discount_type == 'percent' ? $voucher->discount_value . '%' : number_format($voucher->discount_value, 0, ',', '.') . ' VND' }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Tổng tiền --}}
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">💰 Tổng tiền</div>
                <div class="card-body">
                    <p><strong>Tạm tính:</strong> <span id="subtotal">{{ number_format($subtotal, 0, ',', '.') }}</span> VND</p>
                    <p><strong>Phí vận chuyển:</strong> <span id="shipping">{{ number_format($shippingFee, 0, ',', '.') }}</span> VND</p>
                    <p><strong>Giảm giá:</strong> <span id="discount">0</span></p>
                    <hr>
                    <h5><strong>Thanh toán:</strong> <span id="total">{{ number_format($subtotal + $shippingFee, 0, ',', '.') }}</span> VND</h5>
                </div>
            </div>

            {{-- Phương thức thanh toán --}}
            <div class="card mb-4">
                <div class="card-header bg-info text-white">💳 Phương thức thanh toán</div>
                <div class="card-body">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" value="cod" id="cod" {{ old('payment_method') == 'cod' ? 'checked' : '' }}>
                        <label class="form-check-label" for="cod">Thanh toán khi nhận hàng (COD)</label>
                    </div>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="radio" name="payment_method" value="bank" id="bank" {{ old('payment_method') == 'bank' ? 'checked' : '' }}>
                        <label class="form-check-label" for="bank">Chuyển khoản ngân hàng</label>
                    </div>
                </div>
            </div>

            {{-- Nút xác nhận --}}
            <div class="text-end">
                <button class="btn btn-success btn-lg">✅ Xác nhận đặt hàng</button>
            </div>
        </form>
    @else
        <div class="alert alert-warning">Không có sản phẩm nào để thanh toán.</div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const voucherSelect = document.getElementById('voucher-select');

        voucherSelect.addEventListener('change', function () {
            const option = this.options[this.selectedIndex];
            const type = option.getAttribute('data-type');
            const value = parseFloat(option.getAttribute('data-value')) || 0;

            const subtotal = {{ $subtotal }};
            const shipping = {{ $shippingFee }};

            let discountAmount = 0;
            let discountText = '0';

            if (type === 'percent') {
                discountAmount = subtotal * value / 100;
                discountText = value + '%';
            } else if (type === 'fixed') {
                discountAmount = value;
                discountText = new Intl.NumberFormat('vi-VN').format(value) + ' VND';
            }

            const total = subtotal + shipping - discountAmount;

            document.getElementById('discount').innerText = discountText;
            document.getElementById('total').innerText = new Intl.NumberFormat('vi-VN').format(total) + ' VND';
        });
    });
</script>
@endsection
