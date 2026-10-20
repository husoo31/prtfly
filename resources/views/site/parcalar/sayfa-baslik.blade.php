<section class="sayfa-baslik">
    <div class="container">
        <h1 class="anim" @if (! empty($yol)) {{ dt($yol) }} @endif>{{ $baslik }}</h1>
        <nav class="yol anim" style="--gecikme: .15s" aria-label="breadcrumb">
            <a href="{{ route('anasayfa') }}" {{ dt('menu.ogeler.0.etiket') }}>{{ tema('menu.ogeler.0.etiket', 'Ana Sayfa') }}</a>
            @foreach ($kirintilar ?? [] as $etiket => $adres)
                <span>/</span>
                @if ($adres)
                    <a href="{{ $adres }}">{{ $etiket }}</a>
                @else
                    <span class="vurgu">{{ $etiket }}</span>
                @endif
            @endforeach
        </nav>
    </div>
    <x-yildiz />
</section>
