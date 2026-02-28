<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_product',
        'name',
        'price',
        'stock',
    ];
    public function detailTransactions(): HasMany
    {
        return $this->hasMany(DetailTransaction::class);
    }
}
