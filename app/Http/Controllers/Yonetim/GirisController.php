<?php

namespace App\Http\Controllers\Yonetim;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class GirisController extends Controller
{
    public function form(): View
    {
        return view('yonetim.giris');
    }

    public function giris(Request $request): RedirectResponse
    {
        $bilgiler = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (! Auth::attempt($bilgiler, $request->boolean('hatirla'))) {
            return back()->withErrors(['email' => 'E-posta veya şifre hatalı.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('yonetim.panel'));
    }

    public function cikis(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('yonetim.giris');
    }
}
