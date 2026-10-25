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

    public const array PAYMENT_STATUS_LABELS = [
        'unpaid' => 'Unpaid',
        'pending' => 'Payment Pending',
        'paid' => 'Paid',
        'failed' => 'Payment Failed',
        'refunded' => 'Refunded',
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
        'user_id', 'number', 'status', 'payment_status', 'payment_method', 'tracking_number',
        'shipping_carrier', 'estimated_delivery_at', 'shipped_at', 'delivered_at',
        'first_name', 'last_name', 'email', 'phone', 'address_line_1', 'address_line_2',
        'city', 'postal_code', 'country', 'total',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'decimal:2',
            'estimated_delivery_at' => 'datetime',
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class)->oldest();
    }

    public function statusLabel(): string
    {
        return self::STATUS_LABELS[$this->status] ?? str($this->status)->headline()->toString();
    }

    public function paymentStatusLabel(): string
    {
        return self::PAYMENT_STATUS_LABELS[$this->payment_status] ?? str($this->payment_status)->headline()->toString();
    }

    public function canTransitionTo(string $status): bool
    {
        return $status === $this->status
            || in_array($status, self::STATUS_TRANSITIONS[$this->status] ?? [], true);
    }
}
