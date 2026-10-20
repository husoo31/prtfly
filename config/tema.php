<?php

/*
|--------------------------------------------------------------------------
| Tema şeması
|--------------------------------------------------------------------------
|
| Temadaki düzenlenebilir her metin ve görsel burada tanımlanır. Tema
| düzenleyici bu şemadan formu üretir, site şablonları değerleri
| tema('bolum.alan') yardımcısıyla okur. Yeni bir alan eklemek için
| buraya tanımını yazmanız ve şablonda kullanmanız yeterlidir.
|
| Alan tipleri:
|   metin     tek satır yazı              (canlı)
|   yazi      çok satırlı yazı            (canlı)
|   vurgu     {süslü parantez} içi turuncu vurgulanan yazı (canlı)
|   gorsel    görsel yükleme / adres      (canlı)
|   baglanti  adres (URL / #bolum)         (canlı)
|   renk      renk seçici                 (canlı, CSS değişkeni)
|   sayi      sayı                        (yenileme)
|   secim     açılır liste (secenekler)   (yenileme)
|   anahtar   aç / kapat                  (yenileme)
|   ikon      sosyal medya ikonu           (yenileme)
|   liste     tekrarlanan öğeler (alanlar) (yenileme)
|
*/

$sosyalIkonlar = [
    'fa-brands fa-facebook-f' => 'Facebook',
    'fa-brands fa-x-twitter' => 'X (Twitter)',
    'fa-brands fa-instagram' => 'Instagram',
    'fa-brands fa-linkedin-in' => 'LinkedIn',
    'fa-brands fa-behance' => 'Behance',
    'fa-brands fa-dribbble' => 'Dribbble',
    'fa-brands fa-github' => 'GitHub',
    'fa-brands fa-youtube' => 'YouTube',
    'fa-brands fa-tiktok' => 'TikTok',
    'fa-brands fa-pinterest-p' => 'Pinterest',
    'fa-brands fa-whatsapp' => 'WhatsApp',
    'fa-brands fa-telegram' => 'Telegram',
    'fa-solid fa-globe' => 'Web sitesi',
    'fa-solid fa-envelope' => 'E-posta',
];

$hizmetMaddeleri = "Tasarım sistemleri ve kılavuzlar\nPrototipleme ve kullanıcı testleri\nÜrün stratejisi ve danışmanlık";

