<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mesaj extends Model
{
    protected $table = 'mesajlar';

    protected $fillable = ['ad', 'soyad', 'eposta', 'telefon', 'mesaj', 'ip', 'okundu'];

    protected function casts(): array
    {
        return [
            'okundu' => 'boolean',
        ];
    }

    public function tamAd(): string
    {
        return trim($this->ad.' '.$this->soyad);
    }
}
