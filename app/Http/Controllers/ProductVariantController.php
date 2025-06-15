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
    public function index(Request $request)
    {
        $query = Product::with(['variants.ram', 'variants.storage', 'variants.color'])
            ->orderBy('created_at', 'desc');

        // Lọc theo các trường
        $colorId = $request->input('color_id');
        $ramId = $request->input('ram_id');
        $storageId = $request->input('storage_id');
        $search = $request->input('search');

        if ($colorId) {
            $query->whereHas('variants', function ($q) use ($colorId) {
                $q->where('color_id', $colorId);
            });
        }

        if ($ramId) {
            $query->whereHas('variants', function ($q) use ($ramId) {
                $q->where('ram_id', $ramId);
            });
        }

        if ($storageId) {
            $query->whereHas('variants', function ($q) use ($storageId) {
                $q->where('storage_id', $storageId);
            });
        }

        if ($search) {
            $query->where('product_name', 'like', '%' . $search . '%');
        }

        $products = $query->get();
        $colors = Color::all();
        $rams = Ram::all();
        $storages = Storage::all();

        return view('admin.variants.index', compact('products', 'colors', 'rams', 'storages', 'request'));
    }

    public function create()
    {
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

        // Kiểm tra trùng tổ hợp (trừ chính nó)
        $exists = ProductVariant::where('product_id', $request->product_id)
            ->where('ram_id', $request->ram_id)
            ->where('storage_id', $request->storage_id)
            ->where('color_id', $request->color_id)
            ->where('id', '!=', $variant->id)
            ->exists();

        if ($exists) {
            return back()->withErrors('Tổ hợp này đã tồn tại!')->withInput();
        }

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
