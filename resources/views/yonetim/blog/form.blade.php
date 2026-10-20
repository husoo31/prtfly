@extends('yonetim.duzen')

@section('baslik', $yazi->exists ? 'Yazıyı düzenle' : 'Yeni yazı')

@section('icerik')
    <form method="POST" action="{{ $yazi->exists ? route('yonetim.blog.update', $yazi) : route('yonetim.blog.store') }}">
        @csrf
        @if ($yazi->exists) @method('PUT') @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="y-kart">
                    <div class="mb-3">
                        <label class="form-label">Başlık *</label>
                        <input type="text" name="baslik" value="{{ old('baslik', $yazi->baslik) }}" class="form-control form-control-lg" required maxlength="190">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kısa adres (slug)</label>
                        <div class="input-group">
                            <span class="input-group-text">/blog/</span>
                            <input type="text" name="slug" value="{{ old('slug', $yazi->slug) }}" class="form-control" maxlength="190" placeholder="Boş bırakılırsa başlıktan üretilir">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Özet</label>
                        <textarea name="ozet" rows="2" class="form-control" maxlength="1000">{{ old('ozet', $yazi->ozet) }}</textarea>
                        <div class="form-text">Listelerde ve arama motoru açıklamasında kullanılır.</div>
                    </div>
                    <div class="mb-0">
                        <div class="d-flex justify-content-between align-items-end">
                            <label class="form-label">İçerik</label>
                            <div class="btn-group btn-group-sm mb-2 y-md-arac" data-hedef="#icerik">
                                <button type="button" class="btn btn-outline-secondary" data-md="**|**" title="Kalın"><i class="bi bi-type-bold"></i></button>
                                <button type="button" class="btn btn-outline-secondary" data-md="*|*" title="İtalik"><i class="bi bi-type-italic"></i></button>
                                <button type="button" class="btn btn-outline-secondary" data-md="\n## |" title="Başlık"><i class="bi bi-type-h2"></i></button>
                                <button type="button" class="btn btn-outline-secondary" data-md="[|](https://)" title="Bağlantı"><i class="bi bi-link-45deg"></i></button>
                                <button type="button" class="btn btn-outline-secondary" data-md="\n- |" title="Liste"><i class="bi bi-list-ul"></i></button>
                                <button type="button" class="btn btn-outline-secondary" data-md="\n> |" title="Alıntı"><i class="bi bi-quote"></i></button>
                                <button type="button" class="btn btn-outline-secondary" data-md-gorsel title="Görsel"><i class="bi bi-image"></i></button>
                            </div>
                        </div>
                        <textarea name="icerik" id="icerik" rows="18" class="form-control font-monospace" maxlength="100000">{{ old('icerik', $yazi->icerik) }}</textarea>
                        <div class="form-text">Markdown desteklenir. Güvenlik için ham HTML etiketleri temizlenir.</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="y-kart mb-4">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch" name="aktif" value="1" id="aktif" @checked(old('aktif', $yazi->aktif))>
                        <label class="form-check-label" for="aktif">Yayında</label>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Yayın tarihi</label>
                        <input type="datetime-local" name="yayin_tarihi" value="{{ old('yayin_tarihi', $yazi->yayin_tarihi?->format('Y-m-d\TH:i')) }}" class="form-control">
                        <div class="form-text">İleri bir tarih seçerseniz yazı o zaman yayınlanır.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <input type="text" name="kategori" value="{{ old('kategori', $yazi->kategori) }}" class="form-control" list="blog-kategorileri" maxlength="100">
                        <datalist id="blog-kategorileri">
                            @foreach ($kategoriler as $k)
                                <option value="{{ $k }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Etiketler</label>
                        <input type="text" name="etiketler" value="{{ old('etiketler', $yazi->etiketler) }}" class="form-control" maxlength="190" placeholder="ux, tasarım">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Yazar</label>
                        <input type="text" name="yazar" value="{{ old('yazar', $yazi->yazar) }}" class="form-control" maxlength="100">
                    </div>
                    <button class="btn btn-dark w-100"><i class="bi bi-check-lg me-1"></i>Kaydet</button>
                    <a href="{{ route('yonetim.blog.index') }}" class="btn btn-link w-100 text-muted mt-1">Vazgeç</a>
                </div>

                <div class="y-kart">
                    <x-gorsel-sec ad="kapak" :deger="old('kapak', $yazi->kapak)" etiket="Kapak görseli" />
                </div>
            </div>
        </div>
    </form>
@endsection
