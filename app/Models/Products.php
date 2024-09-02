<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    protected $fillable = ['brand', 'description', 'category'];

    public function ProductMembers()
    {
        return $this->hasMany(ProductMembers::class, 'product_id');
    }
}
