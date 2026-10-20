<section id="contact" class="iletisim bolum-dis">
    <div class="kayan bolum-ust" aria-hidden="true">
        <div class="kayan-serit">
            @for ($kopya = 0; $kopya < 2; $kopya++)
                <span>
                    @for ($tur = 0; $tur < 4; $tur++)
                        <span class="oge cerceve" {{ dt('iletisim.kayan_eposta') }}>{{ tema('iletisim.kayan_eposta') }}</span><span class="oge cerceve">_</span>
                        <span class="oge" {{ dt('iletisim.kayan_telefon') }}>{{ tema('iletisim.kayan_telefon') }}</span><span class="oge">_</span>
                    @endfor
                </span>
            @endfor
        </div>
    </div>

    <div class="container">
        <div class="iletisim-ic">
            <div class="row">
                <div class="col-lg-6">
                    <span class="buyuk-yazi anim" {{ dt('iletisim.baslik') }}>{{ tema('iletisim.baslik') }}</span>
                    <p class="anim" style="--gecikme: .1s" {{ dt('iletisim.aciklama') }}>{{ tema('iletisim.aciklama') }}</p>

                    <form class="iletisim-form anim sol" style="--gecikme: .2s" method="POST" action="{{ route('iletisim') }}" data-iletisim-formu data-hata="{{ tema('iletisim.hata_mesaji') }}">
                        @csrf
                        <input type="text" name="web_sitesi" class="tuzak" tabindex="-1" autocomplete="off" aria-hidden="true">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="i-ad" class="zorunlu" {{ dt('iletisim.ad_etiketi') }}>{{ tema('iletisim.ad_etiketi') }}</label>
                                <input type="text" id="i-ad" name="ad" maxlength="100" required value="{{ old('ad') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="i-soyad" {{ dt('iletisim.soyad_etiketi') }}>{{ tema('iletisim.soyad_etiketi') }}</label>
                                <input type="text" id="i-soyad" name="soyad" maxlength="100" value="{{ old('soyad') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="i-eposta" class="zorunlu" {{ dt('iletisim.eposta_etiketi') }}>{{ tema('iletisim.eposta_etiketi') }}</label>
                                <input type="email" id="i-eposta" name="eposta" maxlength="190" required value="{{ old('eposta') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="i-telefon" {{ dt('iletisim.telefon_etiketi') }}>{{ tema('iletisim.telefon_etiketi') }}</label>
                                <input type="tel" id="i-telefon" name="telefon" maxlength="40" value="{{ old('telefon') }}">
                            </div>
                        </div>
                        <label for="i-mesaj" class="zorunlu" {{ dt('iletisim.mesaj_etiketi') }}>{{ tema('iletisim.mesaj_etiketi') }}</label>
                        <textarea id="i-mesaj" name="mesaj" rows="2" maxlength="5000" required placeholder="{{ tema('iletisim.mesaj_yer_tutucu') }}" {{ dt('iletisim.mesaj_yer_tutucu', 'placeholder') }}>{{ old('mesaj') }}</textarea>
                        <button type="submit" class="btn-kare">
                            <span {{ dt('iletisim.buton_metin') }}>{{ tema('iletisim.buton_metin') }}</span> <i class="bi bi-arrow-up-right"></i>
                        </button>
                        <p @class(['form-durum', 'basarili' => session('iletisim_basarili'), 'hatali' => $errors->any()]) role="status">
                            {{ session('iletisim_basarili') ?: ($errors->any() ? tema('iletisim.hata_mesaji') : '') }}
                        </p>
                    </form>
                </div>

                <div class="col-lg-5 ms-auto my-auto">
                    <nav class="iletisim-menu anim sag" style="--gecikme: .3s">
                        <ul>
                            @foreach (array_slice(tema('menu.ogeler', []), 0, 6) as $i => $oge)
                                <li>
                                    <a href="{{ tema_baglanti($oge['baglanti']) }}">
                                        <span {{ dt("menu.ogeler.$i.etiket") }}>{{ $oge['etiket'] }}</span>
                                        <span>{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </nav>
                    <x-sosyal sinif="sosyal-cerceve" class="anim" />
                </div>
            </div>
        </div>
    </div>
</section>
