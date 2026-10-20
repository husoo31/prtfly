@extends('yonetim.duzen')

@section('baslik', 'Tema Düzenleyici')
@section('govde_sinifi', 'td-sayfa')

@section('tam_ekran')
    <div class="td" id="tema-duzenleyici">
        <header class="td-ust">
            <a href="{{ route('yonetim.panel') }}" class="td-geri" title="Panele dön"><i class="bi bi-x-lg"></i></a>
            <div class="td-baslik">
                <strong>Tema Düzenleyici</strong>
                <span class="td-durum" data-durum>
                    @if ($taslakVar)
                        <i class="bi bi-circle-fill text-warning"></i> Yayınlanmamış değişiklikler var
                    @else
                        <i class="bi bi-check-circle-fill text-success"></i> Yayındaki sürüm
                    @endif
                </span>
            </div>

            <div class="td-cihazlar btn-group" role="group" aria-label="Önizleme genişliği">
                <button type="button" class="btn btn-sm aktif" data-cihaz="masaustu" title="Masaüstü"><i class="bi bi-display"></i></button>
                <button type="button" class="btn btn-sm" data-cihaz="tablet" title="Tablet"><i class="bi bi-tablet"></i></button>
                <button type="button" class="btn btn-sm" data-cihaz="mobil" title="Mobil"><i class="bi bi-phone"></i></button>
            </div>

            <select class="form-select form-select-sm td-sayfa-sec" data-sayfa title="Önizlenen sayfa">
                <option value="{{ route('anasayfa') }}">Ana sayfa</option>
                <option value="{{ route('projeler') }}">Tüm projeler</option>
                <option value="{{ route('blog') }}">Blog</option>
                <option value="{{ route('hata.onizleme') }}">404 sayfası</option>
            </select>

            <div class="td-eylemler">
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-light" data-bs-toggle="dropdown" title="Diğer"><i class="bi bi-three-dots"></i></button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('yonetim.tema.disa-aktar') }}"><i class="bi bi-download me-2"></i>Yayındaki temayı dışa aktar</a></li>
                        <li><button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#ice-aktar"><i class="bi bi-upload me-2"></i>Tema içe aktar</button></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><button type="button" class="dropdown-item" data-eylem="taslak-sil"><i class="bi bi-arrow-counterclockwise me-2"></i>Taslağı at (yayındakine dön)</button></li>
                        <li><button type="button" class="dropdown-item text-danger" data-eylem="sifirla"><i class="bi bi-arrow-repeat me-2"></i>Varsayılan temaya sıfırla</button></li>
                    </ul>
                </div>
                <a href="{{ route('anasayfa') }}" target="_blank" class="btn btn-sm btn-outline-light" title="Siteyi yeni sekmede aç"><i class="bi bi-box-arrow-up-right"></i></a>
                <button type="button" class="btn btn-sm btn-vurgu" data-eylem="yayinla">
                    <i class="bi bi-cloud-arrow-up me-1"></i>Yayınla
                </button>
            </div>
        </header>

        <div class="td-govde">
            <aside class="td-panel">
                <div class="td-panel-ic" data-panel></div>
            </aside>
            <section class="td-onizleme">
                <div class="td-cerceve" data-cerceve>
                    <iframe src="{{ route('anasayfa', ['onizleme' => 1]) }}" title="Canlı önizleme" data-iframe></iframe>
                    <div class="td-yukleniyor" data-yukleniyor><span class="spinner-border spinner-border-sm"></span> Önizleme yenileniyor</div>
                </div>
            </section>
        </div>
    </div>

    <div class="modal fade" id="ice-aktar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ route('yonetim.tema.ice-aktar') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tema içe aktar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">Daha önce dışa aktardığınız <code>.json</code> dosyasını seçin. İçe aktarılan tema önce taslak olarak yüklenir; inceledikten sonra "Yayınla" ile kaydedin.</p>
                    <input type="file" name="dosya" accept=".json,application/json" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link text-muted" data-bs-dismiss="modal">Vazgeç</button>
                    <button class="btn btn-dark">İçe aktar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div class="toast align-items-center border-0" role="status" data-bildirim>
            <div class="d-flex">
                <div class="toast-body" data-bildirim-metin></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Kapat"></button>
            </div>
        </div>
    </div>
@endsection

@push('betikler')
    <script>
        window.TemaDuzenleyici = {
            sema: @json($sema),
            degerler: @json($degerler),
            canliTipler: @json($canliTipler),
            taslakVar: @json($taslakVar),
            adresler: {
                taslak: @json(route('yonetim.tema.taslak')),
                yayinla: @json(route('yonetim.tema.yayinla')),
                taslakSil: @json(route('yonetim.tema.taslak-sil')),
                sifirla: @json(route('yonetim.tema.sifirla'))
            },
            bildirim: @json(session('basari'))
        };
    </script>
    <script src="{{ asset('assets/js/tema-duzenleyici.js') }}?v={{ config('portfolyo.surum') }}"></script>
@endpush
