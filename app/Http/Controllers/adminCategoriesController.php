<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\adminCategoriesModel;
use Illuminate\Http\Request;

class adminCatCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request )
    {
        $query = adminCategoriesModel::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('category_name', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        $listCat = $query->orderByDesc('id')->paginate(3);

        return view('admin.danh_muc.index', compact('listCat'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.danh_muc.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|unique:categories,category_name|max:255',
            'description' => 'nullable',
        ], [
            'category_name.required' => 'Tên danh muc không được để trống',
            'category_name.unique' => 'Tên danh muc đã tồn tại',
            'category_name.max' => 'Tên danh muc không được vượt quá 255 ký tự',
        ]);

        adminCategoriesModel::create([
            'category_name' => $request->category_name,
            'description' => $request->description?? '',
        ]);

        return redirect()->route('danhmuc.index')->with('success', 'Thêm danh muc thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
     $danhmucs = adminCategoriesModel::findOrFail($id);
     return view('admin.danh_muc.edit',compact('danhmucs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'category_name' => 'required|max:255'. $id,
            'description' => 'nullable',
        ], [
            'category_name.required' => 'Tên danh muc không được để trống',
            'category_name.max' => 'Tên danh muc không được vượt quá 255 ký tự',

        ]);
        $danhmucs = adminCategoriesModel::findOrFail($id);
       $danhmucs->update([
            'category_name' => $request->category_name,
            'description' => $request->description?? '',
        ]);

        return redirect()->route('danhmuc.index')->with('success', 'Cập nhật danh mục thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $danhmuc = adminCategoriesModel::findOrFail($id);
        $danhmuc->delete();
        return redirect()->route('danhmuc.index')->with('success', 'Xoá danh muc thành công');

    }
}
