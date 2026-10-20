@php $kelimeler = tema('kayan_yazi.ogeler', []); @endphp
@if (count($kelimeler))
<div class="kayan bolum-ust" aria-hidden="true">
    <div class="kayan-serit">
        @for ($kopya = 0; $kopya < 2; $kopya++)
            <span>
                @for ($tur = 0; $tur < 3; $tur++)
                    @foreach ($kelimeler as $i => $kelime)
                        <span @class(['oge', 'cerceve' => ($i + $tur * count($kelimeler)) % 2 === 1]) {{ dt("kayan_yazi.ogeler.$i.metin") }}>{{ $kelime['metin'] }}</span><span @class(['oge', 'cerceve' => ($i + $tur * count($kelimeler)) % 2 === 1])>_</span>
                    @endforeach
                @endfor
            </span>
        @endfor
    </div>
</div>
@endif
