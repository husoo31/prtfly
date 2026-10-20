<a href="{{ route('anasayfa') }}" {{ $attributes->merge(['class' => 'logo']) }}>
    @if (tema('genel.logo_gorsel'))
        <img src="{{ tema_url(tema('genel.logo_gorsel')) }}" alt="{{ tema('genel.site_adi') }}" {{ dt('genel.logo_gorsel', 'src') }}>
    @else
        {{ tema_logo() }}
    @endif
</a>
