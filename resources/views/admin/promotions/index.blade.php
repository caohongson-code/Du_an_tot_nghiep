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
<<<<<<< HEAD
                    <th>Sản phẩm áp dụng</th>
                    <th>Danh mục áp dụng</th>
=======
>>>>>>> be89ce2cbccc5cd0cc791b738965a2f68a61ae19
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
<<<<<<< HEAD

                        {{-- ✅ Sản phẩm áp dụng: theo ưu tiên --}}
                        <td>
                            @if($promotion->products->isNotEmpty())
                                <div class="d-flex flex-column gap-1">
                                    @foreach ($promotion->products as $product)
                                        <span class="badge bg-info text-dark">{{ $product->product_name }}</span>
                                    @endforeach
                                </div>
                            @elseif($promotion->categories->isNotEmpty())
                                <span class="text-muted fst-italic">Theo danh mục</span>
                            @else
                                <span class="text-muted fst-italic">Tất cả sản phẩm</span>
                            @endif
                        </td>

                        {{-- ✅ Danh mục áp dụng --}}
                        <td>
                            @if($promotion->categories->isEmpty())
                                <span class="text-muted fst-italic">Không chọn danh mục</span>
                            @else
                                <div class="d-flex flex-column gap-1">
                                    @foreach ($promotion->categories as $category)
                                        <span class="badge bg-warning text-dark">{{ $category->category_name }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </td>

=======
>>>>>>> be89ce2cbccc5cd0cc791b738965a2f68a61ae19
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
<<<<<<< HEAD
                        <td colspan="10">Không có khuyến mãi nào.</td>
=======
                        <td colspan="9">Không có khuyến mãi nào.</td>
>>>>>>> be89ce2cbccc5cd0cc791b738965a2f68a61ae19
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $promotions->links() }}
</div>
@endsection
