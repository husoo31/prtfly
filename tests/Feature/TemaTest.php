<?php

namespace Tests\Feature;

use App\Models\Ayar;
use App\Models\User;
use App\Support\Tema;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TemaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Ayar::bellegiTemizle();
    }

    protected function degerlerle(callable $degistir): array
    {
        $degerler = app(Tema::class)->varsayilanlar();
        $degistir($degerler);

        return $degerler;
    }

    public function test_varsayilanlar_semadaki_tum_bolumleri_icerir(): void
    {
        $degerler = app(Tema::class)->varsayilanlar();

        foreach (array_keys(config('tema.bolumler')) as $bolum) {
            $this->assertArrayHasKey($bolum, $degerler);
        }

        $this->assertCount(4, $degerler['hizmetler']['ogeler']);
    }

    public function test_taslak_yalnizca_onizlemede_gorunur_yayinlaninca_herkese(): void
    {
        $this->actingAs(User::factory()->create());

        $degerler = $this->degerlerle(fn (&$d) => $d['hero']['isim'] = 'Taslak İsim');

        $this->postJson('/yonetim/tema/taslak', ['degerler' => $degerler])->assertOk();

        $this->get('/?onizleme=1')->assertOk()->assertSee('Taslak İsim')->assertSee('data-tema="hero.isim"', false);
        $this->get('/')->assertOk()->assertDontSee('Taslak İsim');

        $this->postJson('/yonetim/tema/yayinla', ['degerler' => $degerler])->assertOk();
        Ayar::bellegiTemizle();

        auth()->logout();
        $this->get('/')->assertSee('Taslak İsim');
    }

    public function test_girdi_semaya_gore_temizlenir(): void
    {
        $tema = app(Tema::class);

        $temiz = $tema->temizle([
            'bilinmeyen' => ['x' => 1],
            'genel' => [
                'renk_vurgu' => 'red;}</style><script>',
                'mod' => 'mor',
                'onyukleyici' => '0',
                'zararli' => 'x',
            ],
            'hero' => [
                'buton1_baglanti' => 'javascript:alert(1)',
                'profil_gorsel' => 'uploads/2026/10/foto.png',
            ],
            'hizmetler' => [
                'ogeler' => [['baslik' => 'Tek', 'fazla' => 'x'], 'bozuk'],
            ],
        ]);

        $this->assertArrayNotHasKey('bilinmeyen', $temiz);
        $this->assertArrayNotHasKey('zararli', $temiz['genel']);
        $this->assertSame('#ff6b00', $temiz['genel']['renk_vurgu']);
        $this->assertSame('acik', $temiz['genel']['mod']);
        $this->assertFalse($temiz['genel']['onyukleyici']);
        $this->assertSame('', $temiz['hero']['buton1_baglanti']);
        $this->assertSame('uploads/2026/10/foto.png', $temiz['hero']['profil_gorsel']);
        $this->assertCount(1, $temiz['hizmetler']['ogeler']);
        $this->assertSame(['baslik', 'maddeler', 'buton_metin', 'baglanti'], array_keys($temiz['hizmetler']['ogeler'][0]));
    }

    public function test_vurgu_metni_kacislanir(): void
    {
        $html = (string) tema_vurgu('<b>x</b> {vurgulu}');

        $this->assertStringContainsString('&lt;b&gt;', $html);
        $this->assertStringContainsString('<span class="vurgu">vurgulu</span>', $html);
    }

    public function test_bolum_gizlenebilir(): void
    {
        $this->actingAs(User::factory()->create());

        $this->postJson('/yonetim/tema/yayinla', [
            'degerler' => $this->degerlerle(fn (&$d) => $d['fiyatlar']['aktif'] = false),
        ])->assertOk();
        Ayar::bellegiTemizle();

        $this->get('/')->assertOk()->assertDontSee('id="pricing"', false);
    }

    public function test_taslak_sil_ve_sifirla(): void
    {
        $this->actingAs(User::factory()->create());

        $this->postJson('/yonetim/tema/taslak', [
            'degerler' => $this->degerlerle(fn (&$d) => $d['hero']['isim'] = 'Geçici'),
        ])->assertOk();

        $this->postJson('/yonetim/tema/taslak-sil')->assertOk()->assertJsonPath('degerler.hero.isim', 'Dynvel');
        $this->postJson('/yonetim/tema/sifirla')->assertOk()->assertJsonPath('degerler.genel.renk_vurgu', '#ff6b00');
    }

    public function test_disa_aktar_json_dondurur(): void
    {
        $this->actingAs(User::factory()->create());

        $yanit = $this->get('/yonetim/tema/disa-aktar')->assertOk();
        $veri = json_decode($yanit->streamedContent(), true);

        $this->assertSame('Dynvel', $veri['hero']['isim']);
    }

    public function test_misafir_tema_kaydedemez(): void
    {
        $this->postJson('/yonetim/tema/yayinla', ['degerler' => []])->assertUnauthorized();
    }
}
