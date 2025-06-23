<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Color;
use App\Models\Ram;
use App\Models\Storage;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage as StorageFacade; 


class ProductController extends Controller
{
    // Hiển thị danh sách sản phẩm
    public function index(Request $request)
    {
       $query = Product::with('category');

        // Xử lý tìm kiếm
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhereHas('category', function ($q) use ($search) {
                      $q->where('category_name', 'like', "%{$search}%");
                  });
            });
        }

        $products = $query->get();
        return view('admin.products.index', compact('products'));
    }

    // Hiển thị form tạo sản phẩm mới
public function create()
    {
        $categories = Category::all();
        $colors = Color::all();
        $rams = Ram::all();
        $storages = Storage::all();
        return view('admin.products.create', compact('categories', 'colors', 'rams', 'storages'));
    }

    // Lưu sản phẩm mới vào database
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lte:price',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'quantity' => 'required|integer|min:0',
            'status' => 'required|boolean',
            'variants.*.color_id' => 'required|exists:colors,id',
            'variants.*.ram_id' => 'required|exists:rams,id',
            'variants.*.storage_id' => 'required|exists:storages,id',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.quantity' => 'required|integer|min:0',
            'color_images.*' => 'required_if:variants.*.color_id,exists|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'discount_price.lte' => 'Giá khuyến mãi phải nhỏ hơn hoặc bằng giá gốc.',
        ]);

        DB::beginTransaction();
        try {
            // Tạo sản phẩm
            $product = new Product();
            $product->product_name = $request->product_name;
            $product->category_id = $request->category_id;
            $product->price = $request->price;
            $product->discount_price = $request->discount_price;
            $product->quantity = $request->quantity; // Tổng số lượng có thể dùng để kiểm tra
            $product->status = $request->status;

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('products', 'public');
                $product->image = $path;
            }

            $product->save();

            // Xử lý hình ảnh theo màu
            $colorImages = [];
            if ($request->hasFile('color_images')) {
                foreach ($request->file('color_images') as $colorId => $file) {
                    if ($file->isValid()) {
                        $path = $file->store('product_variants', 'public');
                        $colorImages[$colorId] = $path;
                    } else {
                        throw new \Exception("Tệp hình ảnh cho màu ID {$colorId} không hợp lệ.");
                    }
                }
            }

            // Tạo biến thể với kiểm tra trùng lặp
            $variantsData = $request->variants ?? [];
            $existingVariants = ProductVariant::where('product_id', $product->id)->get()->keyBy(function ($item) {
                return $item->color_id . '-' . $item->ram_id . '-' . $item->storage_id;
            });

            foreach ($variantsData as $index => $variantData) {
                $key = $variantData['color_id'] . '-' . $variantData['ram_id'] . '-' . $variantData['storage_id'];
                if (!isset($existingVariants[$key])) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'color_id' => $variantData['color_id'],
                        'ram_id' => $variantData['ram_id'],
                        'storage_id' => $variantData['storage_id'],
                        'price' => $variantData['price'],
                        'quantity' => $variantData['quantity'] ?? 0,
                        'image' => $colorImages[$variantData['color_id']] ?? null,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Thêm sản phẩm và biến thể thành công!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Đã xảy ra lỗi: ' . $e->getMessage()])->withInput();
        }
    }
    // Hiển thị chi tiết sản phẩm
    public function show($id)
    {
        $product = Product::with('variants')->findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    // Hiển thị form sửa sản phẩm
    public function edit($id)
    {
        $categories = Category::all();
        $colors = Color::all();
        $rams = Ram::all();
        $storages = Storage::all();
        $product = Product::with('variants')->findOrFail($id);
        return view('admin.products.edit', compact('product', 'categories', 'colors', 'rams', 'storages'));
    }

    // Cập nhật sản phẩm
public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);

    $request->validate([
        'product_name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'category_id' => 'required|exists:categories,id',
        'description' => 'nullable|string',
    ]);

    // Cập nhật thông tin sản phẩm
    $product->update([
        'product_name' => $request->product_name,
        'price' => $request->price,
        'category_id' => $request->category_id,
        'description' => $request->description,
    ]);

    $variantsData = $request->variants ?? [];

    // Lưu danh sách ID biến thể đã được xử lý (dùng để xoá những cái không còn)
    $processedVariantIds = [];

    foreach ($variantsData as $index => $variantData) {
        $variantId = $variantData['id'] ?? null;

        // Kiểm tra xem có ảnh mới không
        $imagePath = null;
        if ($request->hasFile("color_images.{$variantData['color_id']}")) {
            $file = $request->file("color_images.{$variantData['color_id']}");
            $imagePath = $file->store('product_variants', 'public');
        } else {
            // Nếu không có ảnh mới, lấy ảnh cũ
            $imagePath = $variantData['old_image'] ?? null;
        }

        if ($variantId) {
            // Nếu có ID → Cập nhật biến thể cũ
            $variant = ProductVariant::find($variantId);
            if ($variant) {
                $variant->update([
                    'color_id' => $variantData['color_id'],
                    'ram_id' => $variantData['ram_id'],
                    'storage_id' => $variantData['storage_id'],
                    'price' => $variantData['price'],
                    'image' => $imagePath,
                ]);
                $processedVariantIds[] = $variantId;
            }
        } else {
            // Nếu không có ID → Thêm mới biến thể
            $newVariant = ProductVariant::create([
                'product_id' => $product->id,
                'color_id' => $variantData['color_id'],
                'ram_id' => $variantData['ram_id'],
                'storage_id' => $variantData['storage_id'],
                'price' => $variantData['price'],
                'image' => $imagePath,
            ]);
            $processedVariantIds[] = $newVariant->id;
        }
    }

    // Xoá các biến thể không còn được submit trong form
    ProductVariant::where('product_id', $product->id)
        ->whereNotIn('id', $processedVariantIds)
        ->delete();

    return redirect()->route('products.index')->with('success', 'Cập nhật sản phẩm thành công!');
}


    // Xóa sản phẩm
public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Xóa file ảnh của sản phẩm nếu tồn tại
        if ($product->image && StorageFacade::disk('public')->exists($product->image)) {
            StorageFacade::disk('public')->delete($product->image);
        }

        // Xóa file ảnh của các biến thể nếu tồn tại
        foreach ($product->variants as $variant) {
            if ($variant->image && StorageFacade::disk('public')->exists($variant->image)) {
                StorageFacade::disk('public')->delete($variant->image);
            }
        }

        // Xóa tất cả biến thể trước, sau đó xóa sản phẩm
        $product->variants()->delete();
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Xóa sản phẩm thành công!');
    }
}