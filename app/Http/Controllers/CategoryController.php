<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    // Hiển thị danh sách danh mục
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    // Hiển thị form tạo danh mục mới
    public function create()
    {
        return view('admin.categories.create');
    }

    // Lưu danh mục mới vào database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:225',
            'description' => 'nullable|string',
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')->with('success', 'Thêm danh mục thành công!');
    }

    // Hiển thị chi tiết danh mục
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.show', compact('category'));
    }

    // Hiển thị form sửa danh mục
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    // Cập nhật danh mục
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:225',
            'description' => 'nullable|string',
        ]);

        $category = Category::findOrFail($id);
        $category->update($validated);

        return redirect()->route('categories.index')->with('success', 'Cập nhật danh mục thành công!');
    }

    // Xóa danh mục
    public function destroy($id)
    {
        Category::destroy($id);
        return redirect()->route('categories.index')->with('success', 'Xóa danh mục thành công!');
    }
}
