<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    // Hiển thị danh sách sản phẩm
    public function index()
    {
        $products = Product::all();
        return view('admin.products.index', compact('products'));
    }

    // Hiển thị form tạo sản phẩm mới
    public function create()
    {
         $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    // Lưu sản phẩm mới vào database
    public function store(Request $request)
{
    $validated = $request->validate([
        'category_id' => 'required|integer',
        'product_name' => 'required|string|max:225',
        'price' => 'required|numeric',
        'discount_price' => 'nullable|numeric',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'quantity' => 'required|integer',
        'status' => 'required|boolean',
    ]);

    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('products', 'public');
        $validated['image'] = $path;
    }

    Product::create($validated);

    return redirect()->route('products.index')->with('success', 'Thêm sản phẩm thành công!');
}
    // Hiển thị chi tiết sản phẩm
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    // Hiển thị form sửa sản phẩm
    public function edit($id)
    {
        $categories = Category::all();
        $product = Product::findOrFail($id);
        return view('admin.products.edit', compact('product','categories'));
    }

    // Cập nhật sản phẩm
    public function update(Request $request, $id)
{
    $validated = $request->validate([
        'category_id' => 'required|integer',
        'product_name' => 'required|string|max:225',
        'price' => 'required|numeric',
        'discount_price' => 'nullable|numeric',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'quantity' => 'required|integer',
        'status' => 'required|boolean',
    ]);

    $product = Product::findOrFail($id);

    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('products', 'public');
        $validated['image'] = $path;
    }

    $product->update($validated);

    return redirect()->route('products.index')->with('success', 'Cập nhật sản phẩm thành công!');
}

    // Xóa sản phẩm
    public function destroy($id)
    {
        Product::destroy($id);
        return redirect()->route('products.index')->with('success', 'Xóa sản phẩm thành công!');
    }
}
