<?php

namespace Tests\Feature;

use App\Models\BlogYazisi;
use App\Models\Mesaj;
use App\Models\Proje;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class YonetimTest extends TestCase
{
    use RefreshDatabase;

    protected function yonetici(): User
    {
        return User::factory()->create(['email' => 'yonetici@example.com', 'password' => 'gizli-sifre-123']);
    }

    public function test_misafir_giris_sayfasina_yonlendirilir(): void
    {
        $this->get('/yonetim')->assertRedirect('/yonetim/giris');
        $this->get('/yonetim/giris')->assertOk()->assertSee('Giriş yap');
    }

    public function test_giris_ve_cikis(): void
    {
        $this->yonetici();

        $this->post('/yonetim/giris', ['email' => 'yonetici@example.com', 'password' => 'yanlis'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->post('/yonetim/giris', ['email' => 'yonetici@example.com', 'password' => 'gizli-sifre-123'])
            ->assertRedirect('/yonetim');
        $this->assertAuthenticated();

        $this->post('/yonetim/cikis')->assertRedirect('/yonetim/giris');
        $this->assertGuest();
    }

    public function test_panel_sayfalari_acilir(): void
    {
        $this->actingAs($this->yonetici());

        foreach (['/yonetim', '/yonetim/projeler', '/yonetim/projeler/create', '/yonetim/blog', '/yonetim/blog/create', '/yonetim/mesajlar', '/yonetim/ayarlar', '/yonetim/profil', '/yonetim/tema'] as $adres) {
            $this->get($adres)->assertOk();
        }
    }

    public function test_proje_ekle_duzenle_sil(): void
    {
        $this->actingAs($this->yonetici());

        $this->post('/yonetim/projeler', [
            'baslik' => 'Yeni Kafe Sitesi',
            'kategori' => 'Web Tasarım, Marka',
            'boyut' => 'buyuk',
            'aktif' => '1',
        ])->assertRedirect('/yonetim/projeler');

        $proje = Proje::firstWhere('baslik', 'Yeni Kafe Sitesi');
        $this->assertSame('yeni-kafe-sitesi', $proje->slug);
        $this->assertSame(['Web Tasarım', 'Marka'], $proje->kategoriler());

        $this->get('/')->assertSee('k-web-tasarim', false);

        $this->put('/yonetim/projeler/'.$proje->id, ['baslik' => 'Kafe', 'boyut' => 'normal'])
            ->assertRedirect('/yonetim/projeler');
        $this->assertFalse($proje->fresh()->aktif);

        $this->delete('/yonetim/projeler/'.$proje->id)->assertRedirect('/yonetim/projeler');
        $this->assertModelMissing($proje);
    }

    public function test_blog_yazisi_ekle_ve_zamanla(): void
    {
        $this->actingAs($this->yonetici());

        $this->post('/yonetim/blog', [
            'baslik' => 'Gelecek Yazı',
            'icerik' => "Merhaba **dünya**\n\n<script>alert(1)</script>",
            'yayin_tarihi' => now()->addDay()->format('Y-m-d\TH:i'),
            'aktif' => '1',
        ])->assertRedirect('/yonetim/blog');

        $yazi = BlogYazisi::firstWhere('baslik', 'Gelecek Yazı');
        $this->assertStringNotContainsString('<script>', (string) $yazi->icerikHtml());
        $this->assertStringContainsString('<strong>dünya</strong>', (string) $yazi->icerikHtml());

        auth()->logout();
        $this->get('/blog/'.$yazi->slug)->assertNotFound();
    }

    public function test_mesaj_okununca_isaretlenir(): void
    {
        $this->actingAs($this->yonetici());
        $mesaj = Mesaj::create(['ad' => 'Can', 'eposta' => 'can@example.com', 'mesaj' => 'Selam']);

        $this->get('/yonetim/mesajlar/'.$mesaj->id)->assertOk()->assertSee('Selam');
        $this->assertTrue($mesaj->fresh()->okundu);
    }

    public function test_profil_sifre_degisimi_mevcut_sifre_ister(): void
    {
        $kullanici = $this->yonetici();
        $this->actingAs($kullanici);

        $this->post('/yonetim/profil', [
            'name' => 'Yönetici',
            'email' => 'yonetici@example.com',
            'mevcut_sifre' => 'yanlis',
            'password' => 'yeni-sifre-456',
            'password_confirmation' => 'yeni-sifre-456',
        ])->assertSessionHasErrors('mevcut_sifre');

        $this->post('/yonetim/profil', [
            'name' => 'Yönetici',
            'email' => 'yonetici@example.com',
            'mevcut_sifre' => 'gizli-sifre-123',
            'password' => 'yeni-sifre-456',
            'password_confirmation' => 'yeni-sifre-456',
        ])->assertSessionHasNoErrors();
    }
}
