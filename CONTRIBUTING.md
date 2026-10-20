# Katkıda Bulunma

Laravel Portfolyo'ya katkı vermek istediğiniz için teşekkürler! 🎉

## Başlamadan önce

- Büyük değişiklikleri önce [oblifex.com](https://oblifex.com) forumunda veya bir GitHub issue'sunda tartışın.
- Katkılarınız [LICENSE](LICENSE) dosyasındaki koşullarla dağıtılır; yazılım ücretsizdir ve satışı yasaktır.

## Geliştirme ortamı

```bash
git clone https://github.com/webdanismani/laravel-portfolyo.git
cd laravel-portfolyo
composer install
cp .env.example .env
php artisan key:generate
php artisan portfolyo:kur
php artisan serve
```

## Kod kuralları

- Kod tabanı **Türkçe** adlandırma kullanır (ör. `Proje`, `BlogYazisi`, `tema()`, `yayinla()`). Yeni kodda da bu düzeni koruyun.
- PHP kodu [Laravel Pint](https://laravel.com/docs/pint) ile biçimlendirilir: `./vendor/bin/pint`
- Ön yüz bağımlılıksız (vanilla) JavaScript ve sade CSS kullanır; derleme adımı yoktur.

## Tema alanı eklemek

1. `config/tema.php` içinde ilgili bölüme alan tanımını ekleyin.
2. Şablonda `tema('bolum.alan')` ile okuyun ve canlı önizleme için `{{ dt('bolum.alan') }}` özniteliğini ekleyin.
3. Tema düzenleyici alanı otomatik olarak gösterir.

## Test

```bash
php artisan test
```

Pull request açmadan önce tüm testlerin geçtiğinden emin olun. GitHub Actions her PR'da testleri otomatik çalıştırır.
