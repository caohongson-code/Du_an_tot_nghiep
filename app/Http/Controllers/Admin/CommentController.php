<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
   public function index()
{
      $comments = Comment::with(['product', 'user', 'replies.user'])
        ->orderBy('created_at', 'desc') // 👉 Sắp xếp mới nhất trước
        ->get();

    return view('admin.comments.index', compact('comments'));
}

  public function create()
{
    $products = Product::all(); // Lấy danh sách sản phẩm
    $users = User::all();       // Lấy danh sách user
    $comments = Comment::whereNull('parent_id')->get(); // Lấy bình luận gốc để làm "trả lời"

    return view('admin.comments.create', compact('products', 'users', 'comments'));
}

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'nullable|exists:users,id',
            'content' => 'required|string|min:2',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        Comment::create($request->only('product_id', 'user_id', 'content', 'rating','parent_id'));

        return redirect()->route('admin.comments.index')->with('success', 'Thêm bình luận thành công');
    }

    public function edit($id)
    {
        $comment = Comment::findOrFail($id);
        $products = Product::all();
        $users = User::all();
        return view('admin.comments.edit', compact('comment', 'products', 'users'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'nullable|exists:users,id',
            'content' => 'required|string|min:2',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        $comment = Comment::findOrFail($id);
        $comment->update($request->only('product_id', 'user_id', 'content', 'rating'));

        return redirect()->route('admin.comments.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy($id)
    {
        Comment::destroy($id);
        return back()->with('success', 'Xóa bình luận thành công');
    }

    public function reply(Request $request, Comment $comment)
    {
        $request->validate(['content' => 'required|string|min:2']);

        Comment::create([
            'product_id' => $comment->product_id,
            'user_id' => null,
            'content' => $request->content,
            'rating' => null,
            'parent_id' => $comment->id,
        ]);

        return back()->with('success', 'Đã trả lời bình luận');
    }
}
