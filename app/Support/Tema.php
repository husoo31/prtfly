<?php

namespace App\Support;

use App\Models\Ayar;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Throwable;

/**
 * Tema değerlerini okur, varsayılanlarla birleştirir, doğrular ve kaydeder.
 *
 * Yayınlanan değerler `ayarlar` tablosunda "tema" anahtarıyla JSON olarak
 * tutulur. Tema düzenleyicideki kaydedilmemiş değişiklikler oturumda
 * taslak olarak bekler ve yalnızca önizleme modunda kullanılır.
 */
class Tema
{
    public const AYAR_ANAHTARI = 'tema';

    public const TASLAK_ANAHTARI = 'tema_taslak';

    protected ?array $degerler = null;

    protected bool $onizleme = false;

    public function sema(): array
    {
        return config('tema.bolumler', []);
    }

    public function onizlemeModu(?bool $durum = null): bool
    {
        if ($durum !== null && $durum !== $this->onizleme) {
            $this->onizleme = $durum;
            $this->degerler = null;
        }

        return $this->onizleme;
    }

    /** Sitede kullanılacak (önizlemedeyse taslak) birleştirilmiş değerler. */
    public function degerler(): array
    {
        if ($this->degerler !== null) {
            return $this->degerler;
        }

        $kaynak = $this->yayinlanan();

        if ($this->onizleme && is_array($taslak = session(self::TASLAK_ANAHTARI))) {
            $kaynak = $taslak;
        }

        return $this->degerler = $this->birlestir($kaynak);
    }

    public function al(?string $yol = null, mixed $varsayilan = null): mixed
    {
        if ($yol === null) {
            return $this->degerler();
        }

        return data_get($this->degerler(), $yol, $varsayilan);
    }

    /** Veritabanında kayıtlı (yayınlanmış) ham değerler. */
    public function yayinlanan(): array
    {
        try {
            $json = Ayar::oku(self::AYAR_ANAHTARI);
        } catch (Throwable) {
            return [];
        }

        $veri = is_string($json) ? json_decode($json, true) : null;

        return is_array($veri) ? $veri : [];
    }

    /** Tema düzenleyicinin açılışta göreceği değerler (taslak varsa taslak). */
    public function duzenleyiciDegerleri(): array
    {
        $taslak = session(self::TASLAK_ANAHTARI);

        return $this->birlestir(is_array($taslak) ? $taslak : $this->yayinlanan());
    }

    public function taslakVarMi(): bool
    {
        return is_array(session(self::TASLAK_ANAHTARI));
    }

    public function taslakKaydet(array $degerler): void
    {
        session([self::TASLAK_ANAHTARI => $degerler]);
        $this->degerler = null;
    }

    public function taslakSil(): void
    {
        session()->forget(self::TASLAK_ANAHTARI);
        $this->degerler = null;
    }

