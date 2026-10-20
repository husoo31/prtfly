/*!
 * Laravel Portfolyo — tema düzenleyici canlı önizleme köprüsü
 * Yalnızca yönetici ?onizleme=1 ile siteyi açtığında yüklenir.
 */
(function () {
    'use strict';

    if (window.parent === window) { return; }

    var koken = location.origin;
    var temel = (document.querySelector('meta[name="temel-url"]') || {}).content || koken;
    var ebeveyn = window.parent;

    function gonder(veri) { ebeveyn.postMessage(veri, koken); }

    function kacis(metin) {
        return String(metin)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }

    function adres(deger) {
        deger = String(deger || '').trim();
        if (deger === '' || /^(https?:|\/\/|#|mailto:|tel:)/i.test(deger)) { return deger; }
        return temel.replace(/\/+$/, '') + '/' + deger.replace(/^\/+/, '');
    }

    function vurgula(metin) {
        return kacis(metin).replace(/\{([^{}]+)\}/g, '<span class="vurgu">$1</span>').replace(/\n/g, '<br>');
    }

    function ogeler(yol) {
        var guvenli = window.CSS && CSS.escape ? CSS.escape(yol) : yol.replace(/"/g, '\\"');
        return Array.prototype.slice.call(document.querySelectorAll('[data-tema="' + guvenli + '"]'));
    }

    function alanGuncelle(yol, deger) {
        deger = deger == null ? '' : String(deger).replace(/\{yil\}/g, String(new Date().getFullYear()));

        var hedefler = ogeler(yol);
        if (!hedefler.length) {
            // Bu alan sayfada doğrudan bir öğeye bağlı değil: düzenleyici yenilesin.
            gonder({ tip: 'eksik', yol: yol });
            return;
        }

        hedefler.forEach(function (el) {
            switch (el.getAttribute('data-tema-oz')) {
                case 'src':
                    el.setAttribute('src', adres(deger));
                    break;
                case 'href':
                    el.setAttribute('href', adres(deger));
                    break;
                case 'placeholder':
                    el.setAttribute('placeholder', deger);
                    break;
                case 'vurgu':
                    el.innerHTML = vurgula(deger);
                    break;
                case 'satir':
                    el.innerHTML = kacis(deger).replace(/\n/g, '<br>');
                    break;
                default:
                    el.textContent = deger;
            }

            if (el.hasAttribute('data-yansima')) {
                el.setAttribute('data-yansima', deger);
            }
        });
    }

    function isaretle(yol) {
        Array.prototype.forEach.call(document.querySelectorAll('.isaretli'), function (el) { el.classList.remove('isaretli'); });
        var hedefler = ogeler(yol);
        if (!hedefler.length) { return false; }
        hedefler.forEach(function (el) { el.classList.add('isaretli'); });
        var ilk = hedefler.find(function (el) { return el.offsetParent !== null; }) || hedefler[0];
        ilk.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return true;
    }

    window.addEventListener('message', function (e) {
        if (e.origin !== koken || !e.data || typeof e.data !== 'object') { return; }
        var veri = e.data;

        if (veri.tip === 'alan') {
            alanGuncelle(veri.yol, veri.deger);
            if (veri.yol === 'hero.unvan' && window.unvanSigdir) { window.unvanSigdir(); }
        } else if (veri.tip === 'renk') {
            document.documentElement.style.setProperty(veri.degisken, veri.deger);
        } else if (veri.tip === 'isaretle') {
            isaretle(veri.yol);
        } else if (veri.tip === 'kaydir') {
            window.scrollTo(0, veri.y || 0);
        }
    });

    // Önizlemedeki bir öğeye tıklanınca düzenleyicide ilgili alanı aç.
    document.addEventListener('click', function (e) {
        var el = e.target.closest('[data-tema]');
        if (el) {
            e.preventDefault();
            e.stopPropagation();
            gonder({ tip: 'sec', yol: el.getAttribute('data-tema') });
            return;
        }

        // Site içi bağlantılarda önizleme modunu koru.
        var a = e.target.closest('a[href]');
        if (a && a.origin === koken && !a.getAttribute('href').startsWith('#') && a.target !== '_blank') {
            var url = new URL(a.href);
            if (url.pathname.indexOf('/yonetim') === 0) { return; }
            url.searchParams.set('onizleme', '1');
            e.preventDefault();
            location.href = url.toString();
        }
    }, true);

    var bekleyen = null;
    window.addEventListener('scroll', function () {
        clearTimeout(bekleyen);
        bekleyen = setTimeout(function () {
            gonder({ tip: 'kaydirma', y: window.scrollY, sayfa: location.pathname });
        }, 120);
    }, { passive: true });

    gonder({ tip: 'hazir', sayfa: location.pathname, baslik: document.title });
})();
