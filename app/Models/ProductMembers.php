<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMembers extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'member_id'];

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

    public function user()
{
    return $this->belongsTo(User::class, 'member_id');
}
}
