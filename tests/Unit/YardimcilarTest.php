<?php

namespace Tests\Unit;

use App\Models\Proje;
use PHPUnit\Framework\TestCase;

class YardimcilarTest extends TestCase
{
    public function test_satirlar_bos_satirlari_atar(): void
    {
        $this->assertSame(['Bir', 'İki', 'Üç'], tema_satirlar("Bir\r\n\n  İki  \n\nÜç\n"));
        $this->assertSame([], tema_satirlar(null));
    }

    public function test_proje_kategorileri_virgulle_ayrilir(): void
    {
        $proje = new Proje(['kategori' => 'Web Tasarım, UI/UX , ,Marka']);

        $this->assertSame(['Web Tasarım', 'UI/UX', 'Marka'], $proje->kategoriler());
    }
}
