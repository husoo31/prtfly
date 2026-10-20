@php $hizmetler = tema('hizmetler.ogeler', []); @endphp
<section id="services" class="hizmetler bolum-ust">
    <div class="container">
        <x-bolum-baslik yol="hizmetler.baslik" class="anim" />

        <div class="hizmet-liste akordeon">
            @foreach ($hizmetler as $i => $hizmet)
                <div @class(['hizmet anim', 'acik' => $i === min(1, count($hizmetler) - 1)]) style="--gecikme: {{ 0.1 + $i * 0.1 }}s">
                    <div class="hizmet-metin">
                        <span class="hizmet-adi" {{ dt("hizmetler.ogeler.$i.baslik") }}>{{ $hizmet['baslik'] }}</span>
                        <div class="hizmet-detay">
                            <ul>
                                @foreach (tema_satirlar($hizmet['maddeler']) as $madde)
                                    <li>{{ $madde }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @if ($hizmet['buton_metin'])
                        <div class="hizmet-buton">
                            <a href="{{ tema_baglanti($hizmet['baglanti']) }}" class="btn-yuvarlak kucuk">
                                <span><span {{ dt("hizmetler.ogeler.$i.buton_metin") }}>{{ $hizmet['buton_metin'] }}</span> <i class="bi bi-arrow-up-right"></i></span>
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        @if (tema('hizmetler.buton_metin'))
            <div class="tumunu-gor anim">
                <a href="{{ tema_baglanti(tema('hizmetler.buton_baglanti')) }}" class="btn-yuvarlak">
                    <span><span {{ dt('hizmetler.buton_metin') }}>{{ tema('hizmetler.buton_metin') }}</span> <i class="bi bi-arrow-up-right"></i></span>
                </a>
            </div>
        @endif
    </div>
</section>
