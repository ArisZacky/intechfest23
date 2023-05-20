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
        Schema::create('dc', function (Blueprint $table) {
            $table->id('id_dc');
            $table->enum('validasi',['Belum Tervalidasi', 'Sudah Valid', 'Tidak Valid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dc');
    }
};
