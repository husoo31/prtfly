<div class="perde"></div>
<aside class="yan-panel" aria-hidden="true">
    <button type="button" class="yan-panel-kapat" aria-label="Kapat"><i class="bi bi-x-lg"></i></button>
    <x-logo />
    <p {{ dt('yan_panel.aciklama') }}>{{ tema('yan_panel.aciklama') }}</p>

    @foreach (['adres', 'eposta', 'telefon'] as $tur)
        @if (tema("yan_panel.$tur"))
            <div class="bilgi">
                <span class="bilgi-baslik" {{ dt("yan_panel.{$tur}_baslik") }}>{{ tema("yan_panel.{$tur}_baslik") }}</span>
                @php
                    $deger = tema("yan_panel.$tur");
                    $adres = match ($tur) {
                        'eposta' => 'mailto:'.$deger,
                        'telefon' => 'tel:'.preg_replace('/[^0-9+]/', '', $deger),
                        default => null,
                    };
                @endphp
                @if ($adres)
                    <a href="{{ $adres }}" class="bilgi-deger" {{ dt("yan_panel.$tur") }}>{{ $deger }}</a>
                @else
                    <span class="bilgi-deger" {{ dt("yan_panel.$tur") }}>{{ $deger }}</span>
                @endif
            </div>
        @endif
    @endforeach

    <x-sosyal sinif="sosyal-daire" class="mt-4" />

    <a href="{{ tema_baglanti('#contact') }}" class="btn-kare yan-panel-baglanti">
        <span {{ dt('yan_panel.buton_metin') }}>{{ tema('yan_panel.buton_metin') }}</span>
        <i class="bi bi-arrow-up-right"></i>
    </a>
</aside>
