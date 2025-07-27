<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
   public function store(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'content' => 'required|string|min:2',
        'rating' => 'nullable|integer|min:1|max:5',
    ]);

    Comment::create([
        'product_id' => $request->product_id,
        'user_id' => Auth::id(), // <--- LẤY ID NGƯỜI DÙNG ĐĂNG NHẬP
        'content' => $request->content,
        'rating' => $request->rating,
        'parent_id' => $request->parent_id, // Có thể null
    ]);

    return back()->with('success', 'Đã gửi bình luận');
}
};