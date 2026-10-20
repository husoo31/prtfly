<?php

namespace App\Http\Controllers\Yonetim;

use App\Http\Controllers\Controller;
use App\Models\BlogYazisi;
use App\Models\Mesaj;
use App\Models\Proje;
use Illuminate\View\View;

class PanelController extends Controller
{
    public function index(): View
    {
        return view('yonetim.panel', [
            'sayilar' => [
                'proje' => Proje::count(),
                'yazi' => BlogYazisi::count(),
                'mesaj' => Mesaj::count(),
                'okunmamis' => Mesaj::where('okundu', false)->count(),
            ],
            'sonMesajlar' => Mesaj::latest()->limit(5)->get(),
            'sonYazilar' => BlogYazisi::latest('yayin_tarihi')->limit(5)->get(),
        ]);
    }
}
