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
            $table->renameColumn('nama_anggota', 'nama_team');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ctf', function (Blueprint $table) {
            $table->renameColumn('nama_team', 'nama_anggota');
        });
    }
};
