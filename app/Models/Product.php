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

    // Quan hệ nhiều-nhiều: sản phẩm có thể có nhiều khuyến mãi
    public function promotions()
    {
        return $this->belongsToMany(Promotion::class);
    }

    // Quan hệ 1-nhiều: 1 sản phẩm có nhiều biến thể
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
