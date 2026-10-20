@extends('yonetim.duzen')

@section('baslik', 'Mesaj')

@section('ust_eylemler')
    <a href="{{ route('yonetim.mesajlar.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Mesajlar</a>
@endsection

@section('icerik')
    <div class="y-kart">
        <div class="d-flex flex-wrap gap-3 align-items-start mb-4">
            <span class="y-avatar buyuk">{{ mb_strtoupper(mb_substr($mesaj->ad, 0, 1)) }}</span>
            <div class="flex-grow-1">
                <h2 class="h5 mb-1">{{ $mesaj->tamAd() }}</h2>
                <div class="text-muted small">
                    <a href="mailto:{{ $mesaj->eposta }}">{{ $mesaj->eposta }}</a>
                    @if ($mesaj->telefon)
                        · <a href="tel:{{ preg_replace('/[^0-9+]/', '', $mesaj->telefon) }}">{{ $mesaj->telefon }}</a>
                    @endif
                    · {{ $mesaj->created_at->translatedFormat('d F Y H:i') }}
                    @if ($mesaj->ip)
                        · IP {{ $mesaj->ip }}
                    @endif
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="mailto:{{ $mesaj->eposta }}?subject={{ rawurlencode('Yanıt: '.tema('genel.site_adi')) }}" class="btn btn-dark"><i class="bi bi-reply me-1"></i>Yanıtla</a>
                <form method="POST" action="{{ route('yonetim.mesajlar.destroy', $mesaj) }}" data-onay="Bu mesaj silinsin mi?">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                </form>
            </div>
        </div>
        <div class="y-mesaj-govde">{{ $mesaj->mesaj }}</div>
    </div>
@endsection
