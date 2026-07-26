<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number', 'user_id', 'address_id', 'guest_email', 'guest_phone',
        'subtotal_minor', 'shipping_fee_minor', 'gst_amount_minor', 'total_minor',
        'currency', 'shipping_full_name', 'shipping_phone', 'shipping_address_line_1',
        'shipping_address_line_2', 'shipping_landmark', 'shipping_city', 'shipping_state',
        'shipping_country', 'shipping_postal_code', 'payment_status', 'order_status',
        'tracking_number', 'courier_name', 'shipped_at', 'delivered_at',
        'gift_order', 'gift_message', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'subtotal_minor' => 'integer',
            'shipping_fee_minor' => 'integer',
            'gst_amount_minor' => 'integer',
            'total_minor' => 'integer',
            'gift_order' => 'boolean',
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}