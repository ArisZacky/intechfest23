<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('ct', function (Blueprint $table) {
            $table->string('nomer_peserta', 10)->after('id_peserta');
        });
    }


    public function down()
    {
        Schema::table('ct', function (Blueprint $table) {
            $table->dropColumn('nomer_peserta');
        });
    }

};