<?php

namespace App\Models;

use App\Models\Filters\CreatedAfterFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Lacodix\LaravelModelFilter\Traits\HasFilters;

class Product extends Model
{
    use HasFactory;
    use HasFilters;

    protected array $filters = [
        CreatedAfterFilter::class,
    ];

    protected $fillable = [
        'title',
        'slug',
        'color',
        'thumbnail',
        'category',
        'price',
        'amount',
        'reserved',
        'weight',
        'description',
    ];

}
