@extends('layouts.site')

@section('baslik', tema('blog.sayfa_baslik'))

@section('icerik')
    @include('site.parcalar.sayfa-baslik', [
        'baslik' => tema('blog.sayfa_baslik'),
        'yol' => 'blog.sayfa_baslik',
        'kirintilar' => array_filter([
            tema('blog.sayfa_baslik') => ($ara !== '' || $kategori !== '') ? route('blog') : null,
            ($kategori ?: ($ara !== '' ? '“'.$ara.'”' : '')) => null,
        ], fn ($v, $k) => $k !== '', ARRAY_FILTER_USE_BOTH),
    ])

    <section class="bolum-ust" style="padding-bottom: var(--bolum-bosluk)">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8">
                    @if ($yazilar->isEmpty())
                        <p class="bos-durum" {{ dt('blog.bos_metin') }}>{{ tema('blog.bos_metin') }}</p>
                    @else
                        <div class="row g-5">
                            @foreach ($yazilar as $i => $yazi)
                                <div class="col-md-6">
                                    <article class="blog-kart anim" style="--gecikme: {{ ($i % 2) * 0.15 }}s">
                                        <a href="{{ route('blog.detay', $yazi->slug) }}" class="gorsel">
                                            <img src="{{ tema_url($yazi->kapak ?: 'assets/img/demo/blog-1.svg') }}" alt="{{ $yazi->baslik }}" loading="lazy">
                                        </a>
                                        <div class="meta">
                                            <span><i class="bi bi-calendar3"></i>{{ $yazi->yayin_tarihi->translatedFormat('d F Y') }}</span>
                                            @if ($yazi->kategori)
                                                <span><i class="bi bi-folder2"></i>{{ $yazi->kategori }}</span>
                                            @endif
                                            <span><i class="bi bi-clock"></i>{{ $yazi->okumaSuresi() }} dk</span>
                                        </div>
                                        <h4><a href="{{ route('blog.detay', $yazi->slug) }}">{{ $yazi->baslik }}</a></h4>
                                        <p>{{ $yazi->ozetMetni() }}</p>
                                        <a href="{{ route('blog.detay', $yazi->slug) }}" class="devami">
                                            <span {{ dt('blog.devami') }}>{{ tema('blog.devami') }}</span> <i class="bi bi-arrow-up-right"></i>
                                        </a>
                                    </article>
                                </div>
                            @endforeach
                        </div>

                        @if ($yazilar->hasPages())
                            <div class="sayfalama">{{ $yazilar->links() }}</div>
                        @endif
                    @endif
                </div>
                <div class="col-lg-4">
                    @include('site.parcalar.blog-kenar')
                </div>
            </div>
        </div>
    </section>
@endsection
