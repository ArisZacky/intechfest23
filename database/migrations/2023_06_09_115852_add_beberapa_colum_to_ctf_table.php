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
        Schema::table('ctf', function (Blueprint $table) {
            $table->string('anggota1', 60)->after('id_transaksi');
            $table->string('foto_1', 100)->after('anggota1');
            $table->string('anggota2', 60)->after('foto_1');
            $table->string('foto_2', 100)->after('anggota2');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ctf', function (Blueprint $table) {
            $table->dropColumn('anggota1');
            $table->dropColumn('foto_1');
            $table->dropColumn('anggota2');
            $table->dropColumn('foto_2');
            $table->dropTimestamps();
        });
    }
};
