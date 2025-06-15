@extends('admin.layouts.app')

@section('title', 'Thêm biến thể sản phẩm')

@section('content')
<<<<<<< HEAD

=======
>>>>>>> be89ce2cbccc5cd0cc791b738965a2f68a61ae19
<div class="container py-4">
    <h2 class="mb-4">Thêm biến thể sản phẩm</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('variants.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

<<<<<<< HEAD
        <div class="mb-3">
            <label for="product_id" class="form-label">Sản phẩm</label>
            <select name="product_id" id="product_id" class="form-select" required>
                <option value="">-- Chọn sản phẩm --</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                        {{ $product->product_name }}
                    </option>
=======
        <div class="mb-4">
            <label class="form-label fw-bold">Sản phẩm</label>
            <select name="product_id" class="form-select" required>
                <option value="">-- Chọn sản phẩm --</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->product_name }}</option>
>>>>>>> be89ce2cbccc5cd0cc791b738965a2f68a61ae19
                @endforeach
            </select>
        </div>

<<<<<<< HEAD
        <div class="mb-3">
            <label for="ram_id" class="form-label">RAM</label>
            <select name="ram_id" id="ram_id" class="form-select" required>
                <option value="">-- Chọn RAM --</option>
                @foreach($rams as $ram)
                    <option value="{{ $ram->id }}" {{ old('ram_id') == $ram->id ? 'selected' : '' }}>
                        {{ $ram->value }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="storage_id" class="form-label">Storage</label>
            <select name="storage_id" id="storage_id" class="form-select" required>
                <option value="">-- Chọn Storage --</option>
                @foreach($storages as $storage)
                    <option value="{{ $storage->id }}" {{ old('storage_id') == $storage->id ? 'selected' : '' }}>
                        {{ $storage->value }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="color_id" class="form-label">Màu</label>
            <select name="color_id" id="color_id" class="form-select" required>
                <option value="">-- Chọn màu --</option>
                @foreach($colors as $color)
                    <option value="{{ $color->id }}" {{ old('color_id') == $color->id ? 'selected' : '' }}>
                        {{ $color->value }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Giá</label>
            <input type="number" name="price" id="price" class="form-control" value="{{ old('price') }}" required min="0" step="0.01" />
        </div>

        <div class="mb-3">
            <label for="discount_price" class="form-label">Giá khuyến mãi (nếu có)</label>
            <input type="number" name="discount_price" id="discount_price" class="form-control" value="{{ old('discount_price') }}" min="0" step="0.01" />
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Số lượng</label>
            <input type="number" name="quantity" id="quantity" class="form-control" value="{{ old('quantity') ?? 1 }}" required min="0" />
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Ảnh biến thể (tùy chọn)</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*" />
        </div>

        <button type="submit" class="btn btn-primary">Thêm biến thể</button>
        <a href="{{ route('variants.index') }}" class="btn btn-secondary ms-2">Hủy</a>
    </form>
</div>

=======
        {{-- Chọn thuộc tính --}}
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card p-3">
                    <h6 class="fw-bold mb-2">Chọn màu</h6>
                    @foreach($colors as $color)
                        <div class="form-check">
                            <input class="form-check-input attr-checkbox" type="checkbox" value="{{ $color->id }}" data-type="color" id="color-{{ $color->id }}">
                            <label class="form-check-label" for="color-{{ $color->id }}">{{ $color->value }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <h6 class="fw-bold mb-2">Chọn RAM</h6>
                    @foreach($rams as $ram)
                        <div class="form-check">
                            <input class="form-check-input attr-checkbox" type="checkbox" value="{{ $ram->id }}" data-type="ram" id="ram-{{ $ram->id }}">
                            <label class="form-check-label" for="ram-{{ $ram->id }}">{{ $ram->value }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <h6 class="fw-bold mb-2">Chọn dung lượng</h6>
                    @foreach($storages as $storage)
                        <div class="form-check">
                            <input class="form-check-input attr-checkbox" type="checkbox" value="{{ $storage->id }}" data-type="storage" id="storage-{{ $storage->id }}">
                            <label class="form-check-label" for="storage-{{ $storage->id }}">{{ $storage->value }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Bảng biến thể --}}
        <div id="variant-table-wrapper" class="d-none mt-4">
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-secondary text-center">
                            <tr>
                                <th>Màu</th>
                                <th>Ảnh</th>
                                <th>RAM</th>
                                <th>Dung lượng</th>
                                <th>Giá</th>
                                <th>Giá KM</th>
                                <th>Số lượng</th>
                            </tr>
                        </thead>
                        <tbody id="variant-table-body">
                            <!-- Biến thể tự động sinh -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex">
            <button type="submit" class="btn btn-primary me-2">Lưu tất cả biến thể</button>
            <a href="{{ route('variants.index') }}" class="btn btn-outline-secondary">Hủy</a>
        </div>
    </form>
</div>

<style>
    .form-check {
        margin-bottom: 0.4rem;
    }
    .variant-group td {
        background-color: #f1f3f5;
        font-weight: 600;
    }
    .table td, .table th {
        vertical-align: middle;
        text-align: center;
    }
</style>

<script>
    const colors = @json($colors);
    const rams = @json($rams);
    const storages = @json($storages);

    const selected = {
        color: [],
        ram: [],
        storage: []
    };

    document.querySelectorAll('.attr-checkbox').forEach(cb => {
        cb.addEventListener('change', () => {
            const type = cb.dataset.type;
            const value = parseInt(cb.value);

            if (cb.checked) {
                selected[type].push(value);
            } else {
                selected[type] = selected[type].filter(v => v !== value);
            }

            generateVariantTable();
        });
    });

    function generateVariantTable() {
        const tbody = document.getElementById('variant-table-body');
        tbody.innerHTML = '';

        if (selected.color.length && selected.ram.length && selected.storage.length) {
            document.getElementById('variant-table-wrapper').classList.remove('d-none');

            selected.color.forEach((colorId, colorIndex) => {
                const color = colors.find(c => c.id === colorId);

                let row = `
                    <tr class="variant-group">
                        <td>
                            <input type="hidden" name="color_id[${colorIndex}]" value="${color.id}">
                            ${color.value}
                        </td>
                        <td>
                            <input type="file" name="color_image[${colorIndex}]" class="form-control form-control-sm" accept="image/*">
                        </td>
                        <td colspan="5"></td>
                    </tr>
                `;

                selected.ram.forEach(ramId => {
                    const ram = rams.find(r => r.id === ramId);
                    selected.storage.forEach(storageId => {
                        const storage = storages.find(s => s.id === storageId);
                        const key = `${colorId}_${ramId}_${storageId}_${colorIndex}`;
                        row += `
                            <tr class="variant-detail">
                                <td></td>
                                <td></td>
                                <td>
                                    <input type="hidden" name="ram_id[${key}]" value="${ram.id}">
                                    ${ram.value}
                                </td>
                                <td>
                                    <input type="hidden" name="storage_id[${key}]" value="${storage.id}">
                                    ${storage.value}
                                </td>
                                <td><input type="number" name="price[${key}]" class="form-control form-control-sm" required min="0" step="0.01" /></td>
                                <td><input type="number" name="discount_price[${key}]" class="form-control form-control-sm" min="0" step="0.01" /></td>
                                <td><input type="number" name="quantity[${key}]" class="form-control form-control-sm" required min="0" value="1" /></td>
                            </tr>
                        `;
                    });
                });

                tbody.insertAdjacentHTML('beforeend', row);
            });
        } else {
            document.getElementById('variant-table-wrapper').classList.add('d-none');
        }
    }
</script>
>>>>>>> be89ce2cbccc5cd0cc791b738965a2f68a61ae19
@endsection
