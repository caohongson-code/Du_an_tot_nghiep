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

        // Lấy giá trị lọc từ request
        $colorId = $request->input('color_id');
        $ramId = $request->input('ram_id');
        $storageId = $request->input('storage_id');
        $search = $request->input('search');

        // Áp dụng bộ lọc
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

    public function destroy(ProductVariant $variant)
    {
        if ($variant->image && StorageFacade::disk('public')->exists($variant->image)) {
            StorageFacade::disk('public')->delete($variant->image);
        }

        $variant->delete();

        return redirect()->route('variants.index')->with('success', 'Xóa biến thể thành công.');
    }
}
