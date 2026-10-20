@props(['ad', 'deger' => '', 'etiket' => null, 'yardim' => null])
<div class="mb-3">
    @if ($etiket)
        <label class="form-label">{{ $etiket }}</label>
    @endif
    <div class="gorsel-sec" data-gorsel-sec>
        <div class="gorsel-onizleme" data-onizleme>
            @if ($deger)
                <img src="{{ tema_url($deger) }}" alt="">
            @else
                <i class="bi bi-image"></i>
            @endif
        </div>
        <div class="flex-grow-1">
            <input type="text" name="{{ $ad }}" value="{{ $deger }}" class="form-control form-control-sm mb-2" placeholder="uploads/... veya https://..." data-deger>
            <div class="d-flex flex-wrap gap-2">
                <label class="btn btn-sm btn-dark mb-0">
                    <i class="bi bi-upload me-1"></i>Yükle
                    <input type="file" accept="image/*" class="d-none" data-dosya>
                </label>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-kutuphane><i class="bi bi-images me-1"></i>Kütüphane</button>
                <button type="button" class="btn btn-sm btn-outline-danger" data-temizle><i class="bi bi-x-lg"></i></button>
            </div>
        </div>
    </div>
    @if ($yardim)
        <div class="form-text">{{ $yardim }}</div>
    @endif
</div>
