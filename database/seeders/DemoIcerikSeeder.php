<?php

namespace Database\Seeders;

use App\Models\BlogYazisi;
use App\Models\Proje;
use Illuminate\Database\Seeder;

/** İlk kurulumda sitenin boş görünmemesi için örnek projeler ve yazılar. */
class DemoIcerikSeeder extends Seeder
{
    public function run(): void
    {
        if (Proje::query()->doesntExist()) {
            $projeler = [
                ['Kurumsal Web Sitesi', 'Web Tasarım', 'buyuk', 'Nova Yazılım'],
                ['Mobil Bankacılık Arayüzü', 'UI/UX', 'orta', 'Pera Finans'],
                ['Fitness Uygulaması', 'Uygulama Tasarımı, Grafik Tasarım', 'normal', 'Kuzey Spor'],
                ['Marka Kimliği', 'Grafik Tasarım, UI/UX', 'normal', 'Atlas Kahve'],
                ['E-ticaret Paneli', 'Web Tasarım, Uygulama Tasarımı', 'normal', 'Lumen Market'],
            ];

            foreach ($projeler as $i => [$baslik, $kategori, $boyut, $musteri]) {
                Proje::create([
                    'baslik' => $baslik,
                    'kategori' => $kategori,
                    'boyut' => $boyut,
                    'musteri' => $musteri,
                    'tarih' => now()->subMonths($i * 2)->translatedFormat('F Y'),
                    'kapak' => 'assets/img/foto/proje-'.($i + 1).'.jpg',
                    'gorsel_1' => 'assets/img/foto/proje-detay-'.($i + 1).'.jpg',
                    'gorsel_2' => 'assets/img/foto/proje-detay-'.(($i + 1) % 5 + 1).'.jpg',
                    'ozet' => 'Kullanıcı ihtiyaçlarından yola çıkarak sade ve etkili bir deneyim tasarladık.',
                    'icerik' => "Proje, kapsamlı bir keşif süreciyle başladı. Kullanıcı görüşmeleri ve rakip analizleriyle ihtiyaçları netleştirdik.\n\nArdından düşük ve yüksek çözünürlüklü prototiplerle tasarımı adım adım olgunlaştırdık; her aşamada gerçek kullanıcılarla test ettik.",
                    'maddeler' => "Kapsamlı kullanıcı araştırması\nBilgi mimarisi ve akış tasarımı\nEtkileşimli prototip\nTasarım sistemi ve teslim dokümanları",
                    'sira' => $i,
                    'aktif' => true,
                ]);
            }
        }

        if (BlogYazisi::query()->doesntExist()) {
            $yazilar = [
                ['Girişimlerde Kullanıcı Odaklı Tasarımın Önemi', 'Tasarım'],
                ['Etkili Kullanıcı Araştırması Nasıl Yapılır?', 'Araştırma'],
                ['Ürün Geliştirmede Prototiplemenin Rolü', 'Ürün'],
                ['Arayüz Tasarımında Görsel Hikâye Anlatımı', 'Tasarım'],
            ];

            foreach ($yazilar as $i => [$baslik, $kategori]) {
                BlogYazisi::create([
                    'baslik' => $baslik,
                    'kategori' => $kategori,
                    'kapak' => 'assets/img/foto/blog-'.($i + 1).'.jpg',
                    'ozet' => 'Daha iyi dijital ürünler tasarlamak için pratik yöntemler, örnekler ve deneyimden süzülmüş öneriler.',
                    'icerik' => "İyi bir tasarım, kullanıcının sorununu anlamakla başlar. Bu yazıda sahada sık karşılaştığım durumları ve işe yarayan yaklaşımları paylaşıyorum.\n\n## Neden önemli?\n\nKullanıcıyı merkeze almayan ürünler, ne kadar güzel görünürse görünsün benimsenmekte zorlanır. Erken geri bildirim, maliyetli hataları en baştan önler.\n\n## Nereden başlamalı?\n\n- Küçük ve odaklı araştırmalarla başlayın\n- Varsayımlarınızı yazın ve test edin\n- Prototipi olabildiğince erken kullanıcıya gösterin\n\n> Tasarım yalnızca nasıl göründüğü değil, nasıl çalıştığıdır.\n\nSonuç olarak; düzenli test, açık iletişim ve sade çözümler her ölçekte ekip için en güvenilir yol haritasıdır.",
                    'yazar' => 'Dynvel',
                    'etiketler' => 'ux, tasarım, ürün',
                    'yayin_tarihi' => now()->subDays($i * 9 + 1),
                    'aktif' => true,
                ]);
            }
        }
    }
}
