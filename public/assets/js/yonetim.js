/*!
 * Laravel Portfolyo — yönetim paneli ortak betikleri
 * webdanismani.com tarafından ücretsiz dağıtılır. Satışı yasaktır.
 */
(function () {
    'use strict';

    var Y = window.Yonetim || {};
    var hepsi = function (s, k) { return Array.prototype.slice.call((k || document).querySelectorAll(s)); };

    /** Görsel adresini tam URL'ye çevirir. */
    Y.adres = function (deger) {
        deger = String(deger || '').trim();
        if (deger === '' || /^(https?:|\/\/|data:|blob:)/i.test(deger)) { return deger; }
        return Y.temel.replace(/\/+$/, '') + '/' + deger.replace(/^\/+/, '');
    };

    /** Dosya yükler → Promise<{yol, url}> */
    Y.yukle = function (dosya) {
        var veri = new FormData();
        veri.append('dosya', dosya);

        return fetch(Y.medyaYukle, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': Y.csrf, 'Accept': 'application/json' },
            body: veri,
            credentials: 'same-origin'
        }).then(function (yanit) {
            return yanit.json().then(function (j) {
                if (!yanit.ok) {
                    var hata = j && j.errors ? Object.values(j.errors)[0][0] : (j.message || 'Yükleme başarısız.');
                    throw new Error(hata);
                }
                return j;
            });
        });
    };

    /* ---------- Medya kütüphanesi ---------- */
    var pencereEl = document.getElementById('medya-penceresi');
    var pencere = null;
    var secimGeriCagri = null;

    function kutuphaneDoldur() {
        var izgara = pencereEl.querySelector('[data-medya-izgara]');
        izgara.innerHTML = '<p class="text-muted">Yükleniyor…</p>';

        fetch(Y.medyaListe, { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' })
            .then(function (y) { return y.json(); })
            .then(function (j) {
                izgara.innerHTML = '';
                if (!j.dosyalar.length) {
                    izgara.innerHTML = '<p class="text-muted">Henüz yüklenmiş görsel yok. Sağ üstten yükleyebilirsiniz.</p>';
                    return;
                }
                j.dosyalar.forEach(function (d) {
                    var b = document.createElement('button');
                    b.type = 'button';
                    b.className = 'medya-oge';
                    b.title = d.ad;
                    var img = document.createElement('img');
                    img.src = d.url;
                    img.loading = 'lazy';
                    img.alt = '';
                    b.appendChild(img);
                    b.addEventListener('click', function () { sec(d); });
                    izgara.appendChild(b);
                });
            });
    }

    function sec(d) {
        if (secimGeriCagri) { secimGeriCagri(d.yol, d.url); }
        secimGeriCagri = null;
        pencere.hide();
    }

    Y.kutuphane = function (geriCagri) {
        if (!pencereEl || !window.bootstrap) { return; }
        pencere = pencere || new bootstrap.Modal(pencereEl);
        secimGeriCagri = geriCagri;
        kutuphaneDoldur();
        pencere.show();
    };

    if (pencereEl) {
        var yukleGirdi = pencereEl.querySelector('[data-medya-yukle]');
        yukleGirdi.addEventListener('change', function () {
            if (!yukleGirdi.files[0]) { return; }
            Y.yukle(yukleGirdi.files[0]).then(sec).catch(function (h) { alert(h.message); })
                .finally(function () { yukleGirdi.value = ''; });
        });
    }

    /* ---------- Formlardaki görsel seçici bileşeni ---------- */
    Y.gorselSeciciBagla = function (kok, degisince) {
        var girdi = kok.querySelector('[data-deger]');
        var onizleme = kok.querySelector('[data-onizleme]');
        var dosya = kok.querySelector('[data-dosya]');

        function ayarla(yol) {
            girdi.value = yol;
            onizleme.innerHTML = yol ? '<img src="' + Y.adres(yol).replace(/"/g, '&quot;') + '" alt="">' : '<i class="bi bi-image"></i>';
            if (degisince) { degisince(yol); }
        }

        girdi.addEventListener('change', function () { ayarla(girdi.value.trim()); });
        dosya.addEventListener('change', function () {
            if (!dosya.files[0]) { return; }
            kok.classList.add('yukleniyor');
            Y.yukle(dosya.files[0]).then(function (j) { ayarla(j.yol); })
                .catch(function (h) { alert(h.message); })
                .finally(function () { kok.classList.remove('yukleniyor'); dosya.value = ''; });
        });
        kok.querySelector('[data-kutuphane]').addEventListener('click', function () {
            Y.kutuphane(function (yol) { ayarla(yol); });
        });
        kok.querySelector('[data-temizle]').addEventListener('click', function () { ayarla(''); });
    };

    hepsi('[data-gorsel-sec]').forEach(function (k) {
        if (!k.closest('[data-panel]')) { Y.gorselSeciciBagla(k); }
    });

    /* ---------- Silme onayı ---------- */
    hepsi('form[data-onay]').forEach(function (f) {
        f.addEventListener('submit', function (e) {
            if (!confirm(f.getAttribute('data-onay'))) { e.preventDefault(); }
        });
    });

    /* ---------- Mobil kenar menü ---------- */
    var kenarAc = document.querySelector('.y-kenar-ac');
    if (kenarAc) {
        var perde = document.querySelector('.y-perde');
        var degistir = function () { document.body.classList.toggle('y-kenar-acik'); };
        kenarAc.addEventListener('click', degistir);
        if (perde) { perde.addEventListener('click', degistir); }
    }

    /* ---------- Markdown araç çubuğu ---------- */
    hepsi('.y-md-arac').forEach(function (arac) {
        var alan = document.querySelector(arac.getAttribute('data-hedef'));
        if (!alan) { return; }

        function ekle(oncesi, sonrasi) {
            var bas = alan.selectionStart, son = alan.selectionEnd;
            var secili = alan.value.slice(bas, son);
            alan.setRangeText(oncesi + secili + sonrasi, bas, son, 'end');
            if (!secili) { alan.selectionStart = alan.selectionEnd = bas + oncesi.length; }
            alan.focus();
        }

        hepsi('[data-md]', arac).forEach(function (d) {
            d.addEventListener('click', function () {
                var parca = d.getAttribute('data-md').replace(/\\n/g, '\n').split('|');
                ekle(parca[0], parca[1] || '');
            });
        });

        var gorselDugme = arac.querySelector('[data-md-gorsel]');
        if (gorselDugme) {
            gorselDugme.addEventListener('click', function () {
                Y.kutuphane(function (yol, url) { ekle('\n![](' + url + ')\n', ''); });
            });
        }
    });

    window.Yonetim = Y;
})();
