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
        Schema::table('ct', function (Blueprint $table) {
            $table->unsignedBigInteger('id_peserta')->after('id_ct');
            $table->foreign('id_peserta')->references('id_peserta')->on('peserta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ct', function (Blueprint $table) {
            $table->dropForeign(['id_peserta']);
            $table->dropColumn('id_peserta');
        });
    }
};
