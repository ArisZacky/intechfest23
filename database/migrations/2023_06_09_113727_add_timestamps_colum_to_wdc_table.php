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
        Schema::table('wdc', function (Blueprint $table) {
            $table->string('foto', 100)->after('id_peserta');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wdc', function (Blueprint $table) {
            $table->dropColumn('foto');
            $table->dropTimestamps();
        });
    }
};
