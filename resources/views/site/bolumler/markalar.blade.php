<div class="markalar bolum-dis">
    <div class="container">
        <span class="ust-yazi" {{ dt('markalar.baslik') }}>{{ tema('markalar.baslik') }}</span>
        <div class="kaydirici otomatik">
            @foreach (tema('markalar.ogeler', []) as $i => $marka)
                <a href="{{ tema_url($marka['baglanti']) ?: '#' }}" class="marka" @if (tema_harici($marka['baglanti'])) target="_blank" rel="noopener" @endif>
                    <img src="{{ tema_url($marka['gorsel']) }}" alt="{{ $marka['ad'] }}" loading="lazy" {{ dt("markalar.ogeler.$i.gorsel", 'src') }}>
                </a>
            @endforeach
        </div>
    </div>
</div>
