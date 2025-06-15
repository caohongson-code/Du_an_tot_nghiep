<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function getVariant(Request $request)
    {
        $variant = ProductVariant::with(['ram', 'storage', 'color'])
            ->where('product_id', $request->product_id)
            ->where('ram_id', $request->ram_id)
            ->where('storage_id', $request->storage_id)
            ->where('color_id', $request->color_id)
            ->first();

        if (!$variant) {
            return response()->json(['error' => 'Không tìm thấy biến thể'], 404);
        }

        return response()->json($variant);
    }
}

