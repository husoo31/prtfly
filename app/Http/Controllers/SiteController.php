<?php

namespace App\Http\Controllers;

use App\Models\BlogYazisi;
use App\Models\Mesaj;
use App\Models\Proje;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

class SiteController extends Controller
{
    public function anasayfa(): View
    {
        $projeler = Proje::query()->yayinda()->limit(max(1, (int) tema('projeler.adet', 5)))->get();

        return view('site.anasayfa', [
            'projeler' => $projeler,
            'kategoriler' => $this->kategorilerOf($projeler),
            'yazilar' => BlogYazisi::query()->yayinda()->limit(max(1, (int) tema('blog.adet', 4)))->get(),
        ]);
    }

    public function projeler(): View
    {
        $projeler = Proje::query()->yayinda()->get();

        return view('site.projeler', [
            'projeler' => $projeler,
            'kategoriler' => $this->kategorilerOf($projeler),
        ]);
    }

    public function blog(Request $request): View
    {
        $ara = trim((string) $request->query('ara', ''));
        $kategori = trim((string) $request->query('kategori', ''));

        $yazilar = BlogYazisi::query()->yayinda()
            ->when($ara !== '', fn ($q) => $q->where(fn ($q) => $q
                ->where('baslik', 'like', '%'.$ara.'%')
                ->orWhere('ozet', 'like', '%'.$ara.'%')
                ->orWhere('icerik', 'like', '%'.$ara.'%')))
            ->when($kategori !== '', fn ($q) => $q->where('kategori', $kategori))
            ->paginate(6)
            ->withQueryString();

        return view('site.blog', [
            'yazilar' => $yazilar,
            'ara' => $ara,
            'kategori' => $kategori,
        ] + $this->kenarCubugu());
    }

    public function blogDetay(string $slug): View
    {
        $yazi = BlogYazisi::query()->yayinda()->where('slug', $slug)->firstOrFail();

        $onceki = BlogYazisi::query()->yayinda()->where('yayin_tarihi', '<', $yazi->yayin_tarihi)->first();
        $sonraki = BlogYazisi::query()->where('aktif', true)->where('yayin_tarihi', '<=', now())
            ->where('yayin_tarihi', '>', $yazi->yayin_tarihi)->orderBy('yayin_tarihi')->first();

        return view('site.blog-detay', [
            'yazi' => $yazi,
            'onceki' => $onceki,
            'sonraki' => $sonraki,
        ] + $this->kenarCubugu());
    }

    public function iletisim(Request $request): JsonResponse|RedirectResponse
    {
        // Bot tuzağı: gizli alan doluysa sessizce başarılı say.
        if (filled($request->input('web_sitesi'))) {
            return $this->iletisimYaniti($request, true);
        }

        $veri = $request->validate([
            'ad' => 'required|string|max:100',
            'soyad' => 'nullable|string|max:100',
            'eposta' => 'required|email|max:190',
            'telefon' => 'nullable|string|max:40',
            'mesaj' => 'required|string|max:5000',
        ]);

        $mesaj = Mesaj::create($veri + ['ip' => $request->ip()]);

        $alici = ayar('bildirim_eposta');

        if ($alici && filter_var($alici, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::raw(
                    "Yeni iletişim mesajı\n\nGönderen: {$mesaj->tamAd()}\nE-posta: {$mesaj->eposta}\nTelefon: {$mesaj->telefon}\n\n{$mesaj->mesaj}",
                    fn ($m) => $m->to($alici)->replyTo($mesaj->eposta, $mesaj->tamAd())
                        ->subject('Yeni mesaj: '.$mesaj->tamAd())
                );
            } catch (Throwable $e) {
                Log::warning('İletişim bildirimi gönderilemedi: '.$e->getMessage());
            }
        }

        return $this->iletisimYaniti($request, true);
    }

    protected function iletisimYaniti(Request $request, bool $basarili): JsonResponse|RedirectResponse
    {
        $mesaj = (string) tema('iletisim.basari_mesaji');

        if ($request->expectsJson()) {
            return response()->json(['basarili' => $basarili, 'mesaj' => $mesaj]);
        }

        return redirect()->to(route('anasayfa').'#contact')->with('iletisim_basarili', $mesaj);
    }

    protected function kategorilerOf($projeler): array
    {
        return $projeler->flatMap(fn (Proje $p) => $p->kategoriler())->unique()->values()->all();
    }

    protected function kenarCubugu(): array
    {
        return [
            'sonYazilar' => BlogYazisi::query()->yayinda()->limit(4)->get(),
            'blogKategorileri' => BlogYazisi::query()->yayinda()->whereNotNull('kategori')
                ->reorder()->selectRaw('kategori, count(*) as adet')->groupBy('kategori')->orderBy('kategori')->get(),
        ];
    }
}
