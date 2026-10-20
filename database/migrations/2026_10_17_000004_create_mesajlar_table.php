<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mesajlar', function (Blueprint $table) {
            $table->id();
            $table->string('ad', 100);
            $table->string('soyad', 100)->nullable();
            $table->string('eposta');
            $table->string('telefon', 40)->nullable();
            $table->text('mesaj');
            $table->string('ip', 45)->nullable();
            $table->boolean('okundu')->default(false)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mesajlar');
    }
};
