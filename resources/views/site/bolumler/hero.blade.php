<section class="hero">
    <div class="container">
        <div class="hero-cizgi"></div>

        <div class="hero-gorsel">
            <div class="kapsul"></div>
            <img class="anim sol" style="--gecikme: .9s" src="{{ tema_url(tema('hero.profil_gorsel')) }}" alt="{{ tema('hero.isim') }}" {{ dt('hero.profil_gorsel', 'src') }}>
        </div>

        <a href="{{ tema_baglanti(tema('hero.donen_baglanti')) }}" class="donen-daire" aria-label="{{ tema('hero.donen_yazi') }}">
            <svg viewBox="0 0 170 170" aria-hidden="true">
                <defs>
                    <path id="donen-yol" d="M85,85 m-66,0 a66,66 0 1,1 132,0 a66,66 0 1,1 -132,0" />
                </defs>
                <text><textPath href="#donen-yol" textLength="408" {{ dt('hero.donen_yazi') }}>{{ tema('hero.donen_yazi') }}</textPath></text>
            </svg>
            <span class="orta"><i class="bi bi-arrow-up-right"></i></span>
        </a>

        <div class="selam anim" style="--gecikme: .3s">
            <h6 {{ dt('hero.selam') }}>{{ tema('hero.selam') }}</h6>
            <span class="emoji" {{ dt('hero.selam_ikon') }}>{{ tema('hero.selam_ikon') }}</span>
            <h6 {{ dt('hero.selam_son') }}>{{ tema('hero.selam_son') }}</h6>
        </div>

        <h1 class="anim" style="--gecikme: .5s" {{ dt('hero.isim') }}>{{ tema('hero.isim') }}</h1>

        <div class="row">
            <div class="col-lg-8 offset-lg-4 col-xl-6">
                <p class="tanitim anim" style="--gecikme: .7s" {{ dt('hero.aciklama') }}>{{ tema('hero.aciklama') }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 offset-lg-4">
                <div class="eylem anim" style="--gecikme: .9s">
                    @if (tema('hero.buton1_metin'))
                        <a href="{{ tema_baglanti(tema('hero.buton1_baglanti')) }}" class="btn-hap dolu">
                            <span {{ dt('hero.buton1_metin') }}>{{ tema('hero.buton1_metin') }}</span> <i class="bi bi-arrow-up-right"></i>
                        </a>
                    @endif
                    @if (tema('hero.buton2_metin'))
                        <a href="{{ tema_baglanti(tema('hero.buton2_baglanti')) }}" class="btn-hap bos">
                            <span {{ dt('hero.buton2_metin') }}>{{ tema('hero.buton2_metin') }}</span> <i class="bi bi-arrow-up-right"></i>
                        </a>
                    @endif
                    <svg class="uzun-ok d-none d-xxl-block" viewBox="0 0 120 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path d="M0 12h116M106 2l10 10-10 10" />
                    </svg>
                    <x-sosyal />
                </div>
            </div>
        </div>

        <span class="unvan anim" style="--gecikme: 1.1s" data-yansima="{{ tema('hero.unvan') }}" {{ dt('hero.unvan') }}>{{ tema('hero.unvan') }}</span>
    </div>
</section>
