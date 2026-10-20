<?php

namespace Tests\Feature;

use App\Support\Kurulum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KurulumTest extends TestCase
{
    use RefreshDatabase;

    public function test_kurulmamis_site_sihirbaza_yonlenir(): void
    {
        if (is_file(Kurulum::isaretDosyasi())) {
            $this->markTestSkipped('Bu ortamda kurulum işareti mevcut.');
        }

        config(['portfolyo.kuruldu' => false]);

        $this->get('/')->assertRedirect('/kurulum');
        $this->get('/kurulum')->assertOk()->assertSee('Kurulumu tamamla');
    }

    public function test_kurulu_sitede_sihirbaz_kapali(): void
    {
        $this->get('/kurulum')->assertRedirect('/');
    }
}