    public function yayinla(array $degerler): void
    {
        Ayar::yaz(self::AYAR_ANAHTARI, json_encode($degerler, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        $this->taslakSil();
    }

    public function varsayilanlar(): array
    {
        return $this->birlestir([]);
    }

    /** Kayıtlı değerleri şemadaki varsayılanların üzerine giydirir. */
    public function birlestir(array $kayitli): array
    {
        $sonuc = [];

        foreach ($this->sema() as $bolumAnahtari => $bolum) {
            $bolumDegeri = is_array($kayitli[$bolumAnahtari] ?? null) ? $kayitli[$bolumAnahtari] : [];

            foreach ($bolum['alanlar'] as $alanAnahtari => $alan) {
                $sonuc[$bolumAnahtari][$alanAnahtari] = array_key_exists($alanAnahtari, $bolumDegeri)
                    ? $this->alanBirlestir($alan, $bolumDegeri[$alanAnahtari])
                    : $alan['varsayilan'] ?? null;
            }
        }

        return $sonuc;
    }

    protected function alanBirlestir(array $alan, mixed $deger): mixed
    {
        if (($alan['tip'] ?? null) !== 'liste') {
            return $deger;
        }

        if (! is_array($deger)) {
            return $alan['varsayilan'] ?? [];
        }

        return array_values(array_map(function ($oge) use ($alan) {
            $oge = is_array($oge) ? $oge : [];
            $tam = [];

            foreach ($alan['alanlar'] as $altAnahtar => $altAlan) {
                $tam[$altAnahtar] = $oge[$altAnahtar] ?? ($altAlan['varsayilan'] ?? '');
            }

            return $tam;
        }, $deger));
    }

    /**
     * Düzenleyiciden gelen girdiyi şemaya göre süzer: bilinmeyen anahtarlar
     * atılır, tipler zorlanır, uzunluklar sınırlanır.
     */
    public function temizle(array $girdi): array
    {
        $temiz = [];

        foreach ($this->sema() as $bolumAnahtari => $bolum) {
            $bolumGirdisi = is_array($girdi[$bolumAnahtari] ?? null) ? $girdi[$bolumAnahtari] : [];

            foreach ($bolum['alanlar'] as $alanAnahtari => $alan) {
                if (! array_key_exists($alanAnahtari, $bolumGirdisi)) {
                    continue;
                }

                $temiz[$bolumAnahtari][$alanAnahtari] = $this->alanTemizle($alan, $bolumGirdisi[$alanAnahtari]);
            }
        }

        return $temiz;
    }

    protected function alanTemizle(array $alan, mixed $deger): mixed
    {
        switch ($alan['tip']) {
            case 'anahtar':
                return filter_var($deger, FILTER_VALIDATE_BOOLEAN);

            case 'sayi':
                return max(0, min(100, (int) $deger));

            case 'renk':
                $deger = is_string($deger) ? trim($deger) : '';

                return preg_match('/^#[0-9a-fA-F]{3}([0-9a-fA-F]{3})?$/', $deger) ? strtolower($deger) : ($alan['varsayilan'] ?? '#000000');

            case 'secim':
            case 'ikon':
                $secenekler = array_keys($alan['secenekler'] ?? []);

                return in_array($deger, $secenekler, true) ? $deger : ($alan['varsayilan'] ?? ($secenekler[0] ?? ''));

            case 'liste':
                if (! is_array($deger)) {
                    return [];
                }

                $ogeler = [];

                foreach (array_slice(array_values($deger), 0, 50) as $oge) {
                    if (! is_array($oge)) {
                        continue;
                    }

                    $temizOge = [];

                    foreach ($alan['alanlar'] as $altAnahtar => $altAlan) {
                        $temizOge[$altAnahtar] = $this->alanTemizle($altAlan, $oge[$altAnahtar] ?? ($altAlan['varsayilan'] ?? ''));
                    }

                    $ogeler[] = $temizOge;
                }

                return $ogeler;

            case 'gorsel':
            case 'baglanti':
                $deger = is_scalar($deger) ? trim((string) $deger) : '';

                // javascript:, data: vb. tehlikeli şemaları engelle.
                if (preg_match('/^\s*(javascript|data|vbscript):/i', $deger)) {
                    return '';
                }

                return Str::limit($deger, 2000, '');

            default:
                $deger = is_scalar($deger) ? (string) $deger : '';
                $deger = str_replace("\r\n", "\n", $deger);

                return Str::limit($deger, ! empty($alan['ham']) ? 20000 : 5000, '');
        }
    }

    /** Bir alanın şema tanımı: "hero.isim" veya "hizmetler.ogeler.baslik". */
    public function alanTanimi(string $yol): ?array
    {
        $parcalar = explode('.', $yol);
        $bolum = $this->sema()[$parcalar[0]] ?? null;
        $alan = $bolum['alanlar'][$parcalar[1] ?? ''] ?? null;

        if ($alan && isset($parcalar[2]) && ($alan['tip'] ?? null) === 'liste') {
            return $alan['alanlar'][$parcalar[2]] ?? null;
        }

        return $alan;
    }

    /** Şemanın JS tarafına verilecek sade hali. */
    public function istemciSemasi(): array
    {
        return Arr::map($this->sema(), fn ($bolum) => [
            'etiket' => $bolum['etiket'],
            'ikon' => $bolum['ikon'] ?? 'bi-square',
            'aciklama' => $bolum['aciklama'] ?? null,
            'alanlar' => $bolum['alanlar'],
        ]);
    }
}
