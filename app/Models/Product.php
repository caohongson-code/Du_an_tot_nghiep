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
    public function category()

{
    return $this->belongsTo(Category::class);
}
public function variants()
{
    return $this->hasMany(ProductVariant::class);
}


}
