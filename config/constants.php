<?php

return [
    'product_status' => [
        1 => 'inactive',
        2 => 'active',
        3 => 'unavailable',
        4 => 'retired',
    ],

    'order_status' => [
        1 => 'pending_confirm',
        2 => 'pending_payment',
        3 => 'processing',
        4 => 'ready',
        5 => 'shipped',
        6 => 'delivered',
        7 => 'completed',
        8 => 'canceled',
        9 => 'return',
        10 => 'hold',
    ],

    'payment_status' => [
        1 => 'pending',
        2 => 'cash',
        3 => 'waiting_for_capture',
        4 => 'succeeded',
        5 => 'canceled',
        6 => 'refund_succeeded',
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
    'website_url' => env('APP_URL', 'https//:zoopodarki.spb.ru'),
    'manager_mail' => env('MANAGER_MAIL', 'manager@zoopodarki.spb.ru'),
    'boxberry_token' => env('BOXBERRY_TOKEN', '1111'),
    'here_com_token' => env('HERE_COM_TOKEN', '1111'),
    'lat_departure' => env('LAT_DEPARTURE', '59.91954757368843'),
    'lng_departure' => env('LNG_DEPARTURE', '30.467041076550657'),

];
