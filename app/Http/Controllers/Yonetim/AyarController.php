<?php

namespace App\Http\Controllers\Yonetim;

use App\Http\Controllers\Controller;
use App\Models\Ayar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** Tema dışındaki site ayarları. */
class AyarController extends Controller
{
    public function index(): View
    {
        return view('yonetim.ayarlar', [
            'bildirimEposta' => ayar('bildirim_eposta', ''),
        ]);
    }

    public function kaydet(Request $request): RedirectResponse
    {
        $veri = $request->validate([
            'bildirim_eposta' => 'nullable|email|max:190',
        ]);

        Ayar::yaz('bildirim_eposta', $veri['bildirim_eposta'] ?? '');

        return back()->with('basari', 'Ayarlar kaydedildi.');
    }
}
