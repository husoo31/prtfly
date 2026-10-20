<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Kurulum — Laravel Portfolyo</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/yonetim.css') }}?v={{ config('portfolyo.surum') }}">
</head>
<body class="y-giris" style="display:block">
    <div class="kurulum">
        <div class="text-center mb-4">
            <span class="y-marka-ikon buyuk"><i class="bi bi-stars"></i></span>
            <h1 class="h3 mt-3 mb-1 fw-bold">Laravel Portfolyo Kurulumu</h1>
            <p class="text-muted">Birkaç bilgiyle sitenizi dakikalar içinde yayına alın.</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $hata)
                        <li>{{ $hata }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="y-kart mb-4">
            <h2 class="h6 fw-bold mb-3">1. Sunucu gereksinimleri</h2>
            <div class="kurulum-gereksinim">
                @foreach ($gereksinimler as $ad => $tamam)
                    <div>
                        <i @class(['bi', 'bi-check-circle-fill text-success' => $tamam, 'bi-x-circle-fill text-danger' => ! $tamam])></i>
                        {{ $ad }}
                    </div>
                @endforeach
            </div>
        </div>

        <form method="POST" action="{{ route('kurulum.kur') }}" class="y-kart" data-kurulum>
            @csrf
            <h2 class="h6 fw-bold mb-3">2. Site ve veritabanı</h2>
            <div class="mb-3">
                <label class="form-label">Site adresi</label>
                <input type="url" name="site_url" value="{{ old('site_url', $varsayilanUrl) }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Veritabanı türü</label>
                <div class="d-flex gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="db_tur" id="db-mysql" value="mysql" @checked(old('db_tur', 'mysql') === 'mysql')>
                        <label class="form-check-label" for="db-mysql">MySQL / MariaDB (önerilen)</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="db_tur" id="db-sqlite" value="sqlite" @checked(old('db_tur') === 'sqlite')>
                        <label class="form-check-label" for="db-sqlite">SQLite (dosya)</label>
                    </div>
                </div>
            </div>

            <div class="row" data-mysql>
                <div class="col-md-8 mb-3">
                    <label class="form-label">Sunucu</label>
                    <input type="text" name="db_host" value="{{ old('db_host', 'localhost') }}" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Port</label>
                    <input type="number" name="db_port" value="{{ old('db_port', 3306) }}" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Veritabanı adı</label>
                    <input type="text" name="db_ad" value="{{ old('db_ad') }}" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Kullanıcı</label>
                    <input type="text" name="db_kullanici" value="{{ old('db_kullanici') }}" class="form-control" autocomplete="off">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Şifre</label>
                    <input type="password" name="db_sifre" class="form-control" autocomplete="new-password">
                </div>
            </div>

            <h2 class="h6 fw-bold mb-3 mt-2">3. Yönetici hesabı</h2>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Ad</label>
                    <input type="text" name="ad" value="{{ old('ad') }}" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">E-posta</label>
                    <input type="email" name="eposta" value="{{ old('eposta') }}" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Şifre (en az 8 karakter)</label>
                    <input type="password" name="sifre" class="form-control" minlength="8" required autocomplete="new-password">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Şifre (tekrar)</label>
                    <input type="password" name="sifre_confirmation" class="form-control" minlength="8" required autocomplete="new-password">
                </div>
            </div>

            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="demo" value="1" id="demo" @checked(old('demo', true))>
                <label class="form-check-label" for="demo">Örnek projeleri ve blog yazılarını yükle</label>
            </div>

            <button class="btn btn-dark btn-lg w-100">Kurulumu tamamla <i class="bi bi-arrow-right ms-1"></i></button>
        </form>

        <p class="y-giris-alt">
            Laravel Portfolyo, <a href="https://webdanismani.com" target="_blank" rel="noopener">webdanismani.com</a> tarafından ücretsiz dağıtılır; satışı yasaktır.
            Destek: <a href="https://oblifex.com" target="_blank" rel="noopener">oblifex.com</a>
        </p>
    </div>

    <script>
        (function () {
            var form = document.querySelector('[data-kurulum]');
            var mysql = form.querySelector('[data-mysql]');
            function guncelle() {
                var secili = form.querySelector('input[name="db_tur"]:checked');
                mysql.style.display = secili && secili.value === 'mysql' ? '' : 'none';
            }
            form.querySelectorAll('input[name="db_tur"]').forEach(function (r) { r.addEventListener('change', guncelle); });
            guncelle();
        })();
    </script>
</body>
</html>
