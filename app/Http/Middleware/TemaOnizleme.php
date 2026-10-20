<?php

namespace App\Http\Middleware;

use App\Support\Tema;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * ?onizleme=1 ile açılan ve oturum açmış yöneticiye ait isteklerde temayı
 * taslak değerlerle çizer. Tema düzenleyicideki iframe bu modu kullanır.
 */
class TemaOnizleme
{
    public function handle(Request $request, Closure $next): Response
    {
        $onizleme = $request->boolean('onizleme') && $request->user() !== null;

        app(Tema::class)->onizlemeModu($onizleme);

        $yanit = $next($request);

        if ($onizleme) {
            $yanit->headers->set('Cache-Control', 'no-store, private');
            $yanit->headers->set('X-Robots-Tag', 'noindex');
        }

        return $yanit;
    }
}
