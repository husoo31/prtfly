@extends('yonetim.duzen')

@section('baslik', 'Blog')

@section('ust_eylemler')
    <a href="{{ route('yonetim.blog.create') }}" class="btn btn-dark"><i class="bi bi-plus-lg me-1"></i>Yeni yazı</a>
@endsection

@section('icerik')
    <div class="y-kart">
        <form class="y-arama mb-3" method="GET">
            <i class="bi bi-search"></i>
            <input type="search" name="ara" value="{{ $ara }}" class="form-control" placeholder="Başlıkta ara">
        </form>

        <div class="table-responsive">
            <table class="table align-middle y-tablo">
                <thead>
                    <tr>
                        <th style="width:90px">Görsel</th>
                        <th>Başlık</th>
                        <th>Kategori</th>
                        <th>Yayın tarihi</th>
                        <th>Durum</th>
                        <th class="text-end">İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($yazilar as $yazi)
                        <tr>
                            <td><img src="{{ tema_url($yazi->kapak ?: 'assets/img/demo/blog-1.svg') }}" class="y-kucuk-gorsel" alt=""></td>
                            <td>
                                <a href="{{ route('yonetim.blog.edit', $yazi) }}" class="fw-semibold text-reset">{{ $yazi->baslik }}</a>
                                <small class="d-block text-muted">/blog/{{ $yazi->slug }}</small>
                            </td>
                            <td><small>{{ $yazi->kategori }}</small></td>
                            <td><small>{{ $yazi->yayin_tarihi?->translatedFormat('d M Y H:i') }}</small></td>
                            <td>
                                @if (! $yazi->aktif)
                                    <span class="badge text-bg-secondary">Taslak</span>
                                @elseif ($yazi->yayin_tarihi?->isFuture())
                                    <span class="badge text-bg-info">Zamanlandı</span>
                                @else
                                    <span class="badge text-bg-success">Yayında</span>
                                @endif
                            </td>
                            <td class="text-end text-nowrap">
                                @if ($yazi->aktif)
                                    <a href="{{ route('blog.detay', $yazi->slug) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Görüntüle"><i class="bi bi-eye"></i></a>
                                @endif
                                <a href="{{ route('yonetim.blog.edit', $yazi) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="{{ route('yonetim.blog.destroy', $yazi) }}" class="d-inline" data-onay="Bu yazı silinsin mi?">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-5">Henüz yazı yok. <a href="{{ route('yonetim.blog.create') }}">İlk yazıyı ekleyin</a>.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $yazilar->links() }}
    </div>
@endsection
