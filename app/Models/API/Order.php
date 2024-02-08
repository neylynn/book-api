<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'shipping_address', 'payment_info'];

    public function books()
    {
        return $this->belongsToMany(Book::class)->withPivot('quantity');
    }

}
