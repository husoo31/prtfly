<?php

namespace App\Http\Controllers\Yonetim;

use App\Http\Controllers\Controller;
use App\Models\Proje;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProjeController extends Controller
{
    public function index(Request $request): View
    {
        $ara = trim((string) $request->query('ara', ''));

        $projeler = Proje::query()
            ->when($ara !== '', fn ($q) => $q->where('baslik', 'like', '%'.$ara.'%')->orWhere('kategori', 'like', '%'.$ara.'%'))
            ->orderBy('sira')->orderByDesc('id')
            ->paginate(20)->withQueryString();

        return view('yonetim.projeler.index', compact('projeler', 'ara'));
    }

    public function create(): View
    {
        return view('yonetim.projeler.form', [
            'proje' => new Proje(['aktif' => true, 'boyut' => 'normal', 'sira' => (int) Proje::max('sira') + 1]),
            'kategoriler' => Proje::tumKategoriler(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Proje::create($this->dogrula($request));

        return redirect()->route('yonetim.projeler.index')->with('basari', 'Proje eklendi.');
    }

    public function edit(Proje $proje): View
    {
        return view('yonetim.projeler.form', [
            'proje' => $proje,
            'kategoriler' => Proje::tumKategoriler(),
        ]);
    }

    public function update(Request $request, Proje $proje): RedirectResponse
    {
        $proje->update($this->dogrula($request));

        return redirect()->route('yonetim.projeler.index')->with('basari', 'Proje güncellendi.');
    }

    public function destroy(Proje $proje): RedirectResponse
    {
        $proje->delete();

        return redirect()->route('yonetim.projeler.index')->with('basari', 'Proje silindi.');
    }

    protected function dogrula(Request $request): array
    {
        $veri = $request->validate([
            'baslik' => 'required|string|max:190',
            'slug' => 'nullable|string|max:190',
            'kategori' => 'nullable|string|max:190',
            'musteri' => 'nullable|string|max:190',
            'tarih' => 'nullable|string|max:100',
            'kapak' => 'nullable|string|max:500',
            'gorsel_1' => 'nullable|string|max:500',
            'gorsel_2' => 'nullable|string|max:500',
            'ozet' => 'nullable|string|max:1000',
            'icerik' => 'nullable|string|max:20000',
            'maddeler' => 'nullable|string|max:5000',
            'baglanti' => 'nullable|url|max:500',
            'boyut' => ['required', Rule::in(array_keys(Proje::BOYUTLAR))],
            'sira' => 'nullable|integer|min:0|max:100000',
        ]);

        $veri['aktif'] = $request->boolean('aktif');
        $veri['sira'] = (int) ($veri['sira'] ?? 0);

        return $veri;
    }
}
