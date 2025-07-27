<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'product_name',
        'price',
        'discount_price',
        'image',
        'quantity',
        'views',
        'description',
        'status',
    ];

    // Quan hệ 1 sản phẩm thuộc 1 danh mục
    public function category()

{
    return $this->belongsTo(Category::class);
}
public function variants()
{
    return $this->hasMany(ProductVariant::class);
}
public function comments()
{
     return $this->hasMany(Comment::class, 'product_id');
}

// App\Models\Product.php

public function reviews()
{
    return $this->hasMany(\App\Models\Review::class)->where('status', 1); // chỉ hiển thị đánh giá đã duyệt (nếu có)
}

}
