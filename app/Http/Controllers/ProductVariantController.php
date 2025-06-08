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
        // Cố định orderBy rõ ràng
        $variants = ProductVariant::with(['product', 'ram', 'storage', 'color'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.variants.index', compact('variants'));
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
            'ram_id' => 'required|exists:rams,id',
            'storage_id' => 'required|exists:storages,id',
            'color_id' => 'required|exists:colors,id',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lte:price',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = $request->file('image') ? $request->file('image')->store('variants', 'public') : null;

        ProductVariant::create([
            'product_id' => $request->product_id,
            'ram_id' => $request->ram_id,
            'storage_id' => $request->storage_id,
            'color_id' => $request->color_id,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'quantity' => $request->quantity,
            'image' => $imagePath,
        ]);

        return redirect()->route('variants.index')->with('success', 'Thêm biến thể thành công.');
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
