<?php

namespace App\Http\Middleware;

use App\Support\Kurulum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Kurulum tamamlanmadıysa her isteği kurulum sihirbazına yönlendirir. */
class KurulumKontrol
{
    public function handle(Request $request, Closure $next): Response
    {
        $kurulumSayfasi = $request->is('kurulum', 'kurulum/*');

        if (! Kurulum::kurulduMu() && ! $kurulumSayfasi) {
            return redirect('/kurulum');
        }

        if (Kurulum::kurulduMu() && $kurulumSayfasi) {
            return redirect('/');
        }

        return $next($request);
    }
}