return [

    'bolumler' => [

        'genel' => [
            'etiket' => 'Genel & Marka',
            'ikon' => 'bi-palette',
            'alanlar' => [
                'site_adi' => ['tip' => 'metin', 'etiket' => 'Site adı', 'varsayilan' => 'Portfolyo'],
                'logo_bas' => ['tip' => 'metin', 'etiket' => 'Logo — ilk kısım', 'varsayilan' => 'P'],
                'logo_vurgu' => ['tip' => 'metin', 'etiket' => 'Logo — vurgulu harf', 'varsayilan' => 'o'],
                'logo_son' => ['tip' => 'metin', 'etiket' => 'Logo — son kısım', 'varsayilan' => 'rtfolyo'],
                'logo_gorsel' => ['tip' => 'gorsel', 'etiket' => 'Logo görseli (doluysa yazı logosunun yerine geçer)', 'varsayilan' => ''],
                'favicon' => ['tip' => 'gorsel', 'etiket' => 'Favicon', 'varsayilan' => 'assets/img/favicon.svg'],
                'renk_vurgu' => ['tip' => 'renk', 'etiket' => 'Vurgu rengi', 'varsayilan' => '#ff6b00', 'degisken' => '--renk-vurgu'],
                'renk_koyu' => ['tip' => 'renk', 'etiket' => 'Koyu renk', 'varsayilan' => '#0f0e0e', 'degisken' => '--renk-koyu'],
                'renk_gri' => ['tip' => 'renk', 'etiket' => 'Gövde yazı rengi', 'varsayilan' => '#545b63', 'degisken' => '--renk-gri'],
                'mod' => ['tip' => 'secim', 'etiket' => 'Görünüm', 'varsayilan' => 'acik', 'secenekler' => ['acik' => 'Açık', 'koyu' => 'Koyu']],
                'onyukleyici' => ['tip' => 'anahtar', 'etiket' => 'Açılış animasyonu (preloader)', 'varsayilan' => true],
                'animasyon' => ['tip' => 'anahtar', 'etiket' => 'Kaydırma animasyonları', 'varsayilan' => true],
            ],
        ],

        'seo' => [
            'etiket' => 'SEO & Paylaşım',
            'ikon' => 'bi-search',
            'alanlar' => [
                'baslik' => ['tip' => 'metin', 'etiket' => 'Sayfa başlığı', 'varsayilan' => 'Dynvel — UI/UX Tasarımcı'],
                'aciklama' => ['tip' => 'yazi', 'etiket' => 'Meta açıklaması', 'varsayilan' => 'Kullanıcı odaklı arayüzler, marka kimlikleri ve dijital ürünler tasarlayan bir UI/UX tasarımcının kişisel portfolyosu.'],
                'anahtar_kelimeler' => ['tip' => 'metin', 'etiket' => 'Anahtar kelimeler', 'varsayilan' => 'portfolyo, ui ux tasarım, web tasarım, marka kimliği'],
                'og_gorsel' => ['tip' => 'gorsel', 'etiket' => 'Paylaşım görseli (1200×630)', 'varsayilan' => ''],
                'ust_kod' => ['tip' => 'yazi', 'etiket' => '<head> içine eklenecek kod (Analytics vb.)', 'varsayilan' => '', 'ham' => true],
            ],
        ],

        'menu' => [
            'etiket' => 'Üst Menü',
            'ikon' => 'bi-list',
            'alanlar' => [
                'ogeler' => [
                    'tip' => 'liste',
                    'etiket' => 'Menü bağlantıları',
                    'oge_etiketi' => 'Bağlantı',
                    'baslik_alani' => 'etiket',
                    'alanlar' => [
                        'etiket' => ['tip' => 'metin', 'etiket' => 'Etiket', 'varsayilan' => 'Yeni bağlantı'],
                        'baglanti' => ['tip' => 'baglanti', 'etiket' => 'Adres', 'varsayilan' => '#home'],
                    ],
                    'varsayilan' => [
                        ['etiket' => 'Ana Sayfa', 'baglanti' => '#home'],
                        ['etiket' => 'Hakkımda', 'baglanti' => '#about'],
                        ['etiket' => 'Portfolyo', 'baglanti' => '#portfolio'],
                        ['etiket' => 'Hizmetler', 'baglanti' => '#services'],
                        ['etiket' => 'Referanslar', 'baglanti' => '#testimonial'],
                        ['etiket' => 'Blog', 'baglanti' => '#blogs'],
                        ['etiket' => 'İletişim', 'baglanti' => '#contact'],
                    ],
                ],
                'buton_metin' => ['tip' => 'metin', 'etiket' => 'Sağ buton yazısı', 'varsayilan' => 'Hadi Konuşalım'],
                'buton_baglanti' => ['tip' => 'baglanti', 'etiket' => 'Sağ buton adresi', 'varsayilan' => '#contact'],
            ],
        ],

        'yan_panel' => [
            'etiket' => 'Yan Panel',
            'ikon' => 'bi-layout-sidebar-reverse',
            'alanlar' => [
                'aciklama' => ['tip' => 'yazi', 'etiket' => 'Kısa tanıtım', 'varsayilan' => 'Fikirleri sade, kullanışlı ve akılda kalan dijital deneyimlere dönüştürüyorum. Yeni projeler için her zaman ulaşabilirsiniz.'],
                'adres_baslik' => ['tip' => 'metin', 'etiket' => 'Adres başlığı', 'varsayilan' => 'adres'],
                'adres' => ['tip' => 'metin', 'etiket' => 'Adres', 'varsayilan' => 'Çankaya, Ankara'],
                'eposta_baslik' => ['tip' => 'metin', 'etiket' => 'E-posta başlığı', 'varsayilan' => 'e-posta'],
                'eposta' => ['tip' => 'metin', 'etiket' => 'E-posta', 'varsayilan' => 'dynvel@ornek.com'],
                'telefon_baslik' => ['tip' => 'metin', 'etiket' => 'Telefon başlığı', 'varsayilan' => 'hemen arayın'],
                'telefon' => ['tip' => 'metin', 'etiket' => 'Telefon', 'varsayilan' => '+90 532 000 00 00'],
                'buton_metin' => ['tip' => 'metin', 'etiket' => 'Buton yazısı', 'varsayilan' => 'Hadi Konuşalım'],
            ],
        ],

        'sosyal' => [
            'etiket' => 'Sosyal Medya',
            'ikon' => 'bi-share',
            'alanlar' => [
                'ogeler' => [
                    'tip' => 'liste',
                    'etiket' => 'Hesaplar',
                    'oge_etiketi' => 'Hesap',
                    'baslik_alani' => 'ikon',
                    'alanlar' => [
                        'ikon' => ['tip' => 'ikon', 'etiket' => 'Platform', 'varsayilan' => 'fa-brands fa-instagram', 'secenekler' => $sosyalIkonlar],
                        'baglanti' => ['tip' => 'baglanti', 'etiket' => 'Adres', 'varsayilan' => 'https://'],
                    ],
                    'varsayilan' => [
                        ['ikon' => 'fa-brands fa-instagram', 'baglanti' => 'https://instagram.com/dynvel'],
                        ['ikon' => 'fa-brands fa-x-twitter', 'baglanti' => 'https://x.com/dynvel'],
                        ['ikon' => 'fa-brands fa-behance', 'baglanti' => 'https://behance.net/dynvel'],
                        ['ikon' => 'fa-brands fa-dribbble', 'baglanti' => 'https://dribbble.com/dynvel'],
                    ],
                ],
            ],
        ],

        'hero' => [
            'etiket' => 'Giriş (Hero)',
            'ikon' => 'bi-person-badge',
            'alanlar' => [
                'aktif' => ['tip' => 'anahtar', 'etiket' => 'Bölümü göster', 'varsayilan' => true],
                'selam' => ['tip' => 'metin', 'etiket' => 'Selamlama', 'varsayilan' => 'Merhaba!'],
                'selam_ikon' => ['tip' => 'metin', 'etiket' => 'Selamlama simgesi (emoji)', 'varsayilan' => '👋'],
                'selam_son' => ['tip' => 'metin', 'etiket' => 'Selamlama devamı', 'varsayilan' => 'Ben'],
                'isim' => ['tip' => 'metin', 'etiket' => 'Ad soyad', 'varsayilan' => 'Dynvel'],
                'aciklama' => ['tip' => 'yazi', 'etiket' => 'Tanıtım yazısı', 'varsayilan' => 'İş birliğinin ve yaratıcılığın ilerlemenin özü olduğuna inanıyorum. Üstlendiğim her projeye taze bir bakış açısı getirmeye kendimi adadım.'],
                'unvan' => ['tip' => 'metin', 'etiket' => 'Büyük unvan yazısı', 'varsayilan' => 'UI UX TASARIMCI'],
                'profil_gorsel' => ['tip' => 'gorsel', 'etiket' => 'Profil fotoğrafı', 'varsayilan' => 'assets/img/foto/profil.svg'],
                'buton1_metin' => ['tip' => 'metin', 'etiket' => '1. buton yazısı', 'varsayilan' => 'Hadi Konuşalım'],
                'buton1_baglanti' => ['tip' => 'baglanti', 'etiket' => '1. buton adresi', 'varsayilan' => '#contact'],
                'buton2_metin' => ['tip' => 'metin', 'etiket' => '2. buton yazısı', 'varsayilan' => 'Hakkımda'],
                'buton2_baglanti' => ['tip' => 'baglanti', 'etiket' => '2. buton adresi', 'varsayilan' => '#about'],
                'donen_yazi' => ['tip' => 'metin', 'etiket' => 'Dönen daire yazısı', 'varsayilan' => 'İLETİŞİME GEÇ • İLETİŞİME GEÇ • '],
                'donen_baglanti' => ['tip' => 'baglanti', 'etiket' => 'Dönen daire adresi', 'varsayilan' => '#contact'],
            ],
        ],

        'yetenekler' => [
            'etiket' => 'Yetenekler',
            'ikon' => 'bi-bar-chart',
            'alanlar' => [
                'aktif' => ['tip' => 'anahtar', 'etiket' => 'Bölümü göster', 'varsayilan' => true],
                'ogeler' => [
                    'tip' => 'liste',
                    'etiket' => 'Yetenekler',
                    'oge_etiketi' => 'Yetenek',
                    'baslik_alani' => 'ad',
                    'alanlar' => [
                        'gorsel' => ['tip' => 'gorsel', 'etiket' => 'Simge', 'varsayilan' => 'assets/img/yetenek/kalem.svg'],
                        'yuzde' => ['tip' => 'metin', 'etiket' => 'Seviye', 'varsayilan' => '%90'],
                        'ad' => ['tip' => 'metin', 'etiket' => 'Ad', 'varsayilan' => 'Yeni yetenek'],
                    ],
                    'varsayilan' => [
                        ['gorsel' => 'assets/img/yetenek/kalem.svg', 'yuzde' => '%90', 'ad' => 'UI Tasarım'],
                        ['gorsel' => 'assets/img/yetenek/katman.svg', 'yuzde' => '%80', 'ad' => 'Prototip'],
                        ['gorsel' => 'assets/img/yetenek/resim.svg', 'yuzde' => '%75', 'ad' => 'Görsel'],
                        ['gorsel' => 'assets/img/yetenek/palet.svg', 'yuzde' => '%90', 'ad' => 'Marka'],
                        ['gorsel' => 'assets/img/yetenek/kod.svg', 'yuzde' => '%85', 'ad' => 'HTML / CSS'],
                        ['gorsel' => 'assets/img/yetenek/mobil.svg', 'yuzde' => '%95', 'ad' => 'Mobil'],
                        ['gorsel' => 'assets/img/yetenek/izgara.svg', 'yuzde' => '%75', 'ad' => 'Bootstrap'],
                        ['gorsel' => 'assets/img/yetenek/hareket.svg', 'yuzde' => '%85', 'ad' => 'Animasyon'],
                    ],
                ],
            ],
        ],

        'hakkimda' => [
            'etiket' => 'Hakkımda',
            'ikon' => 'bi-person',
            'alanlar' => [
                'aktif' => ['tip' => 'anahtar', 'etiket' => 'Bölümü göster', 'varsayilan' => true],
                'baslik' => ['tip' => 'metin', 'etiket' => 'Bölüm başlığı', 'varsayilan' => 'Hakkımda'],
                'metin' => ['tip' => 'vurgu', 'etiket' => 'Tanıtım (süslü parantez içi vurgulanır)', 'varsayilan' => '15 yılı aşkın {dijital ürün} deneyimine sahip kıdemli bir UX/UI tasarımcıyım. Kariyerim boyunca büyük şirketlerle ve zorlu projelerle çalıştım.'],
                'deneyim_sayi' => ['tip' => 'metin', 'etiket' => 'Deneyim sayısı', 'varsayilan' => '15+'],
                'deneyim_metin' => ['tip' => 'yazi', 'etiket' => 'Deneyim yazısı', 'varsayilan' => "Yıllık\nDeneyim"],
                'buton_metin' => ['tip' => 'metin', 'etiket' => 'Yuvarlak buton yazısı', 'varsayilan' => 'Hakkımda'],
                'buton_baglanti' => ['tip' => 'baglanti', 'etiket' => 'Yuvarlak buton adresi', 'varsayilan' => '#contact'],
                'gorsel' => ['tip' => 'gorsel', 'etiket' => 'Görsel', 'varsayilan' => 'assets/img/foto/hakkimda.svg'],
            ],
        ],

        'projeler' => [
            'etiket' => 'Projeler Bölümü',
            'ikon' => 'bi-grid-1x2',
            'aciklama' => 'Projelerin kendisini "Projeler" menüsünden yönetirsiniz.',
            'alanlar' => [
                'aktif' => ['tip' => 'anahtar', 'etiket' => 'Bölümü göster', 'varsayilan' => true],
                'baslik' => ['tip' => 'metin', 'etiket' => 'Bölüm başlığı', 'varsayilan' => 'Son Projeler'],
                'tumu_etiketi' => ['tip' => 'metin', 'etiket' => '"Tümü" filtresi', 'varsayilan' => 'Tüm Tasarımlar'],
                'adet' => ['tip' => 'sayi', 'etiket' => 'Ana sayfada gösterilecek proje', 'varsayilan' => 5],
                'buton_metin' => ['tip' => 'metin', 'etiket' => 'Yuvarlak buton yazısı', 'varsayilan' => 'Tümünü Gör'],
                'detay_buton' => ['tip' => 'metin', 'etiket' => 'Kart üzerindeki buton', 'varsayilan' => 'İncele'],
                'musteri_etiketi' => ['tip' => 'metin', 'etiket' => 'Detay: "Müşteri" etiketi', 'varsayilan' => 'Müşteri:'],
                'kategori_etiketi' => ['tip' => 'metin', 'etiket' => 'Detay: "Kategori" etiketi', 'varsayilan' => 'Kategori:'],
                'tarih_etiketi' => ['tip' => 'metin', 'etiket' => 'Detay: "Tarih" etiketi', 'varsayilan' => 'Tarih:'],
                'sayfa_baslik' => ['tip' => 'metin', 'etiket' => 'Tüm projeler sayfası başlığı', 'varsayilan' => 'Tüm Projeler'],
            ],
        ],

        'hizmetler' => [
            'etiket' => 'Hizmetler',
            'ikon' => 'bi-briefcase',
            'alanlar' => [
                'aktif' => ['tip' => 'anahtar', 'etiket' => 'Bölümü göster', 'varsayilan' => true],
                'baslik' => ['tip' => 'metin', 'etiket' => 'Bölüm başlığı', 'varsayilan' => 'Hizmetlerim'],
                'ogeler' => [
                    'tip' => 'liste',
                    'etiket' => 'Hizmetler',
                    'oge_etiketi' => 'Hizmet',
                    'baslik_alani' => 'baslik',
                    'alanlar' => [
                        'baslik' => ['tip' => 'metin', 'etiket' => 'Başlık', 'varsayilan' => 'Yeni hizmet'],
                        'maddeler' => ['tip' => 'yazi', 'etiket' => 'Maddeler (her satır bir madde)', 'varsayilan' => $hizmetMaddeleri],
                        'buton_metin' => ['tip' => 'metin', 'etiket' => 'Buton yazısı', 'varsayilan' => 'Detay'],
                        'baglanti' => ['tip' => 'baglanti', 'etiket' => 'Buton adresi', 'varsayilan' => '#contact'],
                    ],
                    'varsayilan' => [
                        ['baslik' => 'Görsel Tasarım', 'maddeler' => $hizmetMaddeleri, 'buton_metin' => 'Detay', 'baglanti' => '#contact'],
                        ['baslik' => 'Ürün Stratejisi', 'maddeler' => $hizmetMaddeleri, 'buton_metin' => 'Detay', 'baglanti' => '#contact'],
                        ['baslik' => 'Kullanıcı Testi', 'maddeler' => $hizmetMaddeleri, 'buton_metin' => 'Detay', 'baglanti' => '#contact'],
                        ['baslik' => 'UI/UX Tasarım', 'maddeler' => $hizmetMaddeleri, 'buton_metin' => 'Detay', 'baglanti' => '#contact'],
                    ],
                ],
                'buton_metin' => ['tip' => 'metin', 'etiket' => 'Alt yuvarlak buton yazısı', 'varsayilan' => 'Tümünü Gör'],
                'buton_baglanti' => ['tip' => 'baglanti', 'etiket' => 'Alt yuvarlak buton adresi', 'varsayilan' => '#contact'],
            ],
        ],

        'referanslar' => [
            'etiket' => 'Referanslar',
            'ikon' => 'bi-chat-quote',
            'alanlar' => [
                'aktif' => ['tip' => 'anahtar', 'etiket' => 'Bölümü göster', 'varsayilan' => true],
                'baslik' => ['tip' => 'metin', 'etiket' => 'Büyük başlık', 'varsayilan' => 'Referanslar'],
                'ogeler' => [
                    'tip' => 'liste',
                    'etiket' => 'Yorumlar',
                    'oge_etiketi' => 'Yorum',
                    'baslik_alani' => 'isim',
                    'alanlar' => [
                        'gorsel' => ['tip' => 'gorsel', 'etiket' => 'Fotoğraf', 'varsayilan' => 'assets/img/foto/referans-1.jpg'],
                        'yorum' => ['tip' => 'yazi', 'etiket' => 'Yorum', 'varsayilan' => 'Harika bir iş birliğiydi.'],
                        'isim' => ['tip' => 'metin', 'etiket' => 'Ad soyad', 'varsayilan' => 'Müşteri'],
                        'unvan' => ['tip' => 'metin', 'etiket' => 'Ünvan / şirket', 'varsayilan' => ''],
                    ],
                    'varsayilan' => [
                        ['gorsel' => 'assets/img/foto/referans-1.jpg', 'yorum' => 'Dynvel ile çalışmak oyunun kurallarını değiştirdi. Vizyonumuzu anlayıp tasarıma dönüştürme becerisi etkileyiciydi; sunduğu çözümler kullanıcı etkileşimimizi gözle görülür biçimde artırdı.', 'isim' => 'Elif Kaya', 'unvan' => 'Ürün Müdürü, Nova Yazılım'],
                        ['gorsel' => 'assets/img/foto/referans-2.jpg', 'yorum' => 'Süreç boyunca iletişimi çok netti, teslim tarihlerine eksiksiz uydu. Yeni arayüzümüzden sonra dönüşüm oranımız belirgin şekilde yükseldi.', 'isim' => 'Murat Demir', 'unvan' => 'Kurucu, Pera Studio'],
                        ['gorsel' => 'assets/img/foto/referans-3.jpg', 'yorum' => 'Markamızı baştan sona yeniledi. Detaylara gösterdiği özen ve yaratıcı bakış açısı sayesinde ekibimiz yeni kimliği çok sevdi.', 'isim' => 'Selin Aydın', 'unvan' => 'Pazarlama Direktörü, Kuzey'],
                    ],
                ],
            ],
        ],

        'kayan_yazi' => [
            'etiket' => 'Kayan Yazı',
            'ikon' => 'bi-arrow-left-right',
            'alanlar' => [
                'aktif' => ['tip' => 'anahtar', 'etiket' => 'Bölümü göster', 'varsayilan' => true],
                'ogeler' => [
                    'tip' => 'liste',
                    'etiket' => 'Kelimeler (dolu ve çerçeve yazı sırayla gelir)',
                    'oge_etiketi' => 'Kelime',
                    'baslik_alani' => 'metin',
                    'alanlar' => [
                        'metin' => ['tip' => 'metin', 'etiket' => 'Metin', 'varsayilan' => 'Yeni kelime'],
                    ],
                    'varsayilan' => [
                        ['metin' => 'Kullanıcı Arayüzü'],
                        ['metin' => 'Kullanıcı Deneyimi'],
                        ['metin' => 'Marka Kimliği'],
                        ['metin' => 'Dijital Pazarlama'],
                    ],
                ],
            ],
        ],

        'fiyatlar' => [
            'etiket' => 'Fiyatlandırma',
            'ikon' => 'bi-tags',
            'alanlar' => [
                'aktif' => ['tip' => 'anahtar', 'etiket' => 'Bölümü göster', 'varsayilan' => true],
                'baslik' => ['tip' => 'metin', 'etiket' => 'Bölüm başlığı', 'varsayilan' => 'Fiyatlar'],
                'ozellik_baslik' => ['tip' => 'metin', 'etiket' => '"İçerik" etiketi', 'varsayilan' => 'İçerik'],
                'ogeler' => [
                    'tip' => 'liste',
                    'etiket' => 'Paketler',
                    'oge_etiketi' => 'Paket',
                    'baslik_alani' => 'paket',
                    'alanlar' => [
                        'paket' => ['tip' => 'metin', 'etiket' => 'Paket adı', 'varsayilan' => 'Yeni Paket'],
                        'fiyat' => ['tip' => 'metin', 'etiket' => 'Fiyat', 'varsayilan' => '₺1.000'],
                        'birim' => ['tip' => 'metin', 'etiket' => 'Birim', 'varsayilan' => '/ Saatlik'],
                        'alt_metin' => ['tip' => 'metin', 'etiket' => 'Alt yazı', 'varsayilan' => ''],
                        'ozellikler' => ['tip' => 'yazi', 'etiket' => 'Özellikler (her satır bir madde)', 'varsayilan' => ''],
                        'buton_metin' => ['tip' => 'metin', 'etiket' => 'Buton yazısı', 'varsayilan' => 'Bu Paketi Seç'],
                        'buton_baglanti' => ['tip' => 'baglanti', 'etiket' => 'Buton adresi', 'varsayilan' => '#contact'],
                    ],
                    'varsayilan' => [
                        ['paket' => 'Temel Paket', 'fiyat' => '₺1.200', 'birim' => '/ Saatlik', 'alt_metin' => 'veya yıllık ₺9.900', 'ozellikler' => "İlk görüşme ve kapsam belirleme\nKullanıcı deneyimi (UX) analizi\nTemel arayüz (UI) tasarımı\nBir tur revizyon\nEtkileşimli prototip\n2 hafta içinde teslim", 'buton_metin' => 'Bu Paketi Seç', 'buton_baglanti' => '#contact'],
                        ['paket' => 'Standart Paket', 'fiyat' => '₺1.600', 'birim' => '/ Saatlik', 'alt_metin' => 'veya yıllık ₺12.900', 'ozellikler' => "Temel paketteki her şey\nDetaylı UX araştırması ve personalar\nEksiksiz arayüz tasarımı\nEtkileşimli prototip\nİki tur revizyon\n4 hafta içinde teslim", 'buton_metin' => 'Bu Paketi Seç', 'buton_baglanti' => '#contact'],
                        ['paket' => 'Premium Paket', 'fiyat' => '₺2.000', 'birim' => '/ Saatlik', 'alt_metin' => 'veya yıllık ₺15.900', 'ozellikler' => "Standart paketteki her şey\nKapsamlı kullanıcı testi ve analizi\nBütünlüklü tasarım sistemi\nSınırsız revizyon\nGelişmiş arayüz animasyonları\n6 hafta içinde teslim", 'buton_metin' => 'Bu Paketi Seç', 'buton_baglanti' => '#contact'],
                    ],
                ],
            ],
        ],

        'markalar' => [
            'etiket' => 'Markalar',
            'ikon' => 'bi-award',
            'alanlar' => [
                'aktif' => ['tip' => 'anahtar', 'etiket' => 'Bölümü göster', 'varsayilan' => true],
                'baslik' => ['tip' => 'metin', 'etiket' => 'Başlık', 'varsayilan' => 'Dünya genelinde 200+ şirketin güvendiği tasarımcı'],
                'ogeler' => [
                    'tip' => 'liste',
                    'etiket' => 'Logolar',
                    'oge_etiketi' => 'Logo',
                    'baslik_alani' => 'ad',
                    'alanlar' => [
                        'ad' => ['tip' => 'metin', 'etiket' => 'Marka adı', 'varsayilan' => 'Marka'],
                        'gorsel' => ['tip' => 'gorsel', 'etiket' => 'Logo', 'varsayilan' => 'assets/img/marka-1.svg'],
                        'baglanti' => ['tip' => 'baglanti', 'etiket' => 'Adres', 'varsayilan' => '#'],
                    ],
                    'varsayilan' => [
                        ['ad' => 'Nova', 'gorsel' => 'assets/img/marka-1.svg', 'baglanti' => '#'],
                        ['ad' => 'Pera', 'gorsel' => 'assets/img/marka-2.svg', 'baglanti' => '#'],
                        ['ad' => 'Kuzey', 'gorsel' => 'assets/img/marka-3.svg', 'baglanti' => '#'],
                        ['ad' => 'Atlas', 'gorsel' => 'assets/img/marka-4.svg', 'baglanti' => '#'],
                        ['ad' => 'Lumen', 'gorsel' => 'assets/img/marka-5.svg', 'baglanti' => '#'],
                    ],
                ],
            ],
        ],

        'blog' => [
            'etiket' => 'Blog Bölümü',
            'ikon' => 'bi-journal-text',
            'aciklama' => 'Yazıların kendisini "Blog" menüsünden yönetirsiniz.',
            'alanlar' => [
                'aktif' => ['tip' => 'anahtar', 'etiket' => 'Bölümü göster', 'varsayilan' => true],
                'baslik' => ['tip' => 'metin', 'etiket' => 'Bölüm başlığı', 'varsayilan' => 'Son Yazılar'],
                'adet' => ['tip' => 'sayi', 'etiket' => 'Ana sayfada gösterilecek yazı', 'varsayilan' => 4],
                'oku_buton' => ['tip' => 'metin', 'etiket' => 'Yazı üzerindeki buton', 'varsayilan' => 'Oku'],
                'buton_metin' => ['tip' => 'metin', 'etiket' => 'Yuvarlak buton yazısı', 'varsayilan' => 'Tümünü Gör'],
                'sayfa_baslik' => ['tip' => 'metin', 'etiket' => 'Blog sayfası başlığı', 'varsayilan' => 'Blog'],
                'ara_yer_tutucu' => ['tip' => 'metin', 'etiket' => 'Arama kutusu yazısı', 'varsayilan' => 'Yazılarda ara...'],
                'son_yazilar' => ['tip' => 'metin', 'etiket' => 'Kenar çubuğu: son yazılar', 'varsayilan' => 'Son Yazılar'],
                'kategoriler' => ['tip' => 'metin', 'etiket' => 'Kenar çubuğu: kategoriler', 'varsayilan' => 'Kategoriler'],
                'devami' => ['tip' => 'metin', 'etiket' => 'Devamını oku bağlantısı', 'varsayilan' => 'Devamını Oku'],
                'bos_metin' => ['tip' => 'metin', 'etiket' => 'Yazı yokken gösterilecek metin', 'varsayilan' => 'Henüz yazı yok.'],
            ],
        ],

        'iletisim' => [
            'etiket' => 'İletişim',
            'ikon' => 'bi-envelope',
            'alanlar' => [
                'aktif' => ['tip' => 'anahtar', 'etiket' => 'Bölümü göster', 'varsayilan' => true],
                'kayan_eposta' => ['tip' => 'metin', 'etiket' => 'Kayan şerit: e-posta', 'varsayilan' => 'dynvel@ornek.com'],
                'kayan_telefon' => ['tip' => 'metin', 'etiket' => 'Kayan şerit: telefon', 'varsayilan' => '+90 532 000 00 00'],
                'baslik' => ['tip' => 'metin', 'etiket' => 'Büyük başlık', 'varsayilan' => 'Hadi Konuşalım!'],
                'aciklama' => ['tip' => 'yazi', 'etiket' => 'Açıklama', 'varsayilan' => 'Yeni projeleri, yaratıcı fikirleri ya da vizyonunuzun bir parçası olma fırsatlarını konuşmaya her zaman açığım. Aşağıdaki formdan bana ulaşabilirsiniz.'],
                'ad_etiketi' => ['tip' => 'metin', 'etiket' => 'Form: ad', 'varsayilan' => 'Adınız'],
                'soyad_etiketi' => ['tip' => 'metin', 'etiket' => 'Form: soyad', 'varsayilan' => 'Soyadınız'],
                'eposta_etiketi' => ['tip' => 'metin', 'etiket' => 'Form: e-posta', 'varsayilan' => 'E-posta'],
                'telefon_etiketi' => ['tip' => 'metin', 'etiket' => 'Form: telefon', 'varsayilan' => 'Telefon'],
                'mesaj_etiketi' => ['tip' => 'metin', 'etiket' => 'Form: mesaj', 'varsayilan' => 'Mesajınız'],
                'mesaj_yer_tutucu' => ['tip' => 'metin', 'etiket' => 'Form: mesaj yer tutucu', 'varsayilan' => 'Mesajınızı yazın...'],
                'buton_metin' => ['tip' => 'metin', 'etiket' => 'Form: gönder butonu', 'varsayilan' => 'Gönder'],
                'basari_mesaji' => ['tip' => 'metin', 'etiket' => 'Başarılı gönderim mesajı', 'varsayilan' => 'Teşekkürler! Mesajınız ulaştı, en kısa sürede dönüş yapacağım.'],
                'hata_mesaji' => ['tip' => 'metin', 'etiket' => 'Hata mesajı', 'varsayilan' => 'Mesaj gönderilemedi. Lütfen alanları kontrol edip tekrar deneyin.'],
            ],
        ],

        'alt' => [
            'etiket' => 'Alt Bilgi',
            'ikon' => 'bi-layout-text-window-reverse',
            'alanlar' => [
                'telif' => ['tip' => 'metin', 'etiket' => 'Telif yazısı ({yil} otomatik yıl olur)', 'varsayilan' => '© {yil} Portfolyo — Tüm hakları saklıdır.'],
                'telif_baglanti' => ['tip' => 'baglanti', 'etiket' => 'Telif bağlantısı', 'varsayilan' => '/'],
            ],
        ],

        'hata' => [
            'etiket' => '404 Sayfası',
            'ikon' => 'bi-exclamation-octagon',
            'alanlar' => [
                'baslik' => ['tip' => 'metin', 'etiket' => 'Başlık', 'varsayilan' => 'Sayfa bulunamadı'],
                'aciklama' => ['tip' => 'yazi', 'etiket' => 'Açıklama', 'varsayilan' => 'Aradığınız sayfa taşınmış ya da hiç var olmamış olabilir.'],
                'buton_metin' => ['tip' => 'metin', 'etiket' => 'Buton yazısı', 'varsayilan' => 'Ana sayfaya dön'],
            ],
        ],
    ],

    // Canlı önizlemede sayfa yenilemeden anında güncellenen alan tipleri.
    'canli_tipler' => ['metin', 'yazi', 'vurgu', 'gorsel', 'baglanti', 'renk'],

];
