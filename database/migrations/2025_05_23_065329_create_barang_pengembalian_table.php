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
            Schema::create('barang_pengembalian', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('peminjam_id');
                $table->unsignedBigInteger('barang_id');
                $table->unsignedBigInteger('kbarang_id')->nullable();
                $table->integer('jumlah');
                $table->date('tanggal_pinjam');
                $table->date('tanggal_pengembalian');
                $table->string('status')->default('diproses');
                $table->timestamps();

                // Foreign key (pastikan nama tabel benar!)
                $table->foreign('peminjam_id')->references('id')->on('peminjams');
                $table->foreign('barang_id')->references('id')->on('barangs');
                $table->foreign('kbarang_id')->references('id')->on('kbarangs');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('barang_pengembalian');
    }
};
