<?php

use App\Models\Ayar;
use App\Support\Tema;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

if (! function_exists('tema')) {
    /** Tema değerini okur: tema('hero.isim'). Parametresiz çağrı servisi döndürür. */
    function tema(?string $yol = null, mixed $varsayilan = null): mixed
    {
        $servis = app(Tema::class);

        return $yol === null ? $servis : $servis->al($yol, $varsayilan);
    }
}

if (! function_exists('tema_vurgu')) {
    /** {süslü parantez} içindeki metni vurgu rengiyle sarar; geri kalanı kaçışlanır. */
    function tema_vurgu(?string $metin): HtmlString
    {
        $guvenli = e((string) $metin);
        $guvenli = preg_replace('/\{([^{}]+)\}/u', '<span class="vurgu">$1</span>', $guvenli);

        return new HtmlString(nl2br($guvenli, false));
    }
}

if (! function_exists('tema_satirlar')) {
    /** Çok satırlı alanı boş olmayan satır dizisine çevirir. */
    function tema_satirlar(?string $metin): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\R/u', (string) $metin)), 'strlen'));
    }
}

if (! function_exists('tema_url')) {
    /** Görsel/bağlantı değerini tam adrese çevirir. */
    function tema_url(?string $deger): string
    {
        $deger = trim((string) $deger);

        if ($deger === '' || Str::startsWith($deger, ['http://', 'https://', '//', '#', 'mailto:', 'tel:'])) {
            return $deger;
        }

        return asset(ltrim($deger, '/'));
    }
}

if (! function_exists('tema_baglanti')) {
    /**
     * Menü bağlantılarını çözer. "#bolum" adresleri ana sayfa dışındaki
     * sayfalarda ana sayfanın ilgili bölümüne gider.
     */
    function tema_baglanti(?string $deger): string
    {
        $deger = trim((string) $deger);

        if (Str::startsWith($deger, '#') && ! request()->routeIs('anasayfa')) {
            return route('anasayfa').$deger;
        }

        return tema_url($deger);
    }
}

if (! function_exists('tema_harici')) {
    /** Bağlantı başka bir siteye mi gidiyor? (target="_blank" için) */
    function tema_harici(?string $deger): bool
    {
        $deger = (string) $deger;

        return Str::startsWith($deger, ['http://', 'https://', '//'])
            && parse_url($deger, PHP_URL_HOST) !== request()->getHost();
    }
}

if (! function_exists('tema_logo')) {
    function tema_logo(): HtmlString
    {
        return new HtmlString(
            '<span '.dt('genel.logo_bas').'>'.e(tema('genel.logo_bas')).'</span>'
            .'<span class="vurgu" '.dt('genel.logo_vurgu').'>'.e(tema('genel.logo_vurgu')).'</span>'
            .'<span '.dt('genel.logo_son').'>'.e(tema('genel.logo_son')).'</span>'
        );
    }
}

if (! function_exists('tema_telif')) {
    function tema_telif(?string $metin = null): string
    {
        return str_replace('{yil}', date('Y'), $metin ?? (string) tema('alt.telif'));
    }
}

if (! function_exists('ayar')) {
    /** Tema dışı site ayarı (bildirim e-postası vb.). */
    function ayar(string $anahtar, mixed $varsayilan = null): mixed
    {
        try {
            return Ayar::oku($anahtar, $varsayilan);
        } catch (Throwable) {
            return $varsayilan;
        }
    }
}

if (! function_exists('dt')) {
    /** data-tema öznitelikleri: yalnızca önizleme modunda yazılır. */
    function dt(string $yol, ?string $oznitelik = null): HtmlString
    {
        if (! tema()->onizlemeModu()) {
            return new HtmlString('');
        }

        $html = 'data-tema="'.e($yol).'"';

        if ($oznitelik) {
            $html .= ' data-tema-oz="'.e($oznitelik).'"';
        }

        return new HtmlString($html);
    }
}
