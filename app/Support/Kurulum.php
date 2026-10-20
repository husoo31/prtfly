<?php

namespace App\Support;

use Illuminate\Support\Facades\File;

/** Kurulum durumu ve .env dosyası işlemleri. */
class Kurulum
{
    public static function isaretDosyasi(): string
    {
        return storage_path('app/kuruldu');
    }

    public static function kurulduMu(): bool
    {
        return config('portfolyo.kuruldu') || is_file(static::isaretDosyasi());
    }

    public static function tamamlandiIsaretle(): void
    {
        File::put(static::isaretDosyasi(), now()->toIso8601String());
        static::envYaz(['APP_INSTALLED' => 'true']);
    }

    /** SQLite seçiliyse ve veritabanı dosyası yoksa boş dosyayı oluşturur. */
    public static function veritabaniHazirla(): void
    {
        if (config('database.default') !== 'sqlite') {
            return;
        }

        $yol = config('database.connections.sqlite.database');

        if ($yol && $yol !== ':memory:' && ! is_file($yol)) {
            File::ensureDirectoryExists(dirname($yol));
            File::put($yol, '');
        }
    }

    /**
     * APP_KEY boşsa üretir ve .env dosyasına yazar. .env hiç yoksa
     * .env.example kopyalanır. Böylece paket sunucuya yüklendiği anda
     * oturum ve CSRF sorunsuz çalışır.
     */
    public static function anahtarHazirla(): void
    {
        if (config('app.key')) {
            return;
        }

        $anahtar = 'base64:'.base64_encode(random_bytes(32));

        try {
            static::envYaz(['APP_KEY' => $anahtar]);
        } catch (\Throwable) {
            // Yazılamıyorsa en azından bu istek için anahtar ver.
        }

        config(['app.key' => $anahtar]);
    }

    /** .env içindeki anahtarları günceller, olmayanları sona ekler. */
    public static function envYaz(array $degerler): void
    {
        $env = base_path('.env');

        if (! is_file($env)) {
            $ornek = base_path('.env.example');
            File::put($env, is_file($ornek) ? File::get($ornek) : '');
        }

        $icerik = File::get($env);

        foreach ($degerler as $anahtar => $deger) {
            $satir = $anahtar.'='.static::envDegeri((string) $deger);
            $desen = '/^'.preg_quote($anahtar, '/').'=.*$/m';

            $icerik = preg_match($desen, $icerik)
                ? preg_replace_callback($desen, fn () => $satir, $icerik)
                : rtrim($icerik)."\n".$satir."\n";
        }

        File::put($env, $icerik);
    }

    protected static function envDegeri(string $deger): string
    {
        if ($deger === '' || preg_match('/^[A-Za-z0-9_.:\/+=@-]+$/', $deger)) {
            return $deger;
        }

        return '"'.str_replace(['\\', '"', '$'], ['\\\\', '\\"', '\\$'], $deger).'"';
    }
}
