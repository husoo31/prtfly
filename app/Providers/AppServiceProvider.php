<?php

namespace App\Providers;

use App\Support\Kurulum;
use App\Support\Tema;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Tema::class);

        if (! $this->app->runningUnitTests()) {
            Kurulum::anahtarHazirla();
        }
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();
        Carbon::setLocale(config('app.locale'));
    }
}
