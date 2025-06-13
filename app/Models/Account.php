<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
     use HasFactory;

    protected $fillable = [
        'role_id', 'full_name', 'avatar', 'date_of_birth',
        'email', 'phone', 'gender', 'address', 'password'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
