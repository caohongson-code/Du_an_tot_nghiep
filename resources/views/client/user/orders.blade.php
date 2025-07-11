@extends('client.user.dashboard')

@section('dashboard-content')
@push('styles')
<style>
    .nav-tabs .nav-link {
        font-weight: bold;
        padding: 10px 16px;
        color: #333;
    }
    .nav-tabs .nav-link.active {
        background-color: #337ab7;
        color: white !important;
        border-radius: 4px 4px 0 0;
    }
</style>
@endpush

@php
    $statusMap = [null => 'Tất cả'] + $statuses->pluck('status_name', 'id')->toArray();
@endphp

<h4 class="mb-3">📦 Đơn hàng của bạn</h4>

{{-- Tabs trạng thái --}}
<ul class="nav nav-tabs">
    @foreach ($statusMap as $key => $label)
        <li class="nav-item">
            <a class="nav-link {{ (string) $key === (string) $currentStatus ? 'active' : '' }}"
               href="{{ route('user.orders', ['status' => $key]) }}">{{ $label }}</a>
        </li>
    @endforeach
</ul>

{{-- Danh sách đơn --}}
<div class="mt-3">
    @forelse ($orders as $order)
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-light">
                <strong>Mã đơn: #{{ $order->id }}</strong> |
                <strong>Trạng thái:</strong> {{ $order->orderStatus->status_name ?? 'Không rõ' }} |
                <strong>Ngày:</strong> {{ $order->created_at->format('d/m/Y H:i') }}
            </div>
            <div class="card-body">
                @foreach ($order->orderDetails as $item)
                    @php
                        $variant = $item->productVariant;
                        $product = $variant?->product;
                        $image = $product?->image ? asset('storage/' . $product->image) : asset('images/default.jpg');
                        $reviewKey = $order->id . '-' . $item->product_variant_id;
                    @endphp
                    <div class="d-flex mb-3 border-bottom pb-2">
                        <img src="{{ $image }}" alt="" class="me-3 rounded" style="width: 80px; height: 80px; object-fit: cover;">
                        <div>
                            <h6 class="mb-1">{{ $product->product_name ?? 'Sản phẩm không xác định' }}</h6>
                            <p class="mb-1">Số lượng: {{ $item->quantity }}</p>

                            @if ($order->order_status_id == 5)
                                @if (!empty($reviewedMap[$reviewKey]))
                                    <span class="badge bg-secondary">Đã đánh giá</span>
                                @else
                                    <button class="btn btn-sm btn-outline-success btn-review"
                                            data-variant-id="{{ $item->product_variant_id }}"
                                            data-order-id="{{ $order->id }}"
                                            data-product-name="{{ $product->product_name }}">
                                        Đánh giá
                                    </button>
                                @endif
                            @endif
                        </div>
                    </div>
                @endforeach

                {{-- Hành động --}}
                <div class="text-end">
                    <a href="{{ route('user.orders.detail', $order->id) }}" class="btn btn-sm btn-primary">Chi tiết</a>

                    @if ($order->order_status_id == 1)
                        <button class="btn btn-sm btn-danger cancel-order-btn" data-id="{{ $order->id }}">Huỷ đơn</button>
                    @endif

                    @if ($order->order_status_id == 4)
                        <form action="{{ route('orders.confirmReceived', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn xác nhận đã nhận hàng?');">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">✅ Đã nhận được hàng</button>
                        </form>
                    @endif

                    @if ($order->order_status_id == 5)
                        <button class="btn btn-sm btn-warning return-order-btn" data-id="{{ $order->id }}">Trả hàng</button>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info">Không có đơn hàng nào.</div>
    @endforelse

    <div class="mt-4">{{ $orders->links('pagination::bootstrap-5') }}</div>
</div>

{{-- Modal trả hàng --}}
<div class="modal fade" id="returnModal" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="returnForm" enctype="multipart/form-data"method="POST" >
      @csrf
      <input type="hidden" id="returnOrderId" name="order_id">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Yêu cầu trả hàng</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Lý do trả hàng</label>
            <textarea name="reason" class="form-control" required></textarea>
          </div>
          <div class="mb-3">
            <label>Ảnh minh hoạ (nếu có)</label>
            <input type="file" name="images[]" class="form-control" multiple>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Gửi yêu cầu</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
        </div>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
    $(function () {
        // Huỷ đơn hàng
        $('.cancel-order-btn').click(function () {
    const id = $(this).data('id');
    if (confirm('Bạn muốn huỷ đơn này?')) {
        $.post(`/user/orders/${id}/cancel`, {
            _token: '{{ csrf_token() }}'
        }, function (res) {
            alert(res.message);
            if (res.success) {
                window.location.href = `{{ route('user.orders') }}?status=7`;
            }
        }).fail(function (xhr) {
            console.error(xhr.responseText);
            alert('Lỗi khi huỷ đơn hàng.');
        });
    }
});

        // Mở modal đánh giá
        $('.btn-review').click(function () {
            $('#reviewVariantId').val($(this).data('variant-id'));
            $('#reviewOrderId').val($(this).data('order-id'));
            $('#reviewProductName').text($(this).data('product-name'));
            $('#reviewModal').modal('show');
        });

        // Mở modal trả hàng
        $('.return-order-btn').click(function () {
            const orderId = $(this).data('id');
            $('#returnOrderId').val(orderId);
            $('#returnModal').modal('show');
        });

        // Submit trả hàng
        $('#returnForm').submit(function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const orderId = $('#returnOrderId').val();

            $.ajax({
                url: `/user/orders/${orderId}/return-refund`,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (res) {
                    alert(res.message);
                    if (res.success) location.reload();
                },
                error: function () {
                    alert('Đã xảy ra lỗi khi gửi yêu cầu trả hàng.');
                }
            });
        });
    });
</script>
@endpush
@endsection
