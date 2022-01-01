<?php

return [

    'phone' => env('ZOO_PHONE', ''),
    'phone2' => env('ZOO_PHONE2', ''),
    'website_url' => env('APP_URL', 'https//:zoopodarki.spb.ru'),
    'manager_mail' => env('MANAGER_MAIL', 'manager@zoopodarki.spb.ru'),
    'mail' => env('MAIL', 'zoopodarki@mail.ru'),
    'boxberry_token' => env('BOXBERRY_TOKEN', '1111'),
    'here_com_token' => env('HERE_COM_TOKEN', '1111'),
    'lat_departure' => env('LAT_DEPARTURE', '59.91954757368843'),
    'lng_departure' => env('LNG_DEPARTURE', '30.467041076550657'),

    'shelter_catalog_id' => env('SHELTER_CATALOG_ID', 14),

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

    'sort_type' => [
        '0' => [
            'name' => 'По популярности',
            'type' => 'popularity',
            'sort' => 'desc',
        ],
        '1' => [
            'name' => 'Название: от А до Я',
            'type' => 'name',
            'sort' => 'asc',
        ],
        '2' => [
            'name' => 'Название: от Я до А',
            'type' => 'name',
            'sort' => 'desc',
        ],
        '3' => [
            'name' => 'Цена по возрастанию',
            'type' => 'price_avg',
            'sort' => 'asc',
        ],
        '4' => [
            'name' => 'Цена по убыванию',
            'type' => 'price_avg',
            'sort' => 'desc',
        ],
    ],

];
