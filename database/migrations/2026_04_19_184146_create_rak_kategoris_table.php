<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rak_kategoris', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rak_id')->constrained('raks')->onDelete('cascade');
            $table->foreignId('kategori_id')->constrained('categories')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['rak_id', 'kategori_id']);
            $table->index('rak_id');
            $table->index('kategori_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rak_kategoris');
    }
};