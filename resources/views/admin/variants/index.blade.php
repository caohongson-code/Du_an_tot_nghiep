@extends('admin.layouts.app')

@section('title', 'Quản lý biến thể sản phẩm')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Biến thể sản phẩm</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('variants.create') }}" class="btn btn-success mb-3">
        <i class="fas fa-plus me-2"></i> Thêm biến thể
    </a>

    <div class="accordion" id="variantAccordion">
        @forelse($products as $product)
            <div class="accordion-item mb-2">
                <h2 class="accordion-header" id="heading-{{ $product->id }}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $product->id }}" aria-expanded="false">
                        {{ $product->product_name }}
                        <span class="badge bg-primary ms-2">{{ $product->variants->count() }} biến thể</span>
                    </button>
                </h2>
                <div id="collapse-{{ $product->id }}" class="accordion-collapse collapse" data-bs-parent="#variantAccordion">
                    <div class="accordion-body">
                        @if($product->variants->count())
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle table-striped table-hover">
                                    <thead class="table-dark text-center">
                                        <tr>
                                            <th>ID</th>
                                            <th>RAM</th>
                                            <th>Dung lượng</th>
                                            <th>Màu</th>
                                            <th>Ảnh</th>
                                            <th>Giá</th>
                                            <th>KM</th>
                                            <th>SL</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($product->variants as $variant)
                                            <tr class="text-center">
                                                <td>{{ $variant->id }}</td>
                                                <td>{{ $variant->ram->value ?? '---' }}</td>
                                                <td>{{ $variant->storage->value ?? '---' }}</td>
                                                <td>{{ $variant->color->value ?? '---' }}</td>
                                                <td>
                                                    @if($variant->image)
                                                        <img src="{{ asset('storage/' . $variant->image) }}" width="60" class="rounded shadow-sm" title="Ảnh sản phẩm">
                                                    @else
                                                        <span class="text-muted">Không có ảnh</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ number_format($variant->price, 0, ',', '.') }} đ
                                                </td>
                                                <td>
                                                    @if($variant->discount_price)
                                                        <span class="text-danger fw-bold">
                                                            {{ number_format($variant->discount_price, 0, ',', '.') }} đ
                                                        </span>
                                                        <br>
                                                        <small class="text-muted fst-italic">
                                                            Giảm 
                                                            {{
                                                                round(100 - ($variant->discount_price / $variant->price * 100))
                                                            }}%
                                                        </small>
                                                    @else
                                                        <span class="text-muted fst-italic">--</span>
                                                    @endif
                                                </td>
                                                <td>{{ $variant->quantity }}</td>
                                                <td>
                                                    <a href="{{ route('variants.edit', $variant->id) }}" class="btn btn-warning btn-sm me-1" title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('variants.destroy', $variant->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa biến thể này?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Xóa">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted fst-italic">Chưa có biến thể nào.</p>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center text-muted">Chưa có sản phẩm nào.</p>
        @endforelse
    </div>
</div>
@endsection
