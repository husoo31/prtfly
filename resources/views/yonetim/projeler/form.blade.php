@extends('yonetim.duzen')

@section('baslik', $proje->exists ? 'Projeyi düzenle' : 'Yeni proje')

@section('icerik')
    <form method="POST" action="{{ $proje->exists ? route('yonetim.projeler.update', $proje) : route('yonetim.projeler.store') }}">
        @csrf
        @if ($proje->exists) @method('PUT') @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="y-kart">
                    <div class="mb-3">
                        <label class="form-label">Başlık *</label>
                        <input type="text" name="baslik" value="{{ old('baslik', $proje->baslik) }}" class="form-control form-control-lg" required maxlength="190">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori</label>
                            <input type="text" name="kategori" value="{{ old('kategori', $proje->kategori) }}" class="form-control" list="kategori-onerileri" maxlength="190">
                            <datalist id="kategori-onerileri">
                                @foreach ($kategoriler as $k)
                                    <option value="{{ $k }}">
                                @endforeach
                            </datalist>
                            <div class="form-text">Birden fazla kategori için virgülle ayırın. Filtre butonları bunlardan oluşur.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kısa adres (slug)</label>
                            <input type="text" name="slug" value="{{ old('slug', $proje->slug) }}" class="form-control" maxlength="190" placeholder="Boş bırakılırsa başlıktan üretilir">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Müşteri</label>
                            <input type="text" name="musteri" value="{{ old('musteri', $proje->musteri) }}" class="form-control" maxlength="190">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tarih</label>
                            <input type="text" name="tarih" value="{{ old('tarih', $proje->tarih) }}" class="form-control" maxlength="100" placeholder="Ör. Ağustos 2026">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Özet</label>
                        <textarea name="ozet" rows="2" class="form-control" maxlength="1000">{{ old('ozet', $proje->ozet) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Açıklama</label>
                        <textarea name="icerik" rows="7" class="form-control" maxlength="20000">{{ old('icerik', $proje->icerik) }}</textarea>
                        <div class="form-text">Markdown desteklenir: **kalın**, *italik*, [bağlantı](https://...), ## başlık.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Öne çıkan maddeler</label>
                        <textarea name="maddeler" rows="4" class="form-control" maxlength="5000">{{ old('maddeler', $proje->maddeler) }}</textarea>
                        <div class="form-text">Her satır bir madde.</div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Canlı proje adresi</label>
                        <input type="url" name="baglanti" value="{{ old('baglanti', $proje->baglanti) }}" class="form-control" maxlength="500" placeholder="https://">
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="y-kart mb-4">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch" name="aktif" value="1" id="aktif" @checked(old('aktif', $proje->aktif))>
                        <label class="form-check-label" for="aktif">Yayında</label>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Izgara boyutu</label>
                        <select name="boyut" class="form-select">
                            @foreach (\App\Models\Proje::BOYUTLAR as $deger => $etiket)
                                <option value="{{ $deger }}" @selected(old('boyut', $proje->boyut) === $deger)>{{ $etiket }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">Bir satırda Büyük + Orta ya da üç Normal kutu yan yana gelir.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sıra</label>
                        <input type="number" name="sira" value="{{ old('sira', $proje->sira) }}" class="form-control" min="0">
                    </div>
                    <button class="btn btn-dark w-100"><i class="bi bi-check-lg me-1"></i>Kaydet</button>
                    <a href="{{ route('yonetim.projeler.index') }}" class="btn btn-link w-100 text-muted mt-1">Vazgeç</a>
                </div>

                <div class="y-kart">
                    <x-gorsel-sec ad="kapak" :deger="old('kapak', $proje->kapak)" etiket="Kapak görseli" />
                    <x-gorsel-sec ad="gorsel_1" :deger="old('gorsel_1', $proje->gorsel_1)" etiket="Detay görseli 1" />
                    <x-gorsel-sec ad="gorsel_2" :deger="old('gorsel_2', $proje->gorsel_2)" etiket="Detay görseli 2" />
                </div>
            </div>
        </div>
    </form>
@endsection
