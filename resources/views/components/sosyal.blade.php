@props(['sinif' => 'sosyal-gri'])
<ul {{ $attributes->merge(['class' => $sinif]) }}>
    @foreach (tema('sosyal.ogeler', []) as $i => $hesap)
        <li>
            <a href="{{ tema_url($hesap['baglanti']) }}" target="_blank" rel="noopener" aria-label="{{ config('tema.bolumler.sosyal.alanlar.ogeler.alanlar.ikon.secenekler')[$hesap['ikon']] ?? 'Sosyal' }}" {{ dt("sosyal.ogeler.$i.baglanti", 'href') }}>
                <i class="{{ $hesap['ikon'] }}"></i>
            </a>
        </li>
    @endforeach
</ul>
