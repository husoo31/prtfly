<?php

namespace App\Http\Controllers\Yonetim;

use App\Http\Controllers\Controller;
use App\Support\Tema;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TemaController extends Controller
{
    public function __construct(protected Tema $tema) {}

    public function index(): View
    {
        return view('yonetim.tema', [
            'sema' => $this->tema->istemciSemasi(),
            'degerler' => $this->tema->duzenleyiciDegerleri(),
            'taslakVar' => $this->tema->taslakVarMi(),
            'canliTipler' => config('tema.canli_tipler'),
        ]);
    }

    /** Düzenleyicideki anlık değişiklikleri oturuma taslak olarak yazar. */
    public function taslak(Request $request): JsonResponse
    {
        $this->tema->taslakKaydet($this->girdi($request));

        return response()->json(['basarili' => true]);
    }

    public function yayinla(Request $request): JsonResponse
    {
        $this->tema->yayinla($this->girdi($request));

        return response()->json(['basarili' => true, 'mesaj' => 'Tema yayınlandı.']);
    }

    public function taslakSil(): JsonResponse
    {
        $this->tema->taslakSil();

        return response()->json(['basarili' => true, 'degerler' => $this->tema->duzenleyiciDegerleri()]);
    }

    /** Varsayılan değerleri taslağa yükler; yayınlanana kadar site etkilenmez. */
    public function sifirla(): JsonResponse
    {
        $varsayilan = $this->tema->varsayilanlar();
        $this->tema->taslakKaydet($varsayilan);

        return response()->json(['basarili' => true, 'degerler' => $varsayilan]);
    }

    public function disaAktar(): StreamedResponse
    {
        $json = json_encode($this->tema->birlestir($this->tema->yayinlanan()), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return response()->streamDownload(fn () => print ($json), 'tema-'.now()->format('Y-m-d').'.json', [
            'Content-Type' => 'application/json',
        ]);
    }

    public function iceAktar(Request $request): RedirectResponse
    {
        $request->validate(['dosya' => 'required|file|max:2048']);

        $veri = json_decode((string) file_get_contents($request->file('dosya')->getRealPath()), true);

        if (! is_array($veri)) {
            return back()->withErrors(['dosya' => 'Geçerli bir tema JSON dosyası seçin.']);
        }

        $this->tema->taslakKaydet($this->tema->birlestir($this->tema->temizle($veri)));

        return redirect()->route('yonetim.tema')->with('basari', 'Tema içe aktarıldı. İnceleyip "Yayınla" ile kaydedin.');
    }

    protected function girdi(Request $request): array
    {
        $degerler = $request->input('degerler');

        if (is_string($degerler)) {
            $degerler = json_decode($degerler, true);
        }

        abort_unless(is_array($degerler), 422, 'Geçersiz tema verisi.');

        return $this->tema->birlestir($this->tema->temizle($degerler));
    }
}
