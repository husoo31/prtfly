@php
    $okunmamis = \App\Models\Mesaj::where('okundu', false)->count();
    $menu = [
        ['yonetim.panel', 'bi-speedometer2', 'Gösterge Paneli', 'yonetim.panel'],
        ['yonetim.tema', 'bi-palette2', 'Tema Düzenleyici', 'yonetim.tema'],
        ['yonetim.projeler.index', 'bi-grid-1x2', 'Projeler', 'yonetim.projeler.*'],
        ['yonetim.blog.index', 'bi-journal-text', 'Blog', 'yonetim.blog.*'],
        ['yonetim.mesajlar.index', 'bi-envelope', 'Mesajlar', 'yonetim.mesajlar.*'],
        ['yonetim.ayarlar', 'bi-gear', 'Ayarlar', 'yonetim.ayarlar*'],
        ['yonetim.profil', 'bi-person-circle', 'Profil', 'yonetim.profil*'],
    ];
@endphp
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('baslik', 'Yönetim') — {{ tema('genel.site_adi') }}</title>
    @if (tema('genel.favicon'))
        <link rel="icon" href="{{ tema_url(tema('genel.favicon')) }}">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/yonetim.css') }}?v={{ config('portfolyo.surum') }}">
    @stack('stiller')
</head>
<body class="@yield('govde_sinifi')">
    @hasSection('tam_ekran')
        @yield('tam_ekran')
    @else
        <div class="y-kabuk">
            <aside class="y-kenar" id="y-kenar">
                <a href="{{ route('yonetim.panel') }}" class="y-marka">
                    <span class="y-marka-ikon"><i class="bi bi-stars"></i></span>
                    <span>{{ tema('genel.site_adi') }}</span>
                </a>
                <nav class="y-menu">
                    @foreach ($menu as [$rota, $ikon, $etiket, $desen])
                        <a href="{{ route($rota) }}" @class(['aktif' => request()->routeIs($desen)])>
                            <i class="bi {{ $ikon }}"></i>
                            <span>{{ $etiket }}</span>
                            @if ($rota === 'yonetim.mesajlar.index' && $okunmamis)
                                <span class="y-rozet">{{ $okunmamis }}</span>
                            @endif
                        </a>
                    @endforeach
                </nav>
                <div class="y-kenar-alt">
                    <a href="{{ route('anasayfa') }}" target="_blank"><i class="bi bi-box-arrow-up-right"></i> Siteyi görüntüle</a>
                    <div class="y-surum">
                        Laravel Portfolyo v{{ config('portfolyo.surum') }}<br>
                        <a href="{{ config('portfolyo.dagitici.site') }}" target="_blank" rel="noopener">webdanismani.com</a> ·
                        <a href="{{ config('portfolyo.dagitici.forum') }}" target="_blank" rel="noopener">Destek forumu</a>
                    </div>
                </div>
            </aside>

            <div class="y-ana">
                <header class="y-ust">
                    <button type="button" class="y-kenar-ac btn btn-link d-lg-none" aria-label="Menü"><i class="bi bi-list"></i></button>
                    <h1 class="y-sayfa-baslik">@yield('baslik', 'Yönetim')</h1>
                    <div class="ms-auto d-flex align-items-center gap-2">
                        @yield('ust_eylemler')
                        <div class="dropdown">
                            <button class="btn y-kullanici" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="y-avatar">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                                <span class="d-none d-sm-inline">{{ auth()->user()->name }}</span>
                                <i class="bi bi-chevron-down small"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li><a class="dropdown-item" href="{{ route('yonetim.profil') }}"><i class="bi bi-person me-2"></i>Profil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('yonetim.cikis') }}">
                                        @csrf
                                        <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Çıkış yap</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </header>

                <main class="y-icerik">
                    @if (session('basari'))
                        <div class="alert alert-success d-flex align-items-center gap-2"><i class="bi bi-check-circle"></i>{{ session('basari') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $hata)
                                    <li>{{ $hata }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('icerik')
                </main>
            </div>
        </div>
        <div class="y-perde"></div>
    @endif

    @include('yonetim.parcalar.medya-penceresi')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.Yonetim = {
            csrf: @json(csrf_token()),
            medyaYukle: @json(route('yonetim.medya.yukle')),
            medyaListe: @json(route('yonetim.medya.liste')),
            temel: @json(asset(''))
        };
    </script>
    <script src="{{ asset('assets/js/yonetim.js') }}?v={{ config('portfolyo.surum') }}"></script>
    @stack('betikler')
</body>
</html>
