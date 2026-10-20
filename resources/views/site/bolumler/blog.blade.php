<section id="blogs" class="blog-bolum bolum-ust">
    <div class="container">
        <x-bolum-baslik yol="blog.baslik" class="anim" />

        <div class="blog-liste akordeon">
            @forelse ($yazilar as $i => $yazi)
                <div @class(['blog-satir anim', 'acik' => $i === min(1, $yazilar->count() - 1)]) style="--gecikme: {{ 0.1 + $i * 0.1 }}s">
                    <div class="satir-ust">
                        <h4>{{ $yazi->baslik }}</h4>
                        <div class="tarih-kutu">
                            <h4>{{ $yazi->yayin_tarihi->format('d') }}/</h4>
                            <h6>{{ $yazi->yayin_tarihi->translatedFormat('M') }}</h6>
                        </div>
                    </div>
                    <div class="satir-detay">
                        <div>
                            <figure>
                                <img src="{{ tema_url($yazi->kapak ?: 'assets/img/demo/blog-1.svg') }}" alt="{{ $yazi->baslik }}" loading="lazy">
                                <a href="{{ route('blog.detay', $yazi->slug) }}" class="btn-yuvarlak kucuk">
                                    <span><span {{ dt('blog.oku_buton') }}>{{ tema('blog.oku_buton') }}</span> <i class="bi bi-arrow-up-right"></i></span>
                                </a>
                            </figure>
                        </div>
                    </div>
                </div>
            @empty
                <p class="bos-durum" {{ dt('blog.bos_metin') }}>{{ tema('blog.bos_metin') }}</p>
            @endforelse
        </div>

        @if (tema('blog.buton_metin') && $yazilar->isNotEmpty())
            <div class="tumunu-gor anim">
                <a href="{{ route('blog') }}" class="btn-yuvarlak">
                    <span><span {{ dt('blog.buton_metin') }}>{{ tema('blog.buton_metin') }}</span> <i class="bi bi-arrow-up-right"></i></span>
                </a>
            </div>
        @endif
    </div>
</section>
