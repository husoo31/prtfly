<?php

namespace Tests\Feature;

use App\Models\BlogYazisi;
use App\Models\Mesaj;
use App\Models\Proje;
use Database\Seeders\DemoIcerikSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DemoIcerikSeeder::class);
    }

    public function test_ana_sayfa_varsayilan_temayla_acilir(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Dynvel')
            ->assertSee('Hizmetlerim')
            ->assertSee(Proje::first()->baslik)
            ->assertSee(BlogYazisi::first()->baslik)
            ->assertDontSee('data-tema=', false);
    }

    public function test_projeler_sayfasi_acilir(): void
    {
        $this->get('/projeler')->assertOk()->assertSee('Tüm Projeler');
    }

    public function test_blog_listesi_arama_ve_detay(): void
    {
        $yazi = BlogYazisi::first();

        $this->get('/blog')->assertOk()->assertSee($yazi->baslik);
        $this->get('/blog?ara='.urlencode('Prototipleme'))->assertOk()->assertSee('Prototiplemenin');
        $this->get('/blog/'.$yazi->slug)->assertOk()->assertSee($yazi->baslik)->assertSee('<h2>', false);
    }

    public function test_yayinda_olmayan_yazi_gorunmez(): void
    {
        $yazi = BlogYazisi::first();
        $yazi->update(['aktif' => false]);

        $this->get('/blog/'.$yazi->slug)->assertNotFound();
    }

    public function test_ozel_404_sayfasi(): void
    {
        $this->get('/olmayan-sayfa')->assertNotFound()->assertSee('Sayfa bulunamadı');
    }

    public function test_iletisim_formu_mesaji_kaydeder(): void
    {
        $this->postJson('/iletisim', [
            'ad' => 'Ayşe',
            'soyad' => 'Yıldız',
            'eposta' => 'ayse@example.com',
            'mesaj' => 'Merhaba, bir proje için görüşmek istiyorum.',
        ])->assertOk()->assertJson(['basarili' => true]);

        $this->assertDatabaseHas('mesajlar', ['eposta' => 'ayse@example.com', 'okundu' => false]);
    }

    public function test_iletisim_formu_dogrulama_hatasi(): void
    {
        $this->postJson('/iletisim', ['ad' => '', 'eposta' => 'gecersiz'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['ad', 'eposta', 'mesaj']);
    }

    public function test_bot_tuzagi_mesaji_kaydetmez(): void
    {
        $this->postJson('/iletisim', [
            'ad' => 'Bot',
            'eposta' => 'bot@example.com',
            'mesaj' => 'spam',
            'web_sitesi' => 'http://spam.example',
        ])->assertOk();

        $this->assertSame(0, Mesaj::count());
    }

    public function test_misafir_onizleme_modunu_kullanamaz(): void
    {
        $this->get('/?onizleme=1')->assertOk()->assertDontSee('onizleme.js');
    }
}
