/*!
 * Laravel Portfolyo — canlı önizlemeli tema düzenleyici
 * webdanismani.com tarafından ücretsiz dağıtılır. Satışı yasaktır.
 *
 * Akış: alan değişir → durum güncellenir → metin/görsel/renk alanları
 * iframe'e postMessage ile anında yansıtılır → değişiklik kısa bir
 * gecikmeyle oturuma taslak olarak kaydedilir → yapısal değişikliklerde
 * (liste ekle/sil, aç/kapat, seçim) önizleme kaydırma konumu korunarak
 * yenilenir. "Yayınla" taslağı veritabanına yazar.
 */
(function () {
    'use strict';

    var A = window.TemaDuzenleyici;
    var Y = window.Yonetim;
    var kok = document.getElementById('tema-duzenleyici');
    if (!A || !kok) { return; }

    var bir = function (s, k) { return (k || document).querySelector(s); };
    var hepsi = function (s, k) { return Array.prototype.slice.call((k || document).querySelectorAll(s)); };

    var durum = kopya(A.degerler);
    var panel = bir('[data-panel]', kok);
    var iframe = bir('[data-iframe]', kok);
    var cerceve = bir('[data-cerceve]', kok);
    var yukleniyor = bir('[data-yukleniyor]', kok);
    var durumYazi = bir('[data-durum]', kok);
    var koken = location.origin;

    var acikBolum = null;
    var acikListeOgeleri = {};
    var kaydirmalar = {};
    var gecerliSayfa = '/';
    var zamanlayici = null;
    var kaydediliyor = false;
    var tekrarKaydet = false;
    var yenileGerekli = false;
    var kirli = !!A.taslakVar;

    var BAGLANTI_ONERILERI = ['#home', '#about', '#portfolio', '#services', '#testimonial', '#pricing', '#blogs', '#contact', '/blog', '/projeler'];

    /* =====================================================================
       Yardımcılar
       ===================================================================== */

    function kopya(nesne) { return JSON.parse(JSON.stringify(nesne)); }

    function ogeOlustur(etiket, ozellikler, cocuklar) {
        var el = document.createElement(etiket);
        Object.keys(ozellikler || {}).forEach(function (k) {
            var v = ozellikler[k];
            if (v === null || v === undefined || v === false) { return; }
            if (k === 'sinif') { el.className = v; }
            else if (k === 'metin') { el.textContent = v; }
            else if (k === 'html') { el.innerHTML = v; }
            else if (k.indexOf('on') === 0) { el.addEventListener(k.slice(2), v); }
            else { el.setAttribute(k, v === true ? '' : v); }
        });
        (cocuklar || []).forEach(function (c) {
            if (c === null || c === undefined) { return; }
            el.appendChild(typeof c === 'string' ? document.createTextNode(c) : c);
        });
        return el;
    }

    function parcalar(yol) {
        return yol.split('.').map(function (p) { return /^\d+$/.test(p) ? parseInt(p, 10) : p; });
    }

    function degerAl(yol) {
        return parcalar(yol).reduce(function (n, p) { return n == null ? undefined : n[p]; }, durum);
    }

    function degerYaz(yol, deger) {
        var p = parcalar(yol);
        var hedef = durum;
        for (var i = 0; i < p.length - 1; i++) {
            if (hedef[p[i]] == null) { hedef[p[i]] = typeof p[i + 1] === 'number' ? [] : {}; }
            hedef = hedef[p[i]];
        }
        hedef[p[p.length - 1]] = deger;
    }

    /** "hizmetler.ogeler.2.baslik" → alt alan tanımı */
    function tanimBul(yol) {
        var p = yol.split('.');
        var bolum = A.sema[p[0]];
        if (!bolum) { return null; }
        var alan = bolum.alanlar[p[1]];
        if (alan && alan.tip === 'liste' && p.length >= 4) { return alan.alanlar[p[3]] || null; }
        return alan || null;
    }

    function bildir(metin, tur) {
        var t = bir('[data-bildirim]');
        if (!t || !window.bootstrap) { return; }
        t.className = 'toast align-items-center border-0 text-bg-' + (tur || 'dark');
        bir('[data-bildirim-metin]', t).textContent = metin;
        bootstrap.Toast.getOrCreateInstance(t, { delay: 3500 }).show();
    }

    function durumGoster(tur, metin) {
        var ikonlar = {
            kaydediliyor: '<span class="spinner-border spinner-border-sm text-light"></span>',
            taslak: '<i class="bi bi-circle-fill text-warning"></i>',
            yayinda: '<i class="bi bi-check-circle-fill text-success"></i>',
            hata: '<i class="bi bi-exclamation-triangle-fill text-danger"></i>'
        };
        durumYazi.innerHTML = ikonlar[tur] + ' ';
        durumYazi.appendChild(document.createTextNode(metin));
    }

    function istek(adres, veri) {
        return fetch(adres, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': Y.csrf,
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify(veri || {})
        }).then(function (y) {
            if (y.status === 419) { throw new Error('Oturum süresi doldu, sayfayı yenileyin.'); }
            if (y.status === 401) { throw new Error('Oturum kapandı, tekrar giriş yapın.'); }
            return y.json().then(function (j) {
                if (!y.ok) { throw new Error(j.message || 'İşlem başarısız.'); }
                return j;
            });
        });
    }

    /* =====================================================================
       Önizleme iletişimi
       ===================================================================== */

    function iframeGonder(veri) {
        if (iframe.contentWindow) { iframe.contentWindow.postMessage(veri, koken); }
    }

    function onizlemeYenile() {
        yukleniyor.classList.add('gorunur');
        try {
            iframe.contentWindow.location.reload();
        } catch (e) {
            iframe.src = iframe.src;
        }
    }

    iframe.addEventListener('load', function () {
        yukleniyor.classList.remove('gorunur');
    });

    window.addEventListener('message', function (e) {
        if (e.origin !== koken || e.source !== iframe.contentWindow || !e.data) { return; }
        var v = e.data;

        if (v.tip === 'hazir') {
            gecerliSayfa = v.sayfa;
            yukleniyor.classList.remove('gorunur');
            if (kaydirmalar[gecerliSayfa]) { iframeGonder({ tip: 'kaydir', y: kaydirmalar[gecerliSayfa] }); }
        } else if (v.tip === 'kaydirma') {
            kaydirmalar[v.sayfa] = v.y;
        } else if (v.tip === 'sec') {
            alanaGit(v.yol);
        } else if (v.tip === 'eksik') {
            // Alan sayfada henüz çizilmemiş (ör. boş butona yazı girildi): yenile.
            if (v.yol.indexOf('seo.') !== 0) {
                yenileGerekli = true;
                taslakPlanla();
            }
        }
    });

    /* =====================================================================
       Değişiklik akışı
       ===================================================================== */

    function degistir(yol, deger, tanim) {
        degerYaz(yol, deger);
        kirli = true;

        if (A.canliTipler.indexOf(tanim.tip) !== -1) {
            if (tanim.tip === 'renk') {
                iframeGonder({ tip: 'renk', degisken: tanim.degisken, deger: deger });
            } else {
                iframeGonder({ tip: 'alan', yol: yol, deger: deger });
            }
        } else {
            yenileGerekli = true;
        }

        taslakPlanla();
    }

    function yapisalDegisiklik() {
        kirli = true;
        yenileGerekli = true;
        taslakPlanla(150);
    }

    function taslakPlanla(gecikme) {
        clearTimeout(zamanlayici);
        durumGoster('taslak', 'Kaydedilmemiş değişiklik');
        zamanlayici = setTimeout(taslakKaydet, gecikme == null ? 600 : gecikme);
    }

    function taslakKaydet() {
        zamanlayici = null;
        if (kaydediliyor) { tekrarKaydet = true; return Promise.resolve(); }

        kaydediliyor = true;
        var yenile = yenileGerekli;
        yenileGerekli = false;
        durumGoster('kaydediliyor', 'Taslak kaydediliyor…');

        return istek(A.adresler.taslak, { degerler: durum })
            .then(function () {
                durumGoster('taslak', 'Taslak kaydedildi — yayınlanmadı');
                if (yenile) { onizlemeYenile(); }
            })
            .catch(function (h) {
                yenileGerekli = yenileGerekli || yenile;
                durumGoster('hata', h.message);
                bildir(h.message, 'danger');
            })
            .finally(function () {
                kaydediliyor = false;
                if (tekrarKaydet) { tekrarKaydet = false; taslakKaydet(); }
            });
    }

    function bekleyeniKaydet() {
        if (zamanlayici) { clearTimeout(zamanlayici); return taslakKaydet(); }
        return Promise.resolve();
    }

    /* =====================================================================
       Panel: bölüm listesi
       ===================================================================== */

    function anaListe() {
        acikBolum = null;
        panel.innerHTML = '';
        panel.appendChild(ogeOlustur('div', { sinif: 'td-panel-baslik' }, [
            ogeOlustur('div', {}, [
                ogeOlustur('small', { metin: 'Özelleştir' }),
                ogeOlustur('h2', { metin: 'Tema bölümleri' })
            ])
        ]));

        var ara = ogeOlustur('input', {
            type: 'search', sinif: 'form-control form-control-sm td-ara', placeholder: 'Alan ara… (ör. buton, logo)',
            oninput: function () { aramaYap(ara.value); }
        });
        panel.appendChild(ogeOlustur('div', { sinif: 'td-ara-sar' }, [ogeOlustur('i', { sinif: 'bi bi-search' }), ara]));

        var liste = ogeOlustur('div', { sinif: 'td-bolumler', 'data-bolum-listesi': '' });
        Object.keys(A.sema).forEach(function (anahtar) {
            var b = A.sema[anahtar];
            var gizli = b.alanlar.aktif && durum[anahtar] && durum[anahtar].aktif === false;
            liste.appendChild(ogeOlustur('button', {
                type: 'button', sinif: 'td-bolum-btn' + (gizli ? ' kapali' : ''),
                onclick: function () { bolumAc(anahtar); }
            }, [
                ogeOlustur('i', { sinif: 'bi ' + b.ikon }),
                ogeOlustur('span', { metin: b.etiket }),
                gizli ? ogeOlustur('span', { sinif: 'badge text-bg-secondary ms-auto', metin: 'Gizli' }) : null,
                ogeOlustur('i', { sinif: 'bi bi-chevron-right' + (gizli ? ' ms-2' : ' ms-auto') })
            ]));
        });
        panel.appendChild(liste);
        panel.appendChild(ogeOlustur('div', { sinif: 'td-sonuclar', 'data-sonuclar': '' }));

        panel.appendChild(ogeOlustur('p', {
            sinif: 'td-ipucu',
            html: '<i class="bi bi-hand-index"></i> İpucu: Önizlemede bir yazıya veya görsele tıklayarak doğrudan o alana gidebilirsiniz.'
        }));
    }

    function aramaYap(sorgu) {
        sorgu = sorgu.trim().toLocaleLowerCase('tr');
        var liste = bir('[data-bolum-listesi]', panel);
        var sonuclar = bir('[data-sonuclar]', panel);
        sonuclar.innerHTML = '';
        liste.style.display = sorgu ? 'none' : '';
        if (!sorgu) { return; }

        Object.keys(A.sema).forEach(function (b) {
            Object.keys(A.sema[b].alanlar).forEach(function (a) {
                var t = A.sema[b].alanlar[a];
                var metin = (A.sema[b].etiket + ' ' + t.etiket).toLocaleLowerCase('tr');
                if (metin.indexOf(sorgu) === -1) { return; }
                sonuclar.appendChild(ogeOlustur('button', {
                    type: 'button', sinif: 'td-bolum-btn',
                    onclick: function () { alanaGit(b + '.' + a); }
                }, [
                    ogeOlustur('span', {}, [ogeOlustur('small', { sinif: 'd-block text-muted', metin: A.sema[b].etiket }), t.etiket]),
                    ogeOlustur('i', { sinif: 'bi bi-chevron-right ms-auto' })
                ]));
            });
        });

        if (!sonuclar.children.length) {
            sonuclar.appendChild(ogeOlustur('p', { sinif: 'text-muted small px-3', metin: 'Sonuç bulunamadı.' }));
        }
    }

    /* =====================================================================
       Panel: bölüm alanları
       ===================================================================== */

    function bolumAc(anahtar, kaydirmaKoru) {
        var eskiKaydirma = kaydirmaKoru ? panel.parentNode.scrollTop : 0;
        var bolum = A.sema[anahtar];
        acikBolum = anahtar;
        panel.innerHTML = '';

        panel.appendChild(ogeOlustur('div', { sinif: 'td-panel-baslik' }, [
            ogeOlustur('button', { type: 'button', sinif: 'td-geri-btn', title: 'Bölümler', onclick: anaListe }, [ogeOlustur('i', { sinif: 'bi bi-chevron-left' })]),
            ogeOlustur('div', {}, [
                ogeOlustur('small', { metin: 'Özelleştiriyorsunuz' }),
                ogeOlustur('h2', { metin: bolum.etiket })
            ])
        ]));

        if (bolum.aciklama) {
            panel.appendChild(ogeOlustur('p', { sinif: 'td-bolum-aciklama', metin: bolum.aciklama }));
        }

        var govde = ogeOlustur('div', { sinif: 'td-alanlar' });
        Object.keys(bolum.alanlar).forEach(function (alan) {
            govde.appendChild(alanOlustur(bolum.alanlar[alan], anahtar + '.' + alan));
        });
        panel.appendChild(govde);

        panel.parentNode.scrollTop = eskiKaydirma;
    }

    function alanaGit(yol) {
        var p = yol.split('.');
        if (!A.sema[p[0]]) { return; }

        if (p.length >= 3) { acikListeOgeleri[p[0] + '.' + p[1] + '.' + p[2]] = true; }
        bolumAc(p[0]);

        var hedef = bir('[data-yol="' + yol + '"]', panel) || bir('[data-yol="' + p.slice(0, 2).join('.') + '"]', panel);
        if (!hedef) { return; }
        hedef.scrollIntoView({ block: 'center' });
        hedef.classList.add('vurgulu');
        setTimeout(function () { hedef.classList.remove('vurgulu'); }, 1600);
        var girdi = bir('input:not([type=file]), textarea, select', hedef);
        if (girdi) { girdi.focus({ preventScroll: true }); }
    }

    function alanOlustur(tanim, yol) {
        var deger = degerAl(yol);
        var kap = ogeOlustur('div', { sinif: 'td-alan td-tip-' + tanim.tip, 'data-yol': yol });
        var kimlik = 'a-' + yol.replace(/\./g, '-');
        var bildirim = function (yeni) { degistir(yol, yeni, tanim); };
        var isaretle = function () { iframeGonder({ tip: 'isaretle', yol: yol }); };

        if (tanim.tip === 'liste') {
            kap.appendChild(ogeOlustur('label', { sinif: 'td-etiket', metin: tanim.etiket }));
            kap.appendChild(listeOlustur(tanim, yol));
            return kap;
        }

        if (tanim.tip === 'anahtar') {
            var anahtar = ogeOlustur('input', {
                type: 'checkbox', sinif: 'form-check-input', role: 'switch', id: kimlik,
                onchange: function () { bildirim(anahtar.checked); }
            });
            anahtar.checked = !!deger;
            kap.appendChild(ogeOlustur('div', { sinif: 'form-check form-switch' }, [
                anahtar, ogeOlustur('label', { sinif: 'form-check-label', for: kimlik, metin: tanim.etiket })
            ]));
            return kap;
        }

        kap.appendChild(ogeOlustur('label', { sinif: 'td-etiket', for: kimlik, metin: tanim.etiket }));
        var girdi;

        switch (tanim.tip) {
            case 'yazi':
            case 'vurgu':
                girdi = ogeOlustur('textarea', { sinif: 'form-control form-control-sm', id: kimlik, rows: tanim.ham ? 5 : 3 });
                girdi.value = deger == null ? '' : deger;
                girdi.addEventListener('input', function () { bildirim(girdi.value); });
                kap.appendChild(girdi);
                if (tanim.tip === 'vurgu') {
                    kap.appendChild(ogeOlustur('div', { sinif: 'form-text', html: '<code>{süslü parantez}</code> içine aldığınız kelimeler vurgu rengiyle gösterilir.' }));
                }
                break;

            case 'sayi':
                girdi = ogeOlustur('input', { type: 'number', min: 0, max: 100, sinif: 'form-control form-control-sm', id: kimlik });
                girdi.value = deger;
                girdi.addEventListener('input', function () { bildirim(parseInt(girdi.value, 10) || 0); });
                kap.appendChild(girdi);
                break;

            case 'renk':
                var secici = ogeOlustur('input', { type: 'color', sinif: 'form-control form-control-color', id: kimlik });
                var kod = ogeOlustur('input', { type: 'text', sinif: 'form-control form-control-sm', maxlength: 7 });
                secici.value = kod.value = deger || '#000000';
                secici.addEventListener('input', function () { kod.value = secici.value; bildirim(secici.value); });
                kod.addEventListener('input', function () {
                    if (/^#[0-9a-f]{6}$/i.test(kod.value)) { secici.value = kod.value; bildirim(kod.value.toLowerCase()); }
                });
                girdi = kod;
                kap.appendChild(ogeOlustur('div', { sinif: 'd-flex gap-2 align-items-center' }, [secici, kod]));
                break;

            case 'secim':
            case 'ikon':
                girdi = ogeOlustur('select', { sinif: 'form-select form-select-sm', id: kimlik });
                Object.keys(tanim.secenekler || {}).forEach(function (k) {
                    var o = ogeOlustur('option', { value: k, metin: tanim.secenekler[k] });
                    if (k === deger) { o.selected = true; }
                    girdi.appendChild(o);
                });
                if (tanim.tip === 'ikon') {
                    var ikonOnizleme = ogeOlustur('span', { sinif: 'td-ikon-onizleme', html: '<i class="' + (deger || '') + '"></i>' });
                    girdi.addEventListener('change', function () { ikonOnizleme.innerHTML = '<i class="' + girdi.value + '"></i>'; });
                    kap.appendChild(ogeOlustur('div', { sinif: 'd-flex gap-2 align-items-center' }, [ikonOnizleme, girdi]));
                } else {
                    kap.appendChild(girdi);
                }
                girdi.addEventListener('change', function () { bildirim(girdi.value); });
                break;

            case 'gorsel':
                kap.appendChild(gorselOlustur(yol, deger, bildirim));
                girdi = bir('input[type=text]', kap);
                break;

            default: // metin, baglanti
                girdi = ogeOlustur('input', { type: 'text', sinif: 'form-control form-control-sm', id: kimlik });
                if (tanim.tip === 'baglanti') { girdi.setAttribute('list', 'td-baglanti-onerileri'); }
                girdi.value = deger == null ? '' : deger;
                girdi.addEventListener('input', function () { bildirim(girdi.value); });
                kap.appendChild(girdi);
        }

        if (girdi) { girdi.addEventListener('focus', isaretle); }
        return kap;
    }

    function gorselOlustur(yol, deger, bildirim) {
        var sablon = ogeOlustur('div', { sinif: 'gorsel-sec', 'data-gorsel-sec': '' }, [
            ogeOlustur('div', { sinif: 'gorsel-onizleme', 'data-onizleme': '', html: deger ? '' : '<i class="bi bi-image"></i>' }),
            ogeOlustur('div', { sinif: 'flex-grow-1' }, [
                ogeOlustur('input', { type: 'text', sinif: 'form-control form-control-sm mb-2', placeholder: 'uploads/... veya https://...', 'data-deger': '' }),
                ogeOlustur('div', { sinif: 'd-flex flex-wrap gap-1' }, [
                    ogeOlustur('label', { sinif: 'btn btn-sm btn-dark mb-0', html: '<i class="bi bi-upload me-1"></i>Yükle<input type="file" accept="image/*" class="d-none" data-dosya>' }),
                    ogeOlustur('button', { type: 'button', sinif: 'btn btn-sm btn-outline-secondary', 'data-kutuphane': '', html: '<i class="bi bi-images"></i>', title: 'Kütüphane' }),
                    ogeOlustur('button', { type: 'button', sinif: 'btn btn-sm btn-outline-danger', 'data-temizle': '', html: '<i class="bi bi-x-lg"></i>', title: 'Kaldır' })
                ])
            ])
        ]);

        bir('[data-deger]', sablon).value = deger || '';
        if (deger) {
            var img = ogeOlustur('img', { alt: '' });
            img.src = Y.adres(deger);
            bir('[data-onizleme]', sablon).appendChild(img);
        }

        Y.gorselSeciciBagla(sablon, bildirim);
        bir('[data-deger]', sablon).addEventListener('input', function (e) { bildirim(e.target.value.trim()); });
        return sablon;
    }

    /* =====================================================================
       Liste (tekrarlanan öğeler)
       ===================================================================== */

    function ogeBasligi(tanim, oge, sira) {
        var deger = oge && oge[tanim.baslik_alani];
        if (tanim.alanlar[tanim.baslik_alani] && tanim.alanlar[tanim.baslik_alani].secenekler) {
            deger = tanim.alanlar[tanim.baslik_alani].secenekler[deger] || deger;
        }
        return (deger && String(deger).trim()) ? String(deger).split('\n')[0] : tanim.oge_etiketi + ' ' + (sira + 1);
    }

    function listeOlustur(tanim, yol) {
        var ogeler = degerAl(yol);
        if (!Array.isArray(ogeler)) { ogeler = []; degerYaz(yol, ogeler); }

        var kap = ogeOlustur('div', { sinif: 'td-liste' });

        ogeler.forEach(function (oge, i) {
            var ogeYolu = yol + '.' + i;
            var acik = !!acikListeOgeleri[ogeYolu];
            var baslik = ogeOlustur('span', { sinif: 'td-oge-adi', metin: ogeBasligi(tanim, oge, i) });

            var islem = function (ikon, ipucu, fn, devre) {
                return ogeOlustur('button', {
                    type: 'button', sinif: 'td-oge-islem', title: ipucu, disabled: devre,
                    onclick: function (e) { e.stopPropagation(); fn(); }
                }, [ogeOlustur('i', { sinif: 'bi ' + ikon })]);
            };

            var govde = ogeOlustur('div', { sinif: 'td-oge-govde' });
            Object.keys(tanim.alanlar).forEach(function (alt) {
                var altYol = ogeYolu + '.' + alt;
                var altTanim = tanim.alanlar[alt];
                var alanEl = alanOlustur(altTanim, altYol);
                if (alt === tanim.baslik_alani) {
                    alanEl.addEventListener('input', function () { baslik.textContent = ogeBasligi(tanim, degerAl(ogeYolu), i); });
                    alanEl.addEventListener('change', function () { baslik.textContent = ogeBasligi(tanim, degerAl(ogeYolu), i); });
                }
                govde.appendChild(alanEl);
            });

            var kart = ogeOlustur('div', { sinif: 'td-oge' + (acik ? ' acik' : ''), 'data-yol': ogeYolu }, [
                ogeOlustur('div', {
                    sinif: 'td-oge-ust',
                    onclick: function () {
                        kart.classList.toggle('acik');
                        acikListeOgeleri[ogeYolu] = kart.classList.contains('acik');
                    }
                }, [
                    ogeOlustur('i', { sinif: 'bi bi-grip-vertical text-muted' }),
                    baslik,
                    islem('bi-arrow-up', 'Yukarı taşı', function () { tasi(yol, i, -1); }, i === 0),
                    islem('bi-arrow-down', 'Aşağı taşı', function () { tasi(yol, i, 1); }, i === ogeler.length - 1),
                    islem('bi-copy', 'Çoğalt', function () { cogalt(yol, i); }),
                    islem('bi-trash', 'Sil', function () { sil(yol, i, tanim); }),
                    ogeOlustur('i', { sinif: 'bi bi-chevron-down td-oge-ok' })
                ]),
                govde
            ]);
            kap.appendChild(kart);
        });

        kap.appendChild(ogeOlustur('button', {
            type: 'button', sinif: 'btn btn-sm btn-outline-dark w-100 td-ekle',
            onclick: function () { ekle(yol, tanim); }
        }, [ogeOlustur('i', { sinif: 'bi bi-plus-lg me-1' }), tanim.oge_etiketi + ' ekle']));

        return kap;
    }

    function listeYenidenCiz() {
        if (acikBolum) { bolumAc(acikBolum, true); }
    }

    function acikDurumlariniKaydir(yol, eslestir) {
        var yeni = {};
        Object.keys(acikListeOgeleri).forEach(function (k) {
            if (k.indexOf(yol + '.') !== 0) { yeni[k] = acikListeOgeleri[k]; return; }
            var sira = parseInt(k.slice(yol.length + 1), 10);
            var hedef = eslestir(sira);
            if (hedef !== null) { yeni[yol + '.' + hedef] = acikListeOgeleri[k]; }
        });
        acikListeOgeleri = yeni;
    }

    function ekle(yol, tanim) {
        var ogeler = degerAl(yol);
        var yeni = {};
        Object.keys(tanim.alanlar).forEach(function (k) { yeni[k] = tanim.alanlar[k].varsayilan != null ? tanim.alanlar[k].varsayilan : ''; });
        ogeler.push(yeni);
        acikListeOgeleri[yol + '.' + (ogeler.length - 1)] = true;
        listeYenidenCiz();
        yapisalDegisiklik();
    }

    function sil(yol, i, tanim) {
        if (!confirm(tanim.oge_etiketi + ' silinsin mi?')) { return; }
        degerAl(yol).splice(i, 1);
        acikDurumlariniKaydir(yol, function (s) { return s === i ? null : (s > i ? s - 1 : s); });
        listeYenidenCiz();
        yapisalDegisiklik();
    }

    function cogalt(yol, i) {
        var ogeler = degerAl(yol);
        ogeler.splice(i + 1, 0, kopya(ogeler[i]));
        acikDurumlariniKaydir(yol, function (s) { return s > i ? s + 1 : s; });
        acikListeOgeleri[yol + '.' + (i + 1)] = true;
        listeYenidenCiz();
        yapisalDegisiklik();
    }

    function tasi(yol, i, yon) {
        var ogeler = degerAl(yol);
        var j = i + yon;
        if (j < 0 || j >= ogeler.length) { return; }
        var gecici = ogeler[i];
        ogeler[i] = ogeler[j];
        ogeler[j] = gecici;
        acikDurumlariniKaydir(yol, function (s) { return s === i ? j : (s === j ? i : s); });
        listeYenidenCiz();
        yapisalDegisiklik();
    }

    /* =====================================================================
       Üst çubuk eylemleri
       ===================================================================== */

    function yayinla(dugme) {
        clearTimeout(zamanlayici);
        zamanlayici = null;
        dugme.disabled = true;
        durumGoster('kaydediliyor', 'Yayınlanıyor…');

        istek(A.adresler.yayinla, { degerler: durum })
            .then(function (j) {
                kirli = false;
                durumGoster('yayinda', 'Yayındaki sürüm');
                bildir(j.mesaj || 'Yayınlandı.', 'success');
                if (yenileGerekli) { yenileGerekli = false; onizlemeYenile(); }
            })
            .catch(function (h) {
                durumGoster('hata', h.message);
                bildir(h.message, 'danger');
            })
            .finally(function () { dugme.disabled = false; });
    }

    function sunucuDegerleriniYukle(adres, soru, sonDurum) {
        if (!confirm(soru)) { return; }
        clearTimeout(zamanlayici);
        zamanlayici = null;

        istek(adres).then(function (j) {
            durum = j.degerler;
            acikListeOgeleri = {};
            kirli = sonDurum === 'taslak';
            durumGoster(sonDurum, sonDurum === 'taslak' ? 'Taslak kaydedildi — yayınlanmadı' : 'Yayındaki sürüm');
            if (acikBolum) { bolumAc(acikBolum); } else { anaListe(); }
            onizlemeYenile();
        }).catch(function (h) { bildir(h.message, 'danger'); });
    }

    hepsi('[data-eylem]', kok).forEach(function (d) {
        d.addEventListener('click', function () {
            var eylem = d.getAttribute('data-eylem');
            if (eylem === 'yayinla') { yayinla(d); }
            if (eylem === 'taslak-sil') { sunucuDegerleriniYukle(A.adresler.taslakSil, 'Yayınlanmamış tüm değişiklikler silinsin mi?', 'yayinda'); }
            if (eylem === 'sifirla') { sunucuDegerleriniYukle(A.adresler.sifirla, 'Tüm tema varsayılan değerlere döndürülsün mü? (Yayınlayana kadar site etkilenmez.)', 'taslak'); }
        });
    });

    hepsi('[data-cihaz]', kok).forEach(function (d) {
        d.addEventListener('click', function () {
            hepsi('[data-cihaz]', kok).forEach(function (x) { x.classList.toggle('aktif', x === d); });
            cerceve.setAttribute('data-genislik', d.getAttribute('data-cihaz'));
        });
    });

    bir('[data-sayfa]', kok).addEventListener('change', function (e) {
        bekleyeniKaydet().then(function () {
            var url = new URL(e.target.value);
            url.searchParams.set('onizleme', '1');
            yukleniyor.classList.add('gorunur');
            iframe.src = url.toString();
        });
    });

    window.addEventListener('beforeunload', function (e) {
        if (zamanlayici || kaydediliyor) {
            bekleyeniKaydet();
            e.preventDefault();
            e.returnValue = '';
        }
    });

    /* =====================================================================
       Başlangıç
       ===================================================================== */

    var oneriler = ogeOlustur('datalist', { id: 'td-baglanti-onerileri' });
    BAGLANTI_ONERILERI.forEach(function (o) { oneriler.appendChild(ogeOlustur('option', { value: o })); });
    document.body.appendChild(oneriler);

    anaListe();
    if (A.bildirim) { bildir(A.bildirim, 'success'); }
    if (kirli) { durumGoster('taslak', 'Yayınlanmamış değişiklikler var'); }
})();
