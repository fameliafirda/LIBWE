<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            if (!Schema::hasColumn('books', 'rak_id')) {
                $table->foreignId('rak_id')->nullable()->after('kategori_id')->constrained('raks')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            if (Schema::hasColumn('books', 'rak_id')) {
                $table->dropForeign(['rak_id']);
                $table->dropColumn('rak_id');
            }
        });
    }
};