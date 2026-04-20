<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pinjamans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kelas');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('judul_buku');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali')->nullable();
            $table->enum('status', ['belum dikembalikan', 'sudah dikembalikan'])->default('belum dikembalikan');
            $table->integer('denda')->default(0);
            $table->unsignedBigInteger('anggota_id')->nullable();
            $table->unsignedBigInteger('buku_id')->nullable();
            $table->timestamps();

            $table->foreign('anggota_id')->references('id')->on('anggotas')->onDelete('cascade');
            $table->foreign('buku_id')->references('id')->on('books')->onDelete('cascade');

            $table->index('status');
            $table->index('buku_id');
            $table->index('tanggal_kembali');
            $table->index('tanggal_pinjam');
        });
    }

    public function down(): void
    {
        Schema::table('pinjamans', function (Blueprint $table) {
            $table->dropForeign(['anggota_id']);
            $table->dropForeign(['buku_id']);
        });
        Schema::dropIfExists('pinjamans');
    }
};