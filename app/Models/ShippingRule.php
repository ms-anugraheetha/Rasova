<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'min_order_amount_minor', 'free_above_amount_minor',
        'flat_fee_minor', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'min_order_amount_minor' => 'integer',
            'free_above_amount_minor' => 'integer',
            'flat_fee_minor' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}