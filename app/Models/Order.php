<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'house_number',
        'additions',
        'postal_code',
        'total_price',
        'completed',
        'session_id',
        'products', // mis deze weghalen todo
    ];

    /**
     * @var array
     */
    protected $casts = [
      'products' => 'array'
    ];
}
