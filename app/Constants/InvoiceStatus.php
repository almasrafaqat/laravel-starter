<?php

namespace App\Constants;

class InvoiceStatus
{
    const PENDING = 'pending';
    const PROCESSING = 'processing';
    const SHIPPED = 'shipped';
    const DELIVERED = 'delivered';
    const COMPLETED = 'completed';
    const FAILED = 'failed';
    const REFUNDED = 'refunded';
    const CANCELLED = 'cancelled';

    public static function getAll(): array
    {
        return [
            self::PENDING,
            self::PROCESSING,
            self::SHIPPED,
            self::DELIVERED,
            self::COMPLETED,
            self::FAILED,
            self::REFUNDED,
            self::CANCELLED,
        ];
    }
}