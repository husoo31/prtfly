<?php

namespace App\Http\Controllers\Yonetim;

use App\Http\Controllers\Controller;
use App\Models\BlogYazisi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $ara = trim((string) $request->query('ara', ''));

        $yazilar = BlogYazisi::query()
            ->when($ara !== '', fn ($q) => $q->where('baslik', 'like', '%'.$ara.'%'))
            ->orderByDesc('yayin_tarihi')->orderByDesc('id')
            ->paginate(20)->withQueryString();

        return view('yonetim.blog.index', compact('yazilar', 'ara'));
    }

    public function create(Request $request): View
    {
        return view('yonetim.blog.form', [
            'yazi' => new BlogYazisi([
                'aktif' => true,
                'yayin_tarihi' => now(),
                'yazar' => $request->user()->name,
            ]),
            'kategoriler' => $this->kategoriler(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        BlogYazisi::create($this->dogrula($request));

        return redirect()->route('yonetim.blog.index')->with('basari', 'Yazı eklendi.');
    }

    public function edit(BlogYazisi $yazi): View
    {
        return view('yonetim.blog.form', [
            'yazi' => $yazi,
            'kategoriler' => $this->kategoriler(),
        ]);
    }

    public function update(Request $request, BlogYazisi $yazi): RedirectResponse
    {
        $yazi->update($this->dogrula($request));

        return redirect()->route('yonetim.blog.index')->with('basari', 'Yazı güncellendi.');
    }

    public function destroy(BlogYazisi $yazi): RedirectResponse
    {
        $yazi->delete();

        return redirect()->route('yonetim.blog.index')->with('basari', 'Yazı silindi.');
    }

    protected function dogrula(Request $request): array
    {
        $veri = $request->validate([
            'baslik' => 'required|string|max:190',
            'slug' => 'nullable|string|max:190',
            'kategori' => 'nullable|string|max:100',
            'kapak' => 'nullable|string|max:500',
            'ozet' => 'nullable|string|max:1000',
            'icerik' => 'nullable|string|max:100000',
            'yazar' => 'nullable|string|max:100',
            'etiketler' => 'nullable|string|max:190',
            'yayin_tarihi' => 'nullable|date',
        ]);

        $veri['aktif'] = $request->boolean('aktif');

        return $veri;
    }

    protected function kategoriler(): array
    {
        return BlogYazisi::query()->whereNotNull('kategori')->distinct()->orderBy('kategori')->pluck('kategori')->all();
    }
}
