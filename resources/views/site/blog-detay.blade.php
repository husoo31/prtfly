@extends('layouts.site')

@section('baslik', $yazi->baslik)
@section('aciklama', $yazi->ozetMetni())
@section('og_gorsel', $yazi->kapak ?? '')

@section('icerik')
    @include('site.parcalar.sayfa-baslik', [
        'baslik' => $yazi->baslik,
        'kirintilar' => [tema('blog.sayfa_baslik') => route('blog'), \Illuminate\Support\Str::limit($yazi->baslik, 40) => null],
    ])

    <section class="bolum-ust" style="padding-bottom: var(--bolum-bosluk)">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8">
                    <article>
                        @if ($yazi->kapak)
                            <img src="{{ tema_url($yazi->kapak) }}" alt="{{ $yazi->baslik }}" class="w-100 mb-4 anim">
                        @endif
                        <div class="blog-kart">
                            <div class="meta mt-0">
                                <span><i class="bi bi-calendar3"></i>{{ $yazi->yayin_tarihi->translatedFormat('d F Y') }}</span>
                                @if ($yazi->yazar)
                                    <span><i class="bi bi-person"></i>{{ $yazi->yazar }}</span>
                                @endif
                                @if ($yazi->kategori)
                                    <span><i class="bi bi-folder2"></i><a href="{{ route('blog', ['kategori' => $yazi->kategori]) }}">{{ $yazi->kategori }}</a></span>
                                @endif
                                <span><i class="bi bi-clock"></i>{{ $yazi->okumaSuresi() }} dk</span>
                            </div>
                        </div>

                        <div class="yazi-icerik">
                            {{ $yazi->icerikHtml() }}
                        </div>

                        @if ($yazi->etiketListesi())
                            <div class="etiketler">
                                @foreach ($yazi->etiketListesi() as $etiket)
                                    <span>#{{ $etiket }}</span>
                                @endforeach
                            </div>
                        @endif

                        @if ($onceki || $sonraki)
                            <nav class="yazi-gezinti">
                                <div>
                                    @if ($onceki)
                                        <a href="{{ route('blog.detay', $onceki->slug) }}"><small><i class="bi bi-arrow-left"></i></small>{{ $onceki->baslik }}</a>
                                    @endif
                                </div>
                                <div class="text-end">
                                    @if ($sonraki)
                                        <a href="{{ route('blog.detay', $sonraki->slug) }}"><small><i class="bi bi-arrow-right"></i></small>{{ $sonraki->baslik }}</a>
                                    @endif
                                </div>
                            </nav>
                        @endif
                    </article>
                </div>
                <div class="col-lg-4">
                    @include('site.parcalar.blog-kenar')
                </div>
            </div>
        </div>
    </section>
@endsection
