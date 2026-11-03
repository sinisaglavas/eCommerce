@php
    $statusClass = match ($order->status) {
        'processing' => 'text-bg-warning',
        'completed' => 'text-bg-success',
        'payment_failed' => 'text-bg-danger',
        'shipped' => 'text-dark',
        default => 'text-bg-secondary',
    };
@endphp

<div class="d-flex flex-wrap align-items-center justify-content-md-end gap-2">
    <span class="badge rounded-pill {{ $statusClass }} px-3 py-2" @if ($order->status === 'shipped') style="background-color: #fd7e14" @endif>{{ $order->statusLabel() }}</span>
    @if ($order->payment_status === 'paid' && in_array($order->status, ['paid', 'processing', 'shipped', 'completed'], true))
        <span class="d-inline-flex align-items-center gap-2 rounded-pill border border-success px-3 py-2 small text-success-emphasis bg-success-subtle">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="m7 12 3 3 7-7"/></svg>
            <span>Payment confirmed · {{ $order->status === 'completed' ? 'Delivered' : 'Delivery process started' }}</span>
        </span>
    @else
        <span class="badge rounded-pill text-bg-secondary px-3 py-2">{{ $order->paymentStatusLabel() }}</span>
    @endif
</div>
