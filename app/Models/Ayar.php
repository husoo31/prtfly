<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ayar extends Model
{
    protected $table = 'ayarlar';

    protected $fillable = ['anahtar', 'deger'];

    /** İstek boyunca okunan ayarların önbelleği. */
    protected static array $bellek = [];

    public static function oku(string $anahtar, mixed $varsayilan = null): mixed
    {
        if (! array_key_exists($anahtar, static::$bellek)) {
            static::$bellek[$anahtar] = static::query()->where('anahtar', $anahtar)->value('deger');
        }

        return static::$bellek[$anahtar] ?? $varsayilan;
    }

    public static function yaz(string $anahtar, mixed $deger): void
    {
        static::query()->updateOrCreate(['anahtar' => $anahtar], ['deger' => $deger]);
        static::$bellek[$anahtar] = $deger;
    }

    public static function bellegiTemizle(): void
    {
        static::$bellek = [];
    }
}
