<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pengembalians', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pinjaman_id');
            $table->string('nama');
            $table->string('kelas');
            $table->string('judul_buku');
            $table->date('tanggal_kembali')->nullable();
            $table->date('tanggal_pengembalian');
            $table->integer('keterlambatan')->default(0);
            $table->integer('denda')->default(0);
            $table->timestamps();

            $table->foreign('pinjaman_id')->references('id')->on('pinjamans')->onDelete('cascade');
            $table->index('pinjaman_id');
            $table->index('nama');
            $table->index('kelas');
            $table->index('tanggal_pengembalian');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengembalians');
    }
};