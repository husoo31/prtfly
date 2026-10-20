<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\Kurulum;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

/** Paylaşımlı hostinge yüklenen paket için web tabanlı kurulum sihirbazı. */
class KurulumController extends Controller
{
    public function form(Request $request): View
    {
        return view('kurulum', [
            'gereksinimler' => $this->gereksinimler(),
            'varsayilanUrl' => rtrim($request->root(), '/'),
        ]);
    }

    public function kur(Request $request): RedirectResponse
    {
        $veri = $request->validate([
            'site_url' => 'required|url|max:190',
            'db_tur' => 'required|in:sqlite,mysql',
            'db_host' => 'required_if:db_tur,mysql|nullable|string|max:190',
            'db_port' => 'nullable|integer|min:1|max:65535',
            'db_ad' => 'required_if:db_tur,mysql|nullable|string|max:190',
            'db_kullanici' => 'required_if:db_tur,mysql|nullable|string|max:190',
            'db_sifre' => 'nullable|string|max:190',
            'ad' => 'required|string|max:100',
            'eposta' => 'required|email|max:190',
            'sifre' => 'required|string|min:8|confirmed',
        ]);

        $env = [
            'APP_URL' => rtrim($veri['site_url'], '/'),
            'APP_ENV' => 'production',
            'APP_DEBUG' => 'false',
            'DB_CONNECTION' => $veri['db_tur'],
        ];

        if ($veri['db_tur'] === 'mysql') {
            $env += [
                'DB_HOST' => $veri['db_host'],
                'DB_PORT' => (string) ($veri['db_port'] ?: 3306),
                'DB_DATABASE' => $veri['db_ad'],
                'DB_USERNAME' => $veri['db_kullanici'],
                'DB_PASSWORD' => (string) ($veri['db_sifre'] ?? ''),
            ];

            config([
                'database.default' => 'mysql',
                'database.connections.mysql.host' => $env['DB_HOST'],
                'database.connections.mysql.port' => $env['DB_PORT'],
                'database.connections.mysql.database' => $env['DB_DATABASE'],
                'database.connections.mysql.username' => $env['DB_USERNAME'],
                'database.connections.mysql.password' => $env['DB_PASSWORD'],
            ]);
        } else {
            config(['database.default' => 'sqlite']);
        }

        try {
            DB::purge();
            Kurulum::veritabaniHazirla();
            DB::connection()->getPdo();
        } catch (Throwable $e) {
            return back()->withInput()->withErrors(['db_tur' => 'Veritabanına bağlanılamadı: '.$e->getMessage()]);
        }

        try {
            Kurulum::envYaz($env);
            Artisan::call('migrate', ['--force' => true]);

            if ($request->boolean('demo')) {
                Artisan::call('db:seed', ['--force' => true]);
            }

            $kullanici = User::query()->updateOrCreate(
                ['email' => $veri['eposta']],
                ['name' => $veri['ad'], 'password' => $veri['sifre']],
            );

            Kurulum::tamamlandiIsaretle();
        } catch (Throwable $e) {
            return back()->withInput()->withErrors(['genel' => 'Kurulum tamamlanamadı: '.$e->getMessage()]);
        }

        Auth::login($kullanici);
        $request->session()->regenerate();

        return redirect()->route('yonetim.panel')->with('basari', 'Kurulum tamamlandı, hoş geldiniz!');
    }

    protected function gereksinimler(): array
    {
        $yazilabilir = fn ($yol) => is_writable($yol);

        return [
            'PHP 8.3 veya üstü' => version_compare(PHP_VERSION, '8.3.0', '>='),
            'PDO eklentisi' => extension_loaded('pdo'),
            'PDO MySQL / SQLite' => extension_loaded('pdo_mysql') || extension_loaded('pdo_sqlite'),
            'Mbstring eklentisi' => extension_loaded('mbstring'),
            'OpenSSL eklentisi' => extension_loaded('openssl'),
            'Fileinfo eklentisi' => extension_loaded('fileinfo'),
            '.env yazılabilir' => is_file(base_path('.env')) ? $yazilabilir(base_path('.env')) : $yazilabilir(base_path()),
            'storage/ yazılabilir' => $yazilabilir(storage_path()),
            'bootstrap/cache/ yazılabilir' => $yazilabilir(base_path('bootstrap/cache')),
            'public/uploads/ yazılabilir' => $yazilabilir(public_path(config('portfolyo.yukleme_klasoru'))),
            'database/ yazılabilir (SQLite için)' => $yazilabilir(database_path()),
        ];
    }
}
