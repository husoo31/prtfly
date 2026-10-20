@php
    $onizleme = tema()->onizlemeModu();
    $sayfaBasligi = trim($__env->yieldContent('baslik'));
    $baslik = $sayfaBasligi !== '' ? $sayfaBasligi.' — '.tema('genel.site_adi') : tema('seo.baslik');
    $aciklama = trim($__env->yieldContent('aciklama')) ?: tema('seo.aciklama');
    $ogGorsel = trim($__env->yieldContent('og_gorsel')) ?: tema('seo.og_gorsel');
    $govdeSiniflari = collect([
        tema('genel.mod') === 'koyu' ? 'koyu' : null,
        tema('genel.animasyon') ? null : 'animasyonsuz',
        $onizleme ? 'onizleme' : null,
        (tema('genel.onyukleyici') && ! $onizleme) ? null : 'yuklendi',
    ])->filter()->implode(' ');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $baslik }}</title>
    <meta name="description" content="{{ $aciklama }}">
    @if (tema('seo.anahtar_kelimeler'))
        <meta name="keywords" content="{{ tema('seo.anahtar_kelimeler') }}">
    @endif
    <meta name="generator" content="Laravel Portfolyo — webdanismani.com">
    @if ($onizleme)
        <meta name="robots" content="noindex, nofollow">
        <meta name="temel-url" content="{{ asset('') }}">
    @endif
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $baslik }}">
    <meta property="og:description" content="{{ $aciklama }}">
    <meta property="og:url" content="{{ url()->current() }}">
    @if ($ogGorsel)
        <meta property="og:image" content="{{ tema_url($ogGorsel) }}">
        <meta name="twitter:card" content="summary_large_image">
    @endif

    @if (tema('genel.favicon'))
        <link rel="icon" href="{{ tema_url(tema('genel.favicon')) }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Syne:wght@500;600;700;800&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap-utilities.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/site.css') }}?v={{ config('portfolyo.surum') }}">

    <style id="tema-renkler">
        :root {
            --renk-vurgu: {{ tema('genel.renk_vurgu') }};
            --renk-koyu: {{ tema('genel.renk_koyu') }};
            --renk-gri: {{ tema('genel.renk_gri') }};
        }
    </style>

    @unless ($onizleme)
        {!! tema('seo.ust_kod') !!}
    @endunless
</head>
<body class="{{ $govdeSiniflari }}">

    @if (tema('genel.onyukleyici') && ! $onizleme)
        <div class="onyukleyici" aria-hidden="true">
            <div class="yari sol"></div>
            <div class="yari sag"></div>
            <div class="onyukleyici-logo">
                @foreach (mb_str_split(tema('genel.logo_bas').tema('genel.logo_vurgu').tema('genel.logo_son')) as $i => $harf)
                    <span style="animation-delay: {{ $i * 0.08 }}s" @class(['vurgu' => $i === mb_strlen(tema('genel.logo_bas'))])>{{ $harf }}</span>
                @endforeach
            </div>
        </div>
    @endif

    <button type="button" class="yukari-cik" aria-label="Yukarı çık">
        <svg viewBox="0 0 46 46"><circle cx="23" cy="23" r="21.5" /></svg>
        <i class="bi bi-arrow-up"></i>
    </button>

    @include('site.parcalar.ust')

    <main id="home">
        @yield('icerik')
    </main>

    @include('site.parcalar.alt')
    @include('site.parcalar.yan-panel')
    @stack('pencereler')

    <script src="{{ asset('assets/js/site.js') }}?v={{ config('portfolyo.surum') }}"></script>
    @if ($onizleme)
        <script src="{{ asset('assets/js/onizleme.js') }}?v={{ config('portfolyo.surum') }}"></script>
    @endif
    @stack('betikler')
</body>
</html>
