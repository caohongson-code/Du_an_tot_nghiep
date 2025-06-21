<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class adminCategoriesModel extends Model
{
    protected $table ='categories';
    protected $fillable  =[
        'category_name',
        'description',

    ];
}
