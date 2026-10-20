<?php

namespace App\Http\Controllers\Yonetim;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfilController extends Controller
{
    public function index(Request $request): View
    {
        return view('yonetim.profil', ['kullanici' => $request->user()]);
    }

    public function kaydet(Request $request): RedirectResponse
    {
        $kullanici = $request->user();

        $veri = $request->validate([
            'name' => 'required|string|max:100',
            'email' => ['required', 'email', 'max:190', Rule::unique('users', 'email')->ignore($kullanici->id)],
            'mevcut_sifre' => 'nullable|required_with:password|current_password',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $kullanici->name = $veri['name'];
        $kullanici->email = $veri['email'];

        if (! empty($veri['password'])) {
            $kullanici->password = $veri['password'];
        }

        $kullanici->save();

        return back()->with('basari', 'Profil güncellendi.');
    }
}
