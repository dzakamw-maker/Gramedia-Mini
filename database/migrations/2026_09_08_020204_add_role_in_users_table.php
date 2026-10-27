<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // default : membuat kolom role memiliki nilai default 'user' jika tidak diisi saat pembuatan data user bar
            $table->enum('role', ['admin', 'user'])->default('user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // dropColumn : menghapus kolom role dari tabel users jika migrasi dibatalkan
            $table->dropColumn('role');
        });
    }
};
