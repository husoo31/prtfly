<div class="proje-izgara" data-proje-izgara>
    @foreach ($projeler as $i => $proje)
        <div class="proje-kutu {{ $proje->boyut }} {{ $proje->filtreSiniflari() }} anim" style="--gecikme: {{ 0.1 + ($i % 3) * 0.15 }}s">
            <a href="#proje-{{ $proje->id }}" class="proje" data-pencere="proje-{{ $proje->id }}">
                <img src="{{ tema_url($proje->kapak ?: 'assets/img/demo/proje-1.svg') }}" alt="{{ $proje->baslik }}" loading="lazy">
                <span class="incele"><span {{ dt('projeler.detay_buton') }}>{{ tema('projeler.detay_buton') }}</span> <i class="bi bi-arrow-up-right"></i></span>
                <h4 class="proje-adi">{{ $proje->kategoriler()[0] ?? $proje->baslik }}</h4>
            </a>
        </div>
    @endforeach
</div>

@push('pencereler')
    @foreach ($projeler as $i => $proje)
        <div class="pencere" id="proje-{{ $proje->id }}" role="dialog" aria-modal="true" aria-label="{{ $proje->baslik }}">
            <div class="pencere-kutu">
                <button type="button" class="pencere-kapat" aria-label="Kapat"><i class="bi bi-x-lg"></i></button>
                <img class="kapak" src="{{ tema_url($proje->kapak ?: 'assets/img/demo/proje-1.svg') }}" alt="{{ $proje->baslik }}" loading="lazy">

                <div class="proje-meta">
                    @if ($proje->musteri)
                        <div><p>{{ tema('projeler.musteri_etiketi') }}</p><h6>{{ $proje->musteri }}</h6></div>
                    @endif
                    @if ($proje->kategori)
                        <div><p>{{ tema('projeler.kategori_etiketi') }}</p><h6>{{ implode(', ', $proje->kategoriler()) }}</h6></div>
                    @endif
                    @if ($proje->tarih)
                        <div><p>{{ tema('projeler.tarih_etiketi') }}</p><h6>{{ $proje->tarih }}</h6></div>
                    @endif
                </div>

                <div class="proje-detay">
                    <h3>{{ $proje->baslik }}</h3>
                    @if ($proje->ozet)
                        <p>{{ $proje->ozet }}</p>
                    @endif
                    @if ($proje->icerik)
                        <div class="yazi-icerik mt-3">{!! \Illuminate\Support\Str::markdown($proje->icerik, ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}</div>
                    @endif
                    @if ($proje->maddeListesi())
                        <ul>
                            @foreach ($proje->maddeListesi() as $madde)
                                <li>{{ $madde }}</li>
                            @endforeach
                        </ul>
                    @endif
                    @if ($proje->gorsel_1 || $proje->gorsel_2)
                        <div class="row g-4 yan-gorseller">
                            @foreach (array_filter([$proje->gorsel_1, $proje->gorsel_2]) as $gorsel)
                                <div class="col-md-6"><img src="{{ tema_url($gorsel) }}" alt="{{ $proje->baslik }}" loading="lazy"></div>
                            @endforeach
                        </div>
                    @endif
                    @if ($proje->baglanti)
                        <a href="{{ $proje->baglanti }}" target="_blank" rel="noopener" class="btn-hap dolu mt-4">{{ parse_url($proje->baglanti, PHP_URL_HOST) ?: $proje->baglanti }} <i class="bi bi-box-arrow-up-right"></i></a>
                    @endif

                    <div class="pencere-alt">
                        <button type="button" data-pencere-git="-1" aria-label="Önceki proje"><i class="bi bi-arrow-left"></i></button>
                        <x-yildiz class="position-static" style="width:28px;height:28px" />
                        <button type="button" data-pencere-git="1" aria-label="Sonraki proje"><i class="bi bi-arrow-right"></i></button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endpush
