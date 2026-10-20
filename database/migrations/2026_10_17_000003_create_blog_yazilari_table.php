<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_yazilari', function (Blueprint $table) {
            $table->id();
            $table->string('baslik');
            $table->string('slug')->unique();
            $table->string('kategori')->nullable();
            $table->string('kapak')->nullable();
            $table->text('ozet')->nullable();
            $table->longText('icerik')->nullable();
            $table->string('yazar')->nullable();
            $table->string('etiketler')->nullable();
            $table->timestamp('yayin_tarihi')->nullable()->index();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_yazilari');
    }
};
