<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'date',
        'customer_name',
        'status',
        'total',
    ];

    public function items()
    {
        return $this->belongsToMany(Category::class, 'transaction_items')
            ->withPivot([
                'name',
                'qty',
                'unit',
                'price',
                'total',
            ]);
    }
}
