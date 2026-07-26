<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTransaction extends Model
{
    use HasFactory;

    public $timestamps = false; // only has created_at, no updated_at

    protected $fillable = [
        'payment_id', 'transaction_type', 'gateway_transaction_id',
        'gateway_event_id', 'amount_minor', 'response_code',
        'response_message', 'gateway_response',
    ];

    protected function casts(): array
    {
        return ['amount_minor' => 'integer'];
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}