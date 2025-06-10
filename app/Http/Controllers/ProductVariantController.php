<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Ram;
use App\Models\Storage; // Model Storage
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage as StorageFacade; // Alias Facade Storage

class ProductVariantController extends Controller
{
public function index()
{
    $products = Product::with(['variants.ram', 'variants.storage', 'variants.color'])
        ->orderBy('created_at', 'desc')
        ->get();

    return view('admin.variants.index', compact('products'));
}


    public function create()
    {
        // Nếu bảng lớn, nên paginate hoặc cache
        $products = Product::all();
        $rams = Ram::all();
        $storages = Storage::all();
        $colors = Color::all();

        return view('admin.variants.create', compact('products', 'rams', 'storages', 'colors'));
    }

public function store(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'color_id' => 'required|array',
        'color_id.*' => 'required|exists:colors,id',
        'ram_id' => 'required|array',
        'ram_id.*' => 'required|exists:rams,id',
        'storage_id' => 'required|array',
        'storage_id.*' => 'required|exists:storages,id',
        'price' => 'required|array',
        'price.*' => 'required|numeric|min:0',
        'discount_price' => 'nullable|array',
        'discount_price.*' => 'nullable|numeric|min:0',
        'quantity' => 'required|array',
        'quantity.*' => 'required|integer|min:0',
        'color_image' => 'nullable|array',
        'color_image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $productId = $request->input('product_id');
    $colorIds = $request->input('color_id');
    $ramIds = $request->input('ram_id', []); // Mảng kết hợp
    $storageIds = $request->input('storage_id', []);
    $prices = $request->input('price', []);
    $discountPrices = $request->input('discount_price', []);
    $quantities = $request->input('quantity', []);
    $colorImages = $request->file('color_image', []);

    $skippedVariants = 0;

    // Kiểm tra dữ liệu cơ bản
    if (empty($colorIds) || empty(array_filter($ramIds)) || empty(array_filter($storageIds))) {
        return back()->withErrors('Vui lòng chọn ít nhất một màu, RAM và dung lượng.')->withInput();
    }

    if (count($colorIds) !== count($colorImages ?: [])) {
        return back()->withErrors('Số lượng màu và ảnh không đồng đều.')->withInput();
    }

    foreach ($colorIds as $colorIndex => $colorId) {
        $imagePath = isset($colorImages[$colorIndex]) ? $colorImages[$colorIndex]->store('variants', 'public') : null;

        // Lặp qua tất cả các tổ hợp ram_id và storage_id
        foreach ($ramIds as $variantKey => $ramId) {
            // Phân tách key để lấy storageId và colorIndex
            $parts = explode('_', $variantKey);
            if (count($parts) < 3 || $parts[3] !== (string)$colorIndex) {
                continue; // Bỏ qua nếu không khớp với colorIndex
            }
            $storageId = $storageIds[$variantKey] ?? null;

            if (!$storageId) {
                continue; // Bỏ qua nếu thiếu storageId
            }

            // Kiểm tra biến thể đã tồn tại
            $exists = ProductVariant::where('product_id', $productId)
                ->where('ram_id', $ramId)
                ->where('storage_id', $storageId)
                ->where('color_id', $colorId)
                ->exists();

            if ($exists) {
                $skippedVariants++;
                continue;
            }

            // Lấy giá trị từ mảng, sử dụng key động
            $price = $prices[$variantKey] ?? 0;
            $discountPrice = $discountPrices[$variantKey] ?? null;
            $quantity = $quantities[$variantKey] ?? 1;

            ProductVariant::create([
                'product_id' => $productId,
                'ram_id' => $ramId,
                'storage_id' => $storageId,
                'color_id' => $colorId,
                'price' => $price,
                'discount_price' => $discountPrice,
                'quantity' => $quantity,
                'image' => $imagePath, // Ảnh chung cho màu
            ]);
        }
    }

    $message = 'Thêm biến thể thành công!';
    if ($skippedVariants > 0) {
        $message .= " ($skippedVariants biến thể bị bỏ qua vì đã tồn tại)";
    }

    return redirect()->route('variants.index')->with('success', $message);
}

    // Dùng Route Model Binding gọn code
    public function edit(ProductVariant $variant)
    {
        $products = Product::all();
        $rams = Ram::all();
        $storages = Storage::all();
        $colors = Color::all();

        return view('admin.variants.edit', compact('variant', 'products', 'rams', 'storages', 'colors'));
    }

    public function update(Request $request, ProductVariant $variant)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'ram_id' => 'required|exists:rams,id',
            'storage_id' => 'required|exists:storages,id',
            'color_id' => 'required|exists:colors,id',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lte:price',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['product_id', 'ram_id', 'storage_id', 'color_id', 'price', 'discount_price', 'quantity']);

        if ($request->hasFile('image')) {
            if ($variant->image && StorageFacade::disk('public')->exists($variant->image)) {
                StorageFacade::disk('public')->delete($variant->image);
            }
            $data['image'] = $request->file('image')->store('variants', 'public');
        }

        $variant->update($data);

        return redirect()->route('variants.index')->with('success', 'Cập nhật biến thể thành công.');
    }

    public function destroy(ProductVariant $variant)
    {
        if ($variant->image && StorageFacade::disk('public')->exists($variant->image)) {
            StorageFacade::disk('public')->delete($variant->image);
        }

        $variant->delete();

        return redirect()->route('variants.index')->with('success', 'Xóa biến thể thành công.');
    }
}
