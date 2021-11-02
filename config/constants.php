<?php

return [
    'product_status' => [
        1 => 'inactive',
        2 => 'active',
        3 => 'unavailable',
        4 => 'retired',
    ],

    'order_status' => [
        1 => 'pending_payment',
        2 => 'processing',
        3 => 'ready',
        4 => 'shipped',
        5 => 'delivered',
        6 => 'completed',
        7 => 'canceled',
        8 => 'return',
        9 => 'hold',
    ],

    'payment_status' => [
        1 => 'pending',
        2 => 'waiting_for_capture',
        3 => 'succeeded',
        4 => 'canceled',
        5 => 'refund_succeeded',
    ],

    'review_status' => [
        1 => 'pending',
        2 => 'published',
        3 => 'cancelled',
        3 => 'baned',
    ],

    'waitlist_status' => [
        1 => 'pending',
        2 => 'ordered',
        3 => 'notified',
    ],

    'phone' => env('ZOO_PHONE', '+71234567890'),
    'boxberry_token' => env('BOXBERRY_TOKEN', '77cd81a164c86a1f67ccb0ac9e822c1e'),


];
