@extends('yonetim.duzen')

@section('baslik', 'Gösterge Paneli')

@section('icerik')
    <div class="y-karsilama">
        <div>
            <h2>Merhaba, {{ auth()->user()->name }} 👋</h2>
            <p>Sitenizdeki tüm metinleri ve görselleri tema düzenleyiciden canlı önizlemeyle değiştirebilirsiniz.</p>
        </div>
        <a href="{{ route('yonetim.tema') }}" class="btn btn-light btn-lg"><i class="bi bi-palette2 me-2"></i>Tema Düzenleyiciyi Aç</a>
    </div>

    <div class="row g-3 mb-4">
        @foreach ([
            ['Projeler', $sayilar['proje'], 'bi-grid-1x2', route('yonetim.projeler.index')],
            ['Blog yazıları', $sayilar['yazi'], 'bi-journal-text', route('yonetim.blog.index')],
            ['Mesajlar', $sayilar['mesaj'], 'bi-envelope', route('yonetim.mesajlar.index')],
            ['Okunmamış', $sayilar['okunmamis'], 'bi-envelope-exclamation', route('yonetim.mesajlar.index', ['filtre' => 'okunmamis'])],
        ] as [$etiket, $sayi, $ikon, $adres])
            <div class="col-6 col-xl-3">
                <a href="{{ $adres }}" class="y-istatistik">
                    <span class="ikon"><i class="bi {{ $ikon }}"></i></span>
                    <span>
                        <strong>{{ $sayi }}</strong>
                        <small>{{ $etiket }}</small>
                    </span>
                </a>
            </div>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="y-kart h-100">
                <div class="y-kart-ust">
                    <h2>Son mesajlar</h2>
                    <a href="{{ route('yonetim.mesajlar.index') }}" class="btn btn-sm btn-outline-secondary">Tümü</a>
                </div>
                @forelse ($sonMesajlar as $mesaj)
                    <a href="{{ route('yonetim.mesajlar.show', $mesaj) }}" @class(['y-liste-satir', 'fw-bold' => ! $mesaj->okundu])>
                        <span class="y-avatar kucuk">{{ mb_strtoupper(mb_substr($mesaj->ad, 0, 1)) }}</span>
                        <span class="flex-grow-1 text-truncate">
                            {{ $mesaj->tamAd() }}
                            <small class="d-block text-muted fw-normal text-truncate">{{ \Illuminate\Support\Str::limit($mesaj->mesaj, 70) }}</small>
                        </span>
                        <small class="text-muted text-nowrap">{{ $mesaj->created_at->diffForHumans() }}</small>
                    </a>
                @empty
                    <p class="text-muted mb-0">Henüz mesaj yok.</p>
                @endforelse
            </div>
        </div>
        <div class="col-lg-6">
            <div class="y-kart h-100">
                <div class="y-kart-ust">
                    <h2>Son yazılar</h2>
                    <a href="{{ route('yonetim.blog.create') }}" class="btn btn-sm btn-dark"><i class="bi bi-plus-lg"></i> Yeni yazı</a>
                </div>
                @forelse ($sonYazilar as $yazi)
                    <a href="{{ route('yonetim.blog.edit', $yazi) }}" class="y-liste-satir">
                        <span class="flex-grow-1 text-truncate">{{ $yazi->baslik }}</span>
                        @unless ($yazi->aktif)
                            <span class="badge text-bg-secondary">Taslak</span>
                        @endunless
                        <small class="text-muted text-nowrap">{{ $yazi->yayin_tarihi?->translatedFormat('d M Y') }}</small>
                    </a>
                @empty
                    <p class="text-muted mb-0">Henüz yazı yok.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="y-kart mt-4 y-destek">
        <i class="bi bi-life-preserver"></i>
        <div>
            <strong>Yardıma mı ihtiyacınız var?</strong>
            <p class="mb-0 text-muted">Sorularınızı <a href="{{ config('portfolyo.dagitici.forum') }}" target="_blank" rel="noopener">oblifex.com</a> destek forumunda sorabilir, hata bildirimlerini <a href="{{ config('portfolyo.dagitici.depo') }}/issues" target="_blank" rel="noopener">GitHub</a> üzerinden iletebilirsiniz.</p>
        </div>
    </div>
@endsection
