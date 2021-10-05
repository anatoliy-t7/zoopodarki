<?php
/**
 * @see https://github.com/artesaos/seotools
 */

return [
    'meta' => [
        /*
         * The default configurations to be used by the meta generator.
         */
        'defaults' => [
            'title' => config('app.name'), // set false to total remove
            'titleBefore' => false, // Put defaults.title before page title, like 'It's Over 9000! - Dashboard'
            'description' => 'Интернет магазин товаров для животных ZooPodarki. Зоотовары для собак, кошек, рыб, птиц, грызунов, рептилий.', // set false to total remove
            'separator' => ' | ',
            'keywords' => [],
            'canonical' => 'current', // Set to null or 'full' to use Url::full(), set to 'current' to use Url::current(), set false to total remove
            'robots' => false, // Set to 'all', 'none' or any combination of index/noindex and follow/nofollow
        ],
        /*
         * Webmaster tags are always added.
         */
        'webmaster_tags' => [
            'google' => null,
            'pinterest' => null,
            'yandex' => null,
        ],

        'add_notranslate_class' => false,
    ],
    'opengraph' => [
        /*
         * The default configurations to be used by the opengraph generator.
         */
        'defaults' => [
            'title' => 'Интернет магазин товаров для животных ZooPodarki', // set false to total remove
            'description' => 'Интернет магазин товаров для животных ZooPodarki. Зоотовары для собак, кошек, рыб, птиц, грызунов, рептилий.', // set false to total remove
            'url' => null, // Set null for using Url::current(), set false to total remove
            'type' => false,
            'site_name' => false,
            'images' => [config('app.url') . '/assets/img/logo.png'],
        ],
    ],
    'twitter' => [
        /*
         * The default values to be used by the twitter cards generator.
         */
        'defaults' => [
            //'card'        => 'summary',
            //'site'        => '@LuizVinicius73',
        ],
    ],
    'json-ld' => [
        /*
         * The default configurations to be used by the json-ld generator.
         */
        'defaults' => [
            'title' => false, // set false to total remove
            'description' => false, // set false to total remove
            'url' => false, // Set null for using Url::current(), set false to total remove
            'type' => false,
            'images' => [],
        ],
    ],
];
