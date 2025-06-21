<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::with(['product', 'user'])->latest()->paginate(10);
        return view('admin.comments.index', compact('comments'));
    }

    public function edit($id)
    {
        $comment = Comment::findOrFail($id);
        return view('admin.comments.edit', compact('comment'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|min:1',
        ]);

        $comment = Comment::findOrFail($id);
        $comment->update(['content' => $request->content]);

        return redirect()->route('comments.index')->with('success', 'Cập nhật bình luận thành công.');
    }

    public function destroy($id)
    {
        Comment::destroy($id);
        return redirect()->route('comments.index')->with('success', 'Xóa bình luận thành công.');
    }
    public function create()
{
    $products = \App\Models\Product::all();
    $users = \App\Models\Account::all(); // Nếu muốn chọn người bình luận
    return view('admin.comments.create', compact('products', 'users'));
}

public function store(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'user_id' => 'required|exists:accounts,id',
        'content' => 'required|string|min:1',
        'parent_id' => 'nullable|exists:comments,id',
    ]);

    \App\Models\Comment::create($request->only('product_id', 'user_id', 'content', 'parent_id'));

    return redirect()->route('comments.index')->with('success', 'Đã thêm bình luận mới.');
}

}
