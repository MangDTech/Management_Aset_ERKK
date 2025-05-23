<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('barang_peminjaman', function (Blueprint $table) {
            $table->unsignedBigInteger('kbarang_id')->nullable()->after('barang_id');
        });
    }
    public function down()
    {
        Schema::table('barang_peminjaman', function (Blueprint $table) {
            $table->dropColumn('kbarang_id');
        });
    }
};
