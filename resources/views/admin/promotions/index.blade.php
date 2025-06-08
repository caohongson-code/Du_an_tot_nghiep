@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-3">Danh sách khuyến mãi</h2>
    <a href="{{ route('promotions.create') }}" class="btn btn-success mb-3">Thêm mới</a>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-primary">
                <tr>
                    <th>#</th>
                    <th>Mã KM</th>
                    <th>Loại giảm</th>
                    <th>Giá trị</th>
                    <th>Thời gian</th>
                    <th>Giới hạn</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($promotions as $index => $promotion)
                    <tr>
                        <td>{{ $loop->iteration + ($promotions->currentPage() - 1) * $promotions->perPage() }}</td>
                        <td><strong>{{ $promotion->code }}</strong></td>
                        <td>{{ $promotion->discount_type == 'percentage' ? 'Phần trăm' : 'Cố định' }}</td>
                        <td>
                            {{ number_format($promotion->discount_value, 0, ',', '.') }}
                            {{ $promotion->discount_type == 'percentage' ? '%' : 'VNĐ' }}
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($promotion->start_date)->format('d/m/Y H:i') }} <br>
                            → {{ \Carbon\Carbon::parse($promotion->end_date)->format('d/m/Y H:i') }}
                        </td>
                        <td>{{ $promotion->usage_limit ?? 'Không giới hạn' }}</td>
                        <td>
                            <span class="badge bg-{{ $promotion->is_active ? 'success' : 'secondary' }}">
                                {{ $promotion->is_active ? 'Đang áp dụng' : 'Đã tắt' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('promotions.edit', $promotion) }}" class="btn btn-sm btn-primary mb-1">Sửa</a>
                            <form action="{{ route('promotions.destroy', $promotion) }}" method="POST" style="display:inline">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Xóa khuyến mãi này?')" class="btn btn-sm btn-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">Không có khuyến mãi nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $promotions->links() }}
</div>
@endsection
