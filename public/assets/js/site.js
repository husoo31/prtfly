/*!
 * Laravel Portfolyo — site betikleri (bağımlılıksız)
 * webdanismani.com tarafından ücretsiz dağıtılır. Satışı yasaktır.
 */
(function () {
    'use strict';

    var govde = document.body;
    var hepsi = function (secici, kok) { return Array.prototype.slice.call((kok || document).querySelectorAll(secici)); };
    var bir = function (secici, kok) { return (kok || document).querySelector(secici); };

    /* ---------- Önyükleyici ---------- */
    function yuklendi() { govde.classList.add('yuklendi'); }
    if (document.readyState === 'complete') { yuklendi(); } else { window.addEventListener('load', yuklendi); }
    setTimeout(yuklendi, 3500);

    /* ---------- Büyük unvan yazısını kapsayıcıya sığdır ---------- */
    window.unvanSigdir = function () {
        var unvan = bir('.hero .unvan');
        if (!unvan) { return; }
        unvan.style.fontSize = '';
        var kap = unvan.parentNode.getBoundingClientRect();
        var sol = unvan.getBoundingClientRect().left;
        var bos = kap.right - sol;
        if (unvan.scrollWidth > bos && bos > 0) {
            var boyut = parseFloat(getComputedStyle(unvan).fontSize);
            unvan.style.fontSize = Math.max(28, Math.floor(boyut * bos / unvan.scrollWidth)) + 'px';
        }
    };
    window.unvanSigdir();
    window.addEventListener('resize', window.unvanSigdir);
    if (document.fonts && document.fonts.ready) { document.fonts.ready.then(window.unvanSigdir); }

    /* ---------- Sabit üst menü ---------- */
    var ust = bir('.ust-bolum');
    var yer = document.createElement('div');
    if (ust) {
        ust.parentNode.insertBefore(yer, ust);
    }

    /* ---------- Yukarı çık + ilerleme halkası ---------- */
    var yukari = bir('.yukari-cik');
    var halka = yukari ? bir('circle', yukari) : null;
    var cevre = 2 * Math.PI * 21.5;
    if (halka) {
        halka.style.strokeDasharray = cevre;
        halka.style.strokeDashoffset = cevre;
    }
    if (yukari) {
        yukari.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: 'smooth' }); });
    }

    function kaydirmaGuncelle() {
        var y = window.scrollY;

        if (ust) {
            var sabit = y > 300;
            if (sabit !== ust.classList.contains('sabit')) {
                yer.style.height = sabit ? ust.offsetHeight + 'px' : '';
                ust.classList.toggle('sabit', sabit);
            }
        }

        if (yukari) {
            var yukseklik = document.documentElement.scrollHeight - window.innerHeight;
            yukari.classList.toggle('gorunur', y > 400);
            if (halka && yukseklik > 0) {
                halka.style.strokeDashoffset = cevre - (y / yukseklik) * cevre;
            }
        }

        aktifMenuGuncelle();
    }

    /* ---------- Tek sayfa menü: görünür bölümü işaretle ---------- */
    var menuBaglantilari = hepsi('.ana-menu a[href*="#"]').filter(function (a) {
        return a.pathname === location.pathname && a.hash.length > 1;
    });

    function aktifMenuGuncelle() {
        if (!menuBaglantilari.length) { return; }
        var sinir = window.scrollY + 140;
        var aktif = null;

        menuBaglantilari.forEach(function (a) {
            var hedef = document.getElementById(decodeURIComponent(a.hash.slice(1)));
            if (hedef && hedef.offsetTop <= sinir) { aktif = a; }
        });

        menuBaglantilari.forEach(function (a) { a.classList.toggle('aktif', a === aktif); });
    }

    var bekleyen = false;
    window.addEventListener('scroll', function () {
        if (bekleyen) { return; }
        bekleyen = true;
        requestAnimationFrame(function () { kaydirmaGuncelle(); bekleyen = false; });
    }, { passive: true });
    kaydirmaGuncelle();

    /* ---------- Mobil menü ---------- */
    var menuAc = bir('.menu-ac');
    var anaMenu = bir('.ana-menu');
    if (menuAc && anaMenu) {
        menuAc.addEventListener('click', function () {
            menuAc.classList.toggle('acik');
            anaMenu.classList.toggle('acik');
        });
        hepsi('a', anaMenu).forEach(function (a) {
            a.addEventListener('click', function () {
                menuAc.classList.remove('acik');
                anaMenu.classList.remove('acik');
            });
        });
    }

    /* ---------- Yan panel ---------- */
    var yanPanel = bir('.yan-panel');
    var perde = bir('.perde');

    function yanPanelDurum(acik) {
        if (!yanPanel) { return; }
        yanPanel.classList.toggle('acik', acik);
        yanPanel.setAttribute('aria-hidden', acik ? 'false' : 'true');
        if (perde) { perde.classList.toggle('acik', acik); }
    }

    hepsi('.yan-panel-ac').forEach(function (d) { d.addEventListener('click', function () { yanPanelDurum(true); }); });
    hepsi('.yan-panel-kapat, .perde, .yan-panel-baglanti').forEach(function (d) {
        d.addEventListener('click', function () { yanPanelDurum(false); });
    });

    /* ---------- Kaydırma animasyonları ---------- */
    var animler = hepsi('.anim');
    if (govde.classList.contains('animasyonsuz') || govde.classList.contains('onizleme') || !('IntersectionObserver' in window)) {
        animler.forEach(function (el) { el.classList.add('gorundu'); });
    } else {
        var gozcu = new IntersectionObserver(function (girdiler) {
            girdiler.forEach(function (g) {
                if (g.isIntersecting) {
                    g.target.classList.add('gorundu');
                    gozcu.unobserve(g.target);
                }
            });
        }, { rootMargin: '0px 0px -60px 0px', threshold: 0.08 });
        animler.forEach(function (el) { gozcu.observe(el); });
    }

    /* ---------- Otomatik kaydırıcılar (yetenekler, markalar) ---------- */
    hepsi('.kaydirici.otomatik').forEach(function (k) {
        var dur = false;
        k.addEventListener('mouseenter', function () { dur = true; });
        k.addEventListener('mouseleave', function () { dur = false; });
        k.addEventListener('touchstart', function () { dur = true; }, { passive: true });

        setInterval(function () {
            if (dur || k.scrollWidth <= k.clientWidth + 4) { return; }
            var ilk = k.firstElementChild;
            if (!ilk) { return; }
            var adim = ilk.getBoundingClientRect().width + parseFloat(getComputedStyle(k).columnGap || 24);
            var sona = k.scrollLeft + k.clientWidth >= k.scrollWidth - 4;
            k.scrollTo({ left: sona ? 0 : k.scrollLeft + adim, behavior: 'smooth' });
        }, 2600);
    });

    /* ---------- Akordeon (hizmetler, blog satırları) ---------- */
    hepsi('.akordeon').forEach(function (grup) {
        var ogeler = Array.prototype.slice.call(grup.children);
        var ac = function (oge) {
            ogeler.forEach(function (o) { o.classList.toggle('acik', o === oge); });
        };
        ogeler.forEach(function (oge) {
            oge.addEventListener('click', function (e) {
                if (e.target.closest('a')) { return; }
                ac(oge);
            });
            if (window.matchMedia('(hover: hover)').matches) {
                oge.addEventListener('mouseenter', function () { ac(oge); });
            }
        });
    });

    /* ---------- Proje filtresi ---------- */
    hepsi('[data-filtre]').forEach(function (filtre) {
        var bolum = filtre.closest('section') || document;
        var kutular = hepsi('[data-proje-izgara] .proje-kutu', bolum);

        hepsi('button', filtre).forEach(function (dugme) {
            dugme.addEventListener('click', function () {
                var deger = dugme.getAttribute('data-filtre-deger');
                hepsi('button', filtre).forEach(function (d) { d.classList.toggle('aktif', d === dugme); });

                kutular.forEach(function (kutu) {
                    var uygun = deger === '*' || kutu.classList.contains(deger);
                    kutu.classList.toggle('gizli', !uygun);
                    kutu.classList.remove('beliriyor');
                    if (uygun) {
                        void kutu.offsetWidth;
                        kutu.classList.add('beliriyor');
                    }
                });
            });
        });
    });

    /* ---------- Proje detay penceresi ---------- */
    var pencereler = hepsi('.pencere');
    var acikPencere = null;

    function pencereAc(pencere) {
        if (acikPencere) { acikPencere.classList.remove('acik'); }
        acikPencere = pencere;
        pencere.classList.add('acik');
        pencere.scrollTop = 0;
        document.documentElement.style.overflow = 'hidden';
    }

    function pencereKapat() {
        if (!acikPencere) { return; }
        acikPencere.classList.remove('acik');
        acikPencere = null;
        document.documentElement.style.overflow = '';
    }

    hepsi('[data-pencere]').forEach(function (tetik) {
        tetik.addEventListener('click', function (e) {
            var hedef = document.getElementById(tetik.getAttribute('data-pencere'));
            if (!hedef) { return; }
            e.preventDefault();
            pencereAc(hedef);
        });
    });

    pencereler.forEach(function (pencere) {
        pencere.addEventListener('click', function (e) {
            if (e.target === pencere || e.target.closest('.pencere-kapat')) { pencereKapat(); }
            var git = e.target.closest('[data-pencere-git]');
            if (git) {
                var sira = pencereler.indexOf(pencere) + parseInt(git.getAttribute('data-pencere-git'), 10);
                pencereAc(pencereler[(sira + pencereler.length) % pencereler.length]);
            }
        });
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            pencereKapat();
            yanPanelDurum(false);
        }
    });

    /* ---------- Referans kaydırıcısı ---------- */
    hepsi('[data-referans]').forEach(function (sahne) {
        var metinler = hepsi('.referans-metin', sahne);
        var buyukler = hepsi('.referans-buyuk img', sahne);
        var kucukler = hepsi('.referans-kucukler button', sahne);
        var sira = 0;
        var zamanlayici;

        function goster(yeni) {
            sira = (yeni + metinler.length) % metinler.length;
            metinler.forEach(function (m, i) { m.classList.toggle('aktif', i === sira); });
            buyukler.forEach(function (b, i) { b.classList.toggle('aktif', i === sira); });

            // Aktif olanı gizle, sonraki iki görseli küçük olarak göster.
            kucukler.forEach(function (k) { k.classList.add('gizli'); k.style.order = ''; });
            for (var adim = 1; adim <= Math.min(2, kucukler.length - 1); adim++) {
                var k = kucukler[(sira + adim) % kucukler.length];
                k.classList.remove('gizli');
                k.style.order = adim;
            }
        }

        function otomatik() {
            clearInterval(zamanlayici);
            zamanlayici = setInterval(function () { goster(sira + 1); }, 7000);
        }

        hepsi('.referans-oklar button', sahne).forEach(function (d) {
            d.addEventListener('click', function () {
                goster(sira + parseInt(d.getAttribute('data-yon'), 10));
                otomatik();
            });
        });

        kucukler.forEach(function (k) {
            k.addEventListener('click', function () {
                goster(parseInt(k.getAttribute('data-sira'), 10));
                otomatik();
            });
        });

        if (metinler.length > 1) {
            goster(0);
            if (!govde.classList.contains('onizleme')) { otomatik(); }
        }
    });

    /* ---------- İletişim formu (AJAX) ---------- */
    hepsi('[data-iletisim-formu]').forEach(function (form) {
        var durum = bir('.form-durum', form);
        var dugme = bir('button[type="submit"]', form);

        form.addEventListener('submit', function (e) {
            if (!window.fetch || !window.FormData) { return; }
            e.preventDefault();

            dugme.disabled = true;
            durum.className = 'form-durum';
            durum.textContent = '…';

            fetch(form.action, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: new FormData(form),
                credentials: 'same-origin'
            }).then(function (yanit) {
                return yanit.json().catch(function () { return {}; }).then(function (veri) {
                    if (!yanit.ok) { throw veri; }
                    return veri;
                });
            }).then(function (veri) {
                durum.classList.add('basarili');
                durum.textContent = veri.mesaj || '';
                form.reset();
            }).catch(function (hata) {
                durum.classList.add('hatali');
                var ilk = hata && hata.errors ? Object.values(hata.errors)[0] : null;
                durum.textContent = (ilk && ilk[0]) || form.getAttribute('data-hata');
            }).finally(function () {
                dugme.disabled = false;
            });
        });
    });
})();
