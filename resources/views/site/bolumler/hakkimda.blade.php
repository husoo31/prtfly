<section id="about" class="hakkimda bolum-ust">
    <div class="container">
        <x-bolum-baslik yol="hakkimda.baslik" :yildiz="false" class="anim" />

        <h3 class="tanitim-metni anim" style="--gecikme: .2s" {{ dt('hakkimda.metin', 'vurgu') }}>{{ tema_vurgu(tema('hakkimda.metin')) }}</h3>

        <div class="row">
            <div class="col-md-6 d-flex flex-column justify-content-between">
                <div class="deneyim anim sol" style="--gecikme: .3s">
                    <svg class="ok-sus" viewBox="0 0 40 240" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" aria-hidden="true">
                        <path d="M2 2v236c2-14 12-26 30-30" />
                    </svg>
                    <span class="sayi" {{ dt('hakkimda.deneyim_sayi') }}>{{ tema('hakkimda.deneyim_sayi') }}</span>
                    <h4 {{ dt('hakkimda.deneyim_metin', 'satir') }}>{!! nl2br(e(tema('hakkimda.deneyim_metin'))) !!}</h4>
                    <x-yildiz />
                </div>
                @if (tema('hakkimda.buton_metin'))
                    <div>
                        <a href="{{ tema_baglanti(tema('hakkimda.buton_baglanti')) }}" class="btn-yuvarlak">
                            <span><span {{ dt('hakkimda.buton_metin') }}>{{ tema('hakkimda.buton_metin') }}</span> <i class="bi bi-arrow-up-right"></i></span>
                        </a>
                    </div>
                @endif
            </div>
            <div class="col-md-6">
                <figure class="anim sag" style="--gecikme: .3s">
                    <img src="{{ tema_url(tema('hakkimda.gorsel')) }}" alt="{{ tema('hakkimda.baslik') }}" loading="lazy" {{ dt('hakkimda.gorsel', 'src') }}>
                </figure>
            </div>
        </div>
    </div>
</section>
