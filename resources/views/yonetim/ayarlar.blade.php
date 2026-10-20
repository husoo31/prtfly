@extends('yonetim.duzen')

@section('baslik', 'Ayarlar')

@section('icerik')
    <div class="row g-4">
        <div class="col-lg-7">
            <form method="POST" action="{{ route('yonetim.ayarlar.kaydet') }}" class="y-kart">
                @csrf
                <h2 class="h6 mb-3">Bildirimler</h2>
                <div class="mb-3">
                    <label class="form-label">İletişim formu bildirim e-postası</label>
                    <input type="email" name="bildirim_eposta" value="{{ old('bildirim_eposta', $bildirimEposta) }}" class="form-control" placeholder="ornek@alanadi.com">
                    <div class="form-text">Doluysa her yeni mesaj bu adrese de gönderilir. E-posta gönderimi için <code>.env</code> içindeki <code>MAIL_*</code> ayarlarını yapın.</div>
                </div>
                <button class="btn btn-dark"><i class="bi bi-check-lg me-1"></i>Kaydet</button>
            </form>
        </div>
        <div class="col-lg-5">
            <div class="y-kart">
                <h2 class="h6 mb-3">Sistem</h2>
                <dl class="row small mb-0">
                    <dt class="col-6 fw-normal text-muted">Yazılım sürümü</dt><dd class="col-6">{{ config('portfolyo.surum') }}</dd>
                    <dt class="col-6 fw-normal text-muted">Laravel</dt><dd class="col-6">{{ app()->version() }}</dd>
                    <dt class="col-6 fw-normal text-muted">PHP</dt><dd class="col-6">{{ PHP_VERSION }}</dd>
                    <dt class="col-6 fw-normal text-muted">Veritabanı</dt><dd class="col-6">{{ config('database.default') }}</dd>
                    <dt class="col-6 fw-normal text-muted">Dağıtıcı</dt><dd class="col-6"><a href="{{ config('portfolyo.dagitici.site') }}" target="_blank" rel="noopener">webdanismani.com</a></dd>
                    <dt class="col-6 fw-normal text-muted">Destek</dt><dd class="col-6"><a href="{{ config('portfolyo.dagitici.forum') }}" target="_blank" rel="noopener">oblifex.com forum</a></dd>
                </dl>
            </div>
        </div>
    </div>
@endsection
