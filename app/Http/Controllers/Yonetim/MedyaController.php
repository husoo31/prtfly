<?php

namespace App\Http\Controllers\Yonetim;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MedyaController extends Controller
{
    protected const UZANTILAR = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'ico'];

    /** Görseli public/uploads/YYYY/MM altına kaydeder. SVG güvenlik nedeniyle kabul edilmez. */
    public function yukle(Request $request): JsonResponse
    {
        $request->validate([
            'dosya' => 'required|file|mimes:'.implode(',', self::UZANTILAR).'|max:'.config('portfolyo.yukleme_sinir_kb'),
        ], [
            'dosya.mimes' => 'Yalnızca JPG, PNG, GIF, WEBP veya ICO yükleyebilirsiniz.',
        ]);

        $dosya = $request->file('dosya');
        $klasor = config('portfolyo.yukleme_klasoru').'/'.now()->format('Y/m');
        File::ensureDirectoryExists(public_path($klasor));

        $ad = Str::slug(pathinfo($dosya->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'gorsel';
        $ad = Str::limit($ad, 40, '').'-'.Str::lower(Str::random(6)).'.'.strtolower($dosya->extension());
        $dosya->move(public_path($klasor), $ad);

        $yol = $klasor.'/'.$ad;

        return response()->json(['yol' => $yol, 'url' => asset($yol)]);
    }

    /** Son yüklenen görseller (görsel seçici penceresi için). */
    public function liste(): JsonResponse
    {
        $kokKlasor = config('portfolyo.yukleme_klasoru');
        $kok = public_path($kokKlasor);

        if (! is_dir($kok)) {
            return response()->json(['dosyalar' => []]);
        }

        $dosyalar = collect(File::allFiles($kok))
            ->filter(fn ($d) => in_array(strtolower($d->getExtension()), self::UZANTILAR, true))
            ->sortByDesc(fn ($d) => $d->getMTime())
            ->take(120)
            ->map(function ($d) use ($kokKlasor) {
                $yol = $kokKlasor.'/'.str_replace(DIRECTORY_SEPARATOR, '/', $d->getRelativePathname());

                return ['yol' => $yol, 'url' => asset($yol), 'ad' => $d->getFilename()];
            })
            ->values();

        return response()->json(['dosyalar' => $dosyalar]);
    }
}
