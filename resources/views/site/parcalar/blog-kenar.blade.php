<aside>
    <div class="kenar-kutu">
        <form action="{{ route('blog') }}" method="GET" class="arama" role="search">
            @if (tema()->onizlemeModu())
                <input type="hidden" name="onizleme" value="1">
            @endif
            <input type="search" name="ara" value="{{ $ara ?? '' }}" placeholder="{{ tema('blog.ara_yer_tutucu') }}" aria-label="{{ tema('blog.ara_yer_tutucu') }}" {{ dt('blog.ara_yer_tutucu', 'placeholder') }}>
            <button type="submit" aria-label="Ara"><i class="bi bi-search"></i></button>
        </form>
    </div>

    @if ($sonYazilar->isNotEmpty())
        <div class="kenar-kutu">
            <h5 {{ dt('blog.son_yazilar') }}>{{ tema('blog.son_yazilar') }}</h5>
            @foreach ($sonYazilar as $son)
                <a href="{{ route('blog.detay', $son->slug) }}" class="son-yazi">
                    <img src="{{ tema_url($son->kapak ?: 'assets/img/demo/blog-1.svg') }}" alt="" loading="lazy">
                    <div>
                        <small>{{ $son->yayin_tarihi->translatedFormat('d F Y') }}</small>
                        <h6>{{ $son->baslik }}</h6>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

    @if ($blogKategorileri->isNotEmpty())
        <div class="kenar-kutu">
            <h5 {{ dt('blog.kategoriler') }}>{{ tema('blog.kategoriler') }}</h5>
            <ul class="kategori-liste">
                @foreach ($blogKategorileri as $kat)
                    <li>
                        <a href="{{ route('blog', ['kategori' => $kat->kategori]) }}" @class(['aktif' => ($kategori ?? '') === $kat->kategori])>
                            <span>{{ $kat->kategori }}</span><span>({{ $kat->adet }})</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</aside>
