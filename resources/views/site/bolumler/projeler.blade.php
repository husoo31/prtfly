<section id="portfolio" class="projeler bolum-dis">
    <div class="projeler-ic">
        <div class="container">
            <div class="row align-items-end">
                <div class="col-xxl-5">
                    <x-bolum-baslik yol="projeler.baslik" :yildiz="false" class="anim" />
                </div>
                <div class="col-xxl-7">
                    @include('site.parcalar.proje-filtre')
                </div>
            </div>

            @include('site.parcalar.proje-izgara')

            @if (tema('projeler.buton_metin'))
                <div class="tumunu-gor anim">
                    <a href="{{ route('projeler') }}" class="btn-yuvarlak">
                        <span><span {{ dt('projeler.buton_metin') }}>{{ tema('projeler.buton_metin') }}</span> <i class="bi bi-arrow-up-right"></i></span>
                    </a>
                    <x-yildiz />
                </div>
            @endif
        </div>
    </div>
</section>
