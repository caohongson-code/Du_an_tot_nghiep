@extends('admin.layouts.app')

@section('title', 'Bảng điều khiển')

@section('content')
<div class="container-fluid px-4">

    {{-- THỐNG KÊ NHANH --}}
    <div class="row">
        <div class="col-md-3">
            <a href="{{ url('admin/customers') }}" class="text-decoration-none">
                <div class="card text-white mb-4 shadow-sm" style="background: linear-gradient(135deg, #4e73df, #224abe);">
                    <div class="card-body fw-bold">Tổng khách hàng</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">{{ $totalCustomers }} khách hàng</h5>
                        <span class="btn btn-outline-light btn-sm">Xem →</span>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ url('admin/products') }}" class="text-decoration-none">
                <div class="card text-white mb-4 shadow-sm" style="background: linear-gradient(135deg, #36b9cc, #1c768f);">
                    <div class="card-body fw-bold">Tổng sản phẩm</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">{{ $totalProducts }} sản phẩm</h5>
                        <span class="btn btn-outline-light btn-sm">Xem →</span>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ url('admin/orders') }}" class="text-decoration-none">
                <div class="card text-white mb-4 shadow-sm" style="background: linear-gradient(135deg, #f6c23e, #dda20a);">
                    <div class="card-body fw-bold">Đơn hàng chưa xác nhận gần đây</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">{{ $totalOrders }} đơn hàng</h5>
                        <span class="btn btn-outline-light btn-sm">Xem →</span>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ url('admin/products?low_stock=1') }}" class="text-decoration-none">
                <div class="card text-white mb-4 shadow-sm" style="background: linear-gradient(135deg, #e74a3b, #b92c23);">
                    <div class="card-body fw-bold">Sắp hết hàng</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">{{ $lowStockCount }} sản phẩm</h5>
                        <span class="btn btn-outline-light btn-sm">Xem →</span>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- BỘ LỌC RANGE --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <form method="GET" class="d-flex align-items-center flex-wrap gap-2">
                <label for="range" class="fw-bold mb-0">
                    <i class="fas fa-calendar-alt me-2 text-primary"></i> Thống kê theo:
                </label>
                <select name="range" id="range" class="form-select rounded-pill border-primary shadow-sm" style="max-width: 220px;" onchange="this.form.submit()">
                    <option value="daily" {{ $range == 'daily' ? 'selected' : '' }}>📅 Ngày</option>
                    <option value="monthly" {{ $range == 'monthly' ? 'selected' : '' }}>🗓️ Tháng</option>
                    <option value="yearly" {{ $range == 'yearly' ? 'selected' : '' }}>📆 Năm</option>
                </select>
            </form>
        </div>
    </div>

    {{-- BIỂU ĐỒ --}}
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow rounded-4 border-0">
                <div class="card-header text-white fw-bold" style="background: linear-gradient(90deg, #36b9cc, #1cc88a);">
                    <i class="fas fa-chart-line me-1"></i> Biểu đồ đường
                </div>
                <div class="card-body">
                    <canvas id="lineChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow rounded-4 border-0">
                <div class="card-header text-white fw-bold" style="background: linear-gradient(90deg, #f6c23e, #e74a3b);">
                    <i class="fas fa-chart-bar me-1"></i> Biểu đồ cột
                </div>
                <div class="card-body">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ĐƠN HÀNG CHƯA XÁC NHẬN --}}
    <div class="card mb-4 shadow-sm rounded-4">
        <div class="card-header bg-warning text-dark fw-bold rounded-top-4">
            <i class="fas fa-clock me-1"></i> Đơn hàng chưa xác nhận gần đây
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 text-center rounded-bottom-4">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Tên khách</th>
                            <th class="text-end">Tổng tiền</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                            <tr>
                                <td>
                                    <a href="{{ url('admin/orders/' . $order->id) }}" class="text-decoration-none fw-bold text-primary">
                                        #{{ $order->id }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ url('admin/orders/' . $order->id) }}" class="text-decoration-none text-dark">
                                        {{ $order->account->full_name ?? 'Không rõ' }}
                                    </a>
                                </td>
                                <td class="text-end">{{ number_format($order->total_amount, 0, ',', '.') }} đ</td>
                                <td>
                                    <span class="badge bg-{{ $order->orderStatus->status_color ?? 'secondary' }}">
                                        {{ $order->orderStatus->status_name ?? 'Không rõ' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- KHÁCH HÀNG MỚI --}}
    <div class="card mb-4 shadow-sm rounded-4">
        <div class="card-header bg-info text-white fw-bold rounded-top-4">
            <i class="fas fa-users me-1"></i> Khách hàng mới
        </div>
        <div class="card-body">
            <table class="table table-hover text-center">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Ngày sinh</th>
                        <th>Điện thoại</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($newCustomers as $cus)
                        <tr>
                            <td>#{{ $cus->id }}</td>
                            <td>{{ $cus->full_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($cus->dob)->format('d/m/Y') }}</td>
                            <td>{{ $cus->phone }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const commonOptions = {
        responsive: true,
        plugins: {
            legend: { position: 'top' },
            title: { display: true, text: 'Doanh thu theo {{ $range }}' },
            tooltip: {
                callbacks: {
                    label: ctx => `${ctx.dataset.label}: ${ctx.parsed.y.toLocaleString()} đ`
                }
            }
        },
        scales: {
            y: {
                ticks: {
                    callback: value => value.toLocaleString() + ' đ'
                },
                beginAtZero: true
            }
        }
    };

    new Chart(document.getElementById('lineChart').getContext('2d'), {
        type: 'line',
        data: {!! json_encode($lineChartData) !!},
        options: {
            ...commonOptions,
            elements: {
                line: { tension: 0.4 },
                point: { radius: 5, backgroundColor: '#fff', borderColor: '#36b9cc' }
            }
        }
    });

    new Chart(document.getElementById('barChart').getContext('2d'), {
        type: 'bar',
        data: {!! json_encode($barChartData) !!},
        options: {
            ...commonOptions,
            scales: {
                x: { grid: { display: false } },
                y: {
                    grid: { color: '#f0f0f0' },
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection
