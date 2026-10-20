<?php

return [

    // Yazılım sürümü (yönetim panelinin altında görünür).
    'surum' => '1.1.0',

    // Web kurulum sihirbazı tamamlandığında .env içinde true yapılır.
    'kuruldu' => (bool) env('APP_INSTALLED', false),

    // Yüklenen görsellerin public/ altındaki klasörü.
    'yukleme_klasoru' => 'uploads',

    // Yüklenebilecek en büyük görsel (KB).
    'yukleme_sinir_kb' => 5120,

    'dagitici' => [
        'ad' => 'webdanismani.com',
        'site' => 'https://webdanismani.com',
        'forum' => 'https://oblifex.com',
        'depo' => 'https://github.com/webdanismani/laravel-portfolyo',
    ],

];
