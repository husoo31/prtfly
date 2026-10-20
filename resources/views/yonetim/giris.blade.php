@extends('yonetim.duzen')

@section('baslik', 'Giriş')
@section('govde_sinifi', 'y-giris-sayfa')

@section('tam_ekran')
    <div class="y-giris">
        <div class="y-giris-kart">
            <div class="text-center mb-4">
                <span class="y-marka-ikon buyuk"><i class="bi bi-stars"></i></span>
                <h1 class="h4 mt-3 mb-1">{{ tema('genel.site_adi') }}</h1>
                <p class="text-muted mb-0">Yönetim paneline giriş yapın</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('yonetim.giris.yap') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="email">E-posta</label>
                    <input type="email" class="form-control form-control-lg" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">Şifre</label>
                    <input type="password" class="form-control form-control-lg" id="password" name="password" required autocomplete="current-password">
                </div>
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="hatirla" id="hatirla" value="1">
                    <label class="form-check-label" for="hatirla">Beni hatırla</label>
                </div>
                <button class="btn btn-dark btn-lg w-100">Giriş yap <i class="bi bi-arrow-right ms-1"></i></button>
            </form>

            <p class="text-center text-muted small mt-4 mb-0">
                <a href="{{ route('anasayfa') }}" class="text-reset"><i class="bi bi-arrow-left"></i> Siteye dön</a>
            </p>
        </div>
        <p class="y-giris-alt">
            Laravel Portfolyo · <a href="{{ config('portfolyo.dagitici.site') }}" target="_blank" rel="noopener">webdanismani.com</a> tarafından ücretsiz dağıtılır.
        </p>
    </div>
@endsection
