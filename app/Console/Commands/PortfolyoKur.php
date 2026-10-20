<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Support\Kurulum;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

/** Komut satırından kurulum: tabloları oluşturur, demo içerik ve yönetici ekler. */
class PortfolyoKur extends Command
{
    protected $signature = 'portfolyo:kur
        {--ad= : Yönetici adı}
        {--eposta= : Yönetici e-postası}
        {--sifre= : Yönetici şifresi}
        {--demo=1 : Demo içerik yüklensin mi (1/0)}';

    protected $description = 'Portfolyo yazılımını kurar (tablolar, demo içerik, yönetici hesabı)';

    public function handle(): int
    {
        $this->components->info('Portfolyo kurulumu başlıyor');

        Kurulum::veritabaniHazirla();
        $this->call('migrate', ['--force' => true]);

        if ($this->option('demo') !== '0') {
            $this->call('db:seed', ['--force' => true]);
        }

        if (User::query()->doesntExist()) {
            $ad = $this->option('ad') ?: $this->ask('Yönetici adı', 'Yönetici');
            $eposta = $this->option('eposta') ?: $this->ask('Yönetici e-postası');
            $sifre = $this->option('sifre') ?: $this->secret('Yönetici şifresi (en az 8 karakter)');

            $dogrulama = Validator::make(compact('ad', 'eposta', 'sifre'), [
                'ad' => 'required|string|max:100',
                'eposta' => 'required|email|max:190',
                'sifre' => 'required|string|min:8',
            ]);

            if ($dogrulama->fails()) {
                foreach ($dogrulama->errors()->all() as $hata) {
                    $this->components->error($hata);
                }

                return self::FAILURE;
            }

            User::create(['name' => $ad, 'email' => $eposta, 'password' => $sifre]);
            $this->components->info("Yönetici oluşturuldu: {$eposta}");
        }

        Kurulum::tamamlandiIsaretle();
        $this->components->info('Kurulum tamamlandı. Yönetim paneli: '.url('/yonetim'));

        return self::SUCCESS;
    }
}
