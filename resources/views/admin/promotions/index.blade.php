@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2>Danh sách khuyến mãi</h2>
    <a href="{{ route('promotions.create') }}" class="btn btn-success mb-3">Thêm mới</a>

    @foreach($promotions as $promotion)
        <div class="card mb-2">
            <div class="card-body">
                <strong>{{ $promotion->code }}</strong> - 
                {{ number_format($promotion->discount_value, 0, ',', '.') }}
                {{ $promotion->discount_type == 'percentage' ? '%' : 'VNĐ' }}

                <div class="float-end">
                    <a href="{{ route('promotions.edit', $promotion) }}" class="btn btn-sm btn-primary">Sửa</a>
                    <form action="{{ route('promotions.destroy', $promotion) }}" method="POST" style="display:inline">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('Xóa?')" class="btn btn-sm btn-danger">Xóa</button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    {{ $promotions->links() }}
</div>
@endsection
