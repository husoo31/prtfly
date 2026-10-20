@if (count($kategoriler) > 1)
    <ul class="filtre anim" style="--gecikme: .2s" data-filtre>
        <li><button type="button" class="aktif" data-filtre-deger="*" {{ dt('projeler.tumu_etiketi') }}>{{ tema('projeler.tumu_etiketi') }}</button></li>
        @foreach ($kategoriler as $kategori)
            <li><button type="button" data-filtre-deger="k-{{ \Illuminate\Support\Str::slug($kategori) }}">{{ $kategori }}</button></li>
        @endforeach
    </ul>
@endif
