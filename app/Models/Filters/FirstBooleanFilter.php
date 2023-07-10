<?php

namespace App\Models\Filters;

use Lacodix\LaravelModelFilter\Filters\BooleanFilter;

class FirstBooleanFilter extends BooleanFilter
{
    public function options(): array
    {
        return [
            'published',
            'active',
            'boolvalue',
            'reserved'
        ];
    }
}
