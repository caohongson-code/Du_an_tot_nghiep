<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Account;
use App\Models\Product;

class Comment extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'content',
        'rating',
        'parent_id',
    ];

    // Mối quan hệ: comment thuộc về sản phẩm
    public function product()
    {
        return $this->belongsTo(Product::class) ;// để hiện cả sản phẩm đã xóa mềm
    }

    // Mối quan hệ: comment thuộc về người dùng
    public function user()
    {
        return $this->belongsTo(Account::class, 'user_id');
    }

    // Mối quan hệ: comment cha
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    // Mối quan hệ: danh sách các trả lời (reply)
   public function replies()
{
    return $this->hasMany(Comment::class, 'parent_id')->orderBy('created_at');
}

}
