<section id="pricing" class="fiyatlar bolum-dis">
    <div class="fiyatlar-ic">
        <div class="container">
            <x-bolum-baslik yol="fiyatlar.baslik" cizgi="145" class="anim" />

            <div class="row fiyat-izgara g-3 g-md-4 g-lg-5">
                @foreach (tema('fiyatlar.ogeler', []) as $i => $paket)
                    <div class="col-xl-4 col-md-6">
                        <div class="fiyat anim" style="--gecikme: {{ 0.1 + $i * 0.15 }}s">
                            <div>
                                <h6 {{ dt("fiyatlar.ogeler.$i.paket") }}>{{ $paket['paket'] }}</h6>
                                <h3><span class="fiyat-tutar" {{ dt("fiyatlar.ogeler.$i.fiyat") }}>{{ $paket['fiyat'] }}</span> <span {{ dt("fiyatlar.ogeler.$i.birim") }}>{{ $paket['birim'] }}</span></h3>
                                <p {{ dt("fiyatlar.ogeler.$i.alt_metin") }}>{{ $paket['alt_metin'] }}</p>
                                <div class="icerik-etiket">
                                    <h6 {{ dt('fiyatlar.ozellik_baslik') }}>{{ tema('fiyatlar.ozellik_baslik') }}</h6>
                                    <svg viewBox="0 0 90 18" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" aria-hidden="true"><path d="M14 1C12 9 8 14 1 16h88"/></svg>
                                </div>
                                <ul>
                                    @foreach (tema_satirlar($paket['ozellikler']) as $ozellik)
                                        <li>{{ $ozellik }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @if ($paket['buton_metin'])
                                <a href="{{ tema_baglanti($paket['buton_baglanti']) }}" class="fiyat-buton" {{ dt("fiyatlar.ogeler.$i.buton_metin") }}>{{ $paket['buton_metin'] }}</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
