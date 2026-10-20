@php $referanslar = tema('referanslar.ogeler', []); @endphp
@if (count($referanslar))
<section id="testimonial" class="referanslar bolum-ust">
    <div class="container">
        <span class="dev-baslik anim" {{ dt('referanslar.baslik') }}>{{ tema('referanslar.baslik') }}</span>

        <div class="referans-sahne" data-referans>
            <div class="referans-sol">
                <svg class="tirnak anim" viewBox="0 0 64 48" fill="currentColor" aria-hidden="true">
                    <path d="M0 48V28C0 12 8 3 24 0l3 6C18 9 13.5 14.5 13 22h12v26H0zm37 0V28c0-16 8-25 24-28l3 6c-9 3-13.5 8.5-14 16h12v26H37z"/>
                </svg>

                <div class="referans-metinler anim" style="--gecikme: .2s">
                    @foreach ($referanslar as $i => $referans)
                        <div @class(['referans-metin', 'aktif' => $i === 0])>
                            <h5 {{ dt("referanslar.ogeler.$i.yorum") }}>{{ $referans['yorum'] }}</h5>
                            <div class="kisi">
                                <span {{ dt("referanslar.ogeler.$i.isim") }}>{{ $referans['isim'] }}</span>
                                <small {{ dt("referanslar.ogeler.$i.unvan") }}>{{ $referans['unvan'] }}</small>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if (count($referanslar) > 1)
                    <div class="referans-alt">
                        <div class="referans-sus" aria-hidden="true">
                            <x-yildiz class="position-static" />
                            <svg viewBox="0 0 160 30" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round">
                                <path d="M1 28h158c-14-3-24-12-28-26" />
                            </svg>
                        </div>
                        <div>
                            <div class="referans-kucukler">
                                @foreach ($referanslar as $i => $referans)
                                    <button type="button" data-sira="{{ $i }}" @class(['gizli' => $i === 0]) aria-label="{{ $referans['isim'] }}">
                                        <img src="{{ tema_url($referans['gorsel']) }}" alt="" loading="lazy" {{ dt("referanslar.ogeler.$i.gorsel", 'src') }}>
                                    </button>
                                @endforeach
                            </div>
                            <div class="referans-oklar">
                                <button type="button" data-yon="-1" aria-label="Önceki"><i class="bi bi-arrow-left"></i></button>
                                <button type="button" data-yon="1" aria-label="Sonraki"><i class="bi bi-arrow-right"></i></button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="referans-buyuk anim sag">
                @foreach ($referanslar as $i => $referans)
                    <img src="{{ tema_url($referans['gorsel']) }}" alt="{{ $referans['isim'] }}" @class(['aktif' => $i === 0]) {{ dt("referanslar.ogeler.$i.gorsel", 'src') }}>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif
