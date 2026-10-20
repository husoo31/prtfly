@extends('yonetim.duzen')

@section('baslik', 'Projeler')

@section('ust_eylemler')
    <a href="{{ route('yonetim.projeler.create') }}" class="btn btn-dark"><i class="bi bi-plus-lg me-1"></i>Yeni proje</a>
@endsection

@section('icerik')
    <div class="y-kart">
        <form class="y-arama mb-3" method="GET">
            <i class="bi bi-search"></i>
            <input type="search" name="ara" value="{{ $ara }}" class="form-control" placeholder="Başlık veya kategoride ara">
        </form>

        <div class="table-responsive">
            <table class="table align-middle y-tablo">
                <thead>
                    <tr>
                        <th style="width:90px">Görsel</th>
                        <th>Başlık</th>
                        <th>Kategori</th>
                        <th>Boyut</th>
                        <th>Sıra</th>
                        <th>Durum</th>
                        <th class="text-end">İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($projeler as $proje)
                        <tr>
                            <td><img src="{{ tema_url($proje->kapak ?: 'assets/img/demo/proje-1.svg') }}" class="y-kucuk-gorsel" alt=""></td>
                            <td>
                                <a href="{{ route('yonetim.projeler.edit', $proje) }}" class="fw-semibold text-reset">{{ $proje->baslik }}</a>
                                @if ($proje->musteri)
                                    <small class="d-block text-muted">{{ $proje->musteri }}</small>
                                @endif
                            </td>
                            <td><small>{{ $proje->kategori }}</small></td>
                            <td><small>{{ \App\Models\Proje::BOYUTLAR[$proje->boyut] ?? $proje->boyut }}</small></td>
                            <td>{{ $proje->sira }}</td>
                            <td>
                                @if ($proje->aktif)
                                    <span class="badge text-bg-success">Yayında</span>
                                @else
                                    <span class="badge text-bg-secondary">Gizli</span>
                                @endif
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('yonetim.projeler.edit', $proje) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="{{ route('yonetim.projeler.destroy', $proje) }}" class="d-inline" data-onay="Bu proje silinsin mi?">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-5">Henüz proje yok. <a href="{{ route('yonetim.projeler.create') }}">İlk projeyi ekleyin</a>.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $projeler->links() }}
    </div>
@endsection
