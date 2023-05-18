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
        Schema::create('peserta', function (Blueprint $table) {
            $table->id('id_peserta');
            $table->string('email', 60)->unique();
            $table->string('nomer_peserta', 10);
            $table->string('nama_lengkap', 70);
            $table->string('alamat', 100);
            $table->string('nama_instansi', 100);
            $table->string('no_hp', 16);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peserta');
    }
};
