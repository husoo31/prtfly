<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class BlogYazisi extends Model
{
    protected $table = 'blog_yazilari';

    protected $fillable = [
        'baslik', 'slug', 'kategori', 'kapak', 'ozet', 'icerik', 'yazar', 'etiketler',
        'yayin_tarihi', 'aktif',
    ];

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
            'yayin_tarihi' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (BlogYazisi $yazi) {
            $yazi->slug = static::benzersizSlug($yazi->slug ?: $yazi->baslik, $yazi->id);
            $yazi->yayin_tarihi ??= now();
        });
    }

    public static function benzersizSlug(string $kaynak, ?int $haricId = null): string
    {
        $temel = Str::slug($kaynak) ?: 'yazi';
        $slug = $temel;
        $i = 2;

        while (static::query()->where('slug', $slug)->when($haricId, fn ($q) => $q->whereKeyNot($haricId))->exists()) {
            $slug = $temel.'-'.$i++;
        }

        return $slug;
    }

    public function scopeYayinda(Builder $sorgu): Builder
    {
        return $sorgu->where('aktif', true)
            ->where('yayin_tarihi', '<=', now())
            ->orderByDesc('yayin_tarihi')
            ->orderByDesc('id');
    }

    /** Markdown içeriği güvenli HTML'e çevirir (ham HTML atılır). */
    public function icerikHtml(): HtmlString
    {
        return new HtmlString(Str::markdown((string) $this->icerik, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]));
    }

    public function okumaSuresi(): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags((string) $this->icerik)) / 200));
    }

    public function etiketListesi(): array
    {
        return array_values(array_filter(array_map('trim', explode(',', (string) $this->etiketler))));
    }

    public function ozetMetni(int $uzunluk = 160): string
    {
        return $this->ozet ?: Str::limit(strip_tags((string) Str::markdown((string) $this->icerik)), $uzunluk);
    }
}
