@extends('yonetim.duzen')

@section('baslik', 'Mesajlar')

@section('icerik')
    <div class="y-kart">
        <ul class="nav nav-pills mb-3 gap-1">
            <li class="nav-item"><a href="{{ route('yonetim.mesajlar.index') }}" @class(['nav-link', 'active' => $filtre !== 'okunmamis'])>Tümü</a></li>
            <li class="nav-item"><a href="{{ route('yonetim.mesajlar.index', ['filtre' => 'okunmamis']) }}" @class(['nav-link', 'active' => $filtre === 'okunmamis'])>Okunmamış</a></li>
        </ul>

        <div class="list-group list-group-flush">
            @forelse ($mesajlar as $mesaj)
                <a href="{{ route('yonetim.mesajlar.show', $mesaj) }}" @class(['list-group-item list-group-item-action d-flex gap-3 align-items-center py-3', 'fw-bold' => ! $mesaj->okundu])>
                    <span class="y-avatar">{{ mb_strtoupper(mb_substr($mesaj->ad, 0, 1)) }}</span>
                    <span class="flex-grow-1 text-truncate">
                        {{ $mesaj->tamAd() }} <small class="text-muted fw-normal">&lt;{{ $mesaj->eposta }}&gt;</small>
                        <small class="d-block text-muted fw-normal text-truncate">{{ \Illuminate\Support\Str::limit($mesaj->mesaj, 120) }}</small>
                    </span>
                    @unless ($mesaj->okundu)
                        <span class="badge rounded-pill text-bg-warning">Yeni</span>
                    @endunless
                    <small class="text-muted text-nowrap">{{ $mesaj->created_at->translatedFormat('d M Y H:i') }}</small>
                </a>
            @empty
                <p class="text-center text-muted py-5 mb-0">Mesaj yok.</p>
            @endforelse
        </div>
        <div class="mt-3">{{ $mesajlar->links() }}</div>
    </div>
@endsection
