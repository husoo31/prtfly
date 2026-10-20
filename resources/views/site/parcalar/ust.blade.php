<header class="ust-bolum">
    <div class="container-fluid px-0">
        <div class="ust-sarici">
            <div class="ust-logo-kap">
                <x-logo />
            </div>
            <ul class="ana-menu">
                @foreach (tema('menu.ogeler', []) as $i => $oge)
                    <li>
                        <a href="{{ tema_baglanti($oge['baglanti']) }}" @if (tema_harici($oge['baglanti'])) target="_blank" rel="noopener" @endif {{ dt("menu.ogeler.$i.etiket") }}>{{ $oge['etiket'] }}</a>
                    </li>
                @endforeach
            </ul>
            <div class="menu-bilesenler">
                @if (tema('menu.buton_metin'))
                    <a href="{{ tema_baglanti(tema('menu.buton_baglanti')) }}" class="konusalim d-none d-xl-inline-flex">
                        <span {{ dt('menu.buton_metin') }}>{{ tema('menu.buton_metin') }}</span>
                        <i class="bi bi-arrow-up-right"></i>
                    </a>
                @endif
                <button type="button" class="menu-ac" aria-label="Menü"><span></span><span></span><span></span></button>
                <button type="button" class="yan-panel-ac" aria-label="Yan panel"><span class="hamburger"></span></button>
            </div>
        </div>
    </div>
</header>
