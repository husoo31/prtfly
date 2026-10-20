<?php

namespace App\Http\Controllers\Yonetim;

use App\Http\Controllers\Controller;
use App\Models\Mesaj;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MesajController extends Controller
{
    public function index(Request $request): View
    {
        $filtre = $request->query('filtre');

        $mesajlar = Mesaj::query()
            ->when($filtre === 'okunmamis', fn ($q) => $q->where('okundu', false))
            ->latest()
            ->paginate(25)->withQueryString();

        return view('yonetim.mesajlar.index', compact('mesajlar', 'filtre'));
    }

    public function show(Mesaj $mesaj): View
    {
        if (! $mesaj->okundu) {
            $mesaj->update(['okundu' => true]);
        }

        return view('yonetim.mesajlar.goster', compact('mesaj'));
    }

    public function destroy(Mesaj $mesaj): RedirectResponse
    {
        $mesaj->delete();

        return redirect()->route('yonetim.mesajlar.index')->with('basari', 'Mesaj silindi.');
    }
}
