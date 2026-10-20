<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projeler', function (Blueprint $table) {
            $table->id();
            $table->string('baslik');
            $table->string('slug')->unique();
            $table->string('kategori')->nullable();
            $table->string('musteri')->nullable();
            $table->string('tarih')->nullable();
            $table->string('kapak')->nullable();
            $table->string('gorsel_1')->nullable();
            $table->string('gorsel_2')->nullable();
            $table->text('ozet')->nullable();
            $table->longText('icerik')->nullable();
            $table->text('maddeler')->nullable();
            $table->string('baglanti')->nullable();
            $table->string('boyut', 20)->default('normal');
            $table->integer('sira')->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projeler');
    }
};
