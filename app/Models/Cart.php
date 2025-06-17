<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['account_id'];

    public function account() {
        return $this->belongsTo(Account::class);
    }

    public function details() {
        return $this->hasMany(CartDetail::class);
    }
}



?>