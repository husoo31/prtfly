@extends('yonetim.duzen')

@section('baslik', 'Profil')

@section('icerik')
    <form method="POST" action="{{ route('yonetim.profil.kaydet') }}" class="y-kart" style="max-width: 640px">
        @csrf
        <h2 class="h6 mb-3">Hesap bilgileri</h2>
        <div class="mb-3">
            <label class="form-label">Ad</label>
            <input type="text" name="name" value="{{ old('name', $kullanici->name) }}" class="form-control" required maxlength="100">
        </div>
        <div class="mb-4">
            <label class="form-label">E-posta</label>
            <input type="email" name="email" value="{{ old('email', $kullanici->email) }}" class="form-control" required maxlength="190" autocomplete="username">
        </div>

        <h2 class="h6 mb-3">Şifre değiştir</h2>
        <div class="mb-3">
            <label class="form-label">Mevcut şifre</label>
            <input type="password" name="mevcut_sifre" class="form-control" autocomplete="current-password">
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Yeni şifre</label>
                <input type="password" name="password" class="form-control" minlength="8" autocomplete="new-password">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Yeni şifre (tekrar)</label>
                <input type="password" name="password_confirmation" class="form-control" minlength="8" autocomplete="new-password">
            </div>
        </div>
        <div class="form-text mb-3">Şifreyi değiştirmek istemiyorsanız bu alanları boş bırakın.</div>
        <button class="btn btn-dark"><i class="bi bi-check-lg me-1"></i>Kaydet</button>
    </form>
@endsection
