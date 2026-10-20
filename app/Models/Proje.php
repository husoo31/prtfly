<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Proje extends Model
{
    protected $table = 'projeler';

    protected $fillable = [
        'baslik', 'slug', 'kategori', 'musteri', 'tarih', 'kapak', 'gorsel_1', 'gorsel_2',
        'ozet', 'icerik', 'maddeler', 'baglanti', 'boyut', 'sira', 'aktif',
    ];

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
            'sira' => 'integer',
        ];
    }

    public const BOYUTLAR = [
        'normal' => 'Normal (1/3)',
        'orta' => 'Orta (2/5)',
        'buyuk' => 'Büyük (3/5)',
    ];

    protected static function booted(): void
    {
        static::saving(function (Proje $proje) {
            $proje->slug = static::benzersizSlug($proje->slug ?: $proje->baslik, $proje->id);
        });
    }

    public static function benzersizSlug(string $kaynak, ?int $haricId = null): string
    {
        $temel = Str::slug($kaynak) ?: 'proje';
        $slug = $temel;
        $i = 2;

        while (static::query()->where('slug', $slug)->when($haricId, fn ($q) => $q->whereKeyNot($haricId))->exists()) {
            $slug = $temel.'-'.$i++;
        }

        return $slug;
    }

    public function scopeYayinda(Builder $sorgu): Builder
    {
        return $sorgu->where('aktif', true)->orderBy('sira')->orderByDesc('id');
    }

    /** Filtre sınıfı: "Web Tasarım, UI/UX" → "k-web-tasarim k-ui-ux". */
    public function kategoriler(): array
    {
        return array_values(array_filter(array_map('trim', explode(',', (string) $this->kategori))));
    }

    public function filtreSiniflari(): string
    {
        return collect($this->kategoriler())->map(fn ($k) => 'k-'.Str::slug($k))->implode(' ');
    }

    public function maddeListesi(): array
    {
        return tema_satirlar($this->maddeler);
    }

    public static function tumKategoriler(): array
    {
        return static::query()->where('aktif', true)->pluck('kategori')
            ->flatMap(fn ($k) => array_map('trim', explode(',', (string) $k)))
            ->filter()->unique()->values()->all();
    }
}
