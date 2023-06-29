<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('ct', function (Blueprint $table) {
            $table->unsignedBigInteger('id_transaksi')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('ct', function (Blueprint $table) {
            $table->unsignedBigInteger('id_transaksi')->change();
        });
    }
};