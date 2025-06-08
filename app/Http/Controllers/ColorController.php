<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index()
    {
        $colors = Color::all();
        return view('admin.colors.index', [
            'colors' => $colors,
            'type' => 'Màu sắc',
            'routePrefix' => 'colors',
        ]);
    }

    public function create()
    {
        return view('admin.colors.create', [
            'type' => 'Màu sắc',
            'routePrefix' => 'colors',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'value' => 'required|string|max:255|unique:colors,value',
        ]);

        Color::create(['value' => $request->value]);

        return redirect()->route('colors.index')->with('success', 'Thêm màu sắc thành công.');
    }

    public function edit($id)
    {
        $colors = Color::findOrFail($id);

        return view('admin.colors.edit', [
            'colors' => $colors,
            'type' => 'Màu sắc',
            'routePrefix' => 'colors',
        ]);
    }

    public function update(Request $request, $id)
    {
        $colors = Color::findOrFail($id);

        $request->validate([
            'value' => 'required|string|max:255|unique:colors,value,' . $colors->id,
        ]);

        $colors->update(['value' => $request->value]);

        return redirect()->route('colors.index')->with('success', 'Cập nhật màu sắc thành công.');
    }

    public function destroy($id)
    {
        $colors = Color::findOrFail($id);
        $colors->delete();

        return redirect()->route('colors.index')->with('success', 'Xóa màu sắc thành công.');
    }
}
