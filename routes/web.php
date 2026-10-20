<?php

use App\Http\Controllers\KurulumController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\Yonetim\AyarController;
use App\Http\Controllers\Yonetim\BlogController;
use App\Http\Controllers\Yonetim\GirisController;
use App\Http\Controllers\Yonetim\MedyaController;
use App\Http\Controllers\Yonetim\MesajController;
use App\Http\Controllers\Yonetim\PanelController;
use App\Http\Controllers\Yonetim\ProfilController;
use App\Http\Controllers\Yonetim\ProjeController;
use App\Http\Controllers\Yonetim\TemaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Site
|--------------------------------------------------------------------------
*/

Route::middleware('onizleme')->group(function () {
    Route::get('/', [SiteController::class, 'anasayfa'])->name('anasayfa');
    Route::get('/projeler', [SiteController::class, 'projeler'])->name('projeler');
    Route::get('/blog', [SiteController::class, 'blog'])->name('blog');
    Route::get('/blog/{slug}', [SiteController::class, 'blogDetay'])->name('blog.detay');

    // Tema düzenleyicide 404 sayfasını önizlemek için.
    Route::get('/404', fn () => abort(404))->name('hata.onizleme');
});

Route::post('/iletisim', [SiteController::class, 'iletisim'])
    ->middleware('throttle:5,1')
    ->name('iletisim');

/*
|--------------------------------------------------------------------------
| Kurulum sihirbazı
|--------------------------------------------------------------------------
*/

Route::get('/kurulum', [KurulumController::class, 'form'])->name('kurulum');
Route::post('/kurulum', [KurulumController::class, 'kur'])->name('kurulum.kur');

/*
|--------------------------------------------------------------------------
| Yönetim paneli
|--------------------------------------------------------------------------
*/

Route::prefix('yonetim')->name('yonetim.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('giris', [GirisController::class, 'form'])->name('giris');
        Route::post('giris', [GirisController::class, 'giris'])->middleware('throttle:10,1')->name('giris.yap');
    });

    Route::middleware('auth')->group(function () {
        Route::post('cikis', [GirisController::class, 'cikis'])->name('cikis');

        Route::get('/', [PanelController::class, 'index'])->name('panel');

        Route::get('tema', [TemaController::class, 'index'])->name('tema');
        Route::post('tema/taslak', [TemaController::class, 'taslak'])->name('tema.taslak');
        Route::post('tema/yayinla', [TemaController::class, 'yayinla'])->name('tema.yayinla');
        Route::post('tema/taslak-sil', [TemaController::class, 'taslakSil'])->name('tema.taslak-sil');
        Route::post('tema/sifirla', [TemaController::class, 'sifirla'])->name('tema.sifirla');
        Route::get('tema/disa-aktar', [TemaController::class, 'disaAktar'])->name('tema.disa-aktar');
        Route::post('tema/ice-aktar', [TemaController::class, 'iceAktar'])->name('tema.ice-aktar');

        Route::post('medya/yukle', [MedyaController::class, 'yukle'])->name('medya.yukle');
        Route::get('medya', [MedyaController::class, 'liste'])->name('medya.liste');

        Route::resource('projeler', ProjeController::class)
            ->except('show')
            ->parameters(['projeler' => 'proje']);

        Route::resource('blog', BlogController::class)
            ->except('show')
            ->parameters(['blog' => 'yazi']);

        Route::get('mesajlar', [MesajController::class, 'index'])->name('mesajlar.index');
        Route::get('mesajlar/{mesaj}', [MesajController::class, 'show'])->name('mesajlar.show');
        Route::delete('mesajlar/{mesaj}', [MesajController::class, 'destroy'])->name('mesajlar.destroy');

        Route::get('ayarlar', [AyarController::class, 'index'])->name('ayarlar');
        Route::post('ayarlar', [AyarController::class, 'kaydet'])->name('ayarlar.kaydet');

        Route::get('profil', [ProfilController::class, 'index'])->name('profil');
        Route::post('profil', [ProfilController::class, 'kaydet'])->name('profil.kaydet');
    });
});
