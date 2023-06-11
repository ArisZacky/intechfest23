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
        Schema::table('peserta', function (Blueprint $table) {
            $table->string('nomer_peserta', 10)->nullable()->change();
            $table->string('alamat', 100)->nullable()->change();
            $table->string('nama_instansi', 100)->nullable()->change();
            $table->string('no_hp', 16)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peserta', function (Blueprint $table) {
            $table->string('nomer_peserta', 10)->nullable(false)->change();
            $table->string('alamat', 100)->nullable(false)->change();
            $table->string('nama_instansi', 100)->nullable(false)->change();
            $table->string('no_hp', 16)->nullable(false)->change();
        });
    }
};
