<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('raks', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('nomor')->unique();
            $table->text('deskripsi')->nullable();
            $table->string('warna')->default('#ffffff');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('raks');
    }
};