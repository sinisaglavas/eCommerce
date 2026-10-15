<?php

namespace App\Models;

use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public const array STATUS_LABELS = [
        'awaiting_payment' => 'Awaiting Payment',
        'paid' => 'Paid',
        'processing' => 'Processing',
        'shipped' => 'Shipped',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
        'payment_failed' => 'Payment Failed',
        'pending' => 'Pending',
    ];

    private const array STATUS_TRANSITIONS = [
        'awaiting_payment' => ['paid', 'cancelled', 'payment_failed'],
        'paid' => ['processing', 'cancelled'],
        'processing' => ['shipped', 'cancelled'],
        'shipped' => ['completed'],
        'completed' => [],
        'cancelled' => [],
        'payment_failed' => ['awaiting_payment', 'cancelled'],
        'pending' => ['awaiting_payment', 'cancelled'],
    ];

    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id', 'number', 'status', 'first_name', 'last_name', 'email', 'phone',
        'address_line_1', 'address_line_2', 'city', 'postal_code', 'country', 'total',
    ];

    protected function casts(): array
    {
        return ['total' => 'decimal:2'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function canTransitionTo(string $status): bool
    {
        return $status === $this->status
            || in_array($status, self::STATUS_TRANSITIONS[$this->status] ?? [], true);
    }
}
