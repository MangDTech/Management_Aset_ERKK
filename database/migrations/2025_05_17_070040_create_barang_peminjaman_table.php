<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('barang_peminjaman', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('peminjam_id');
            $table->unsignedBigInteger('barang_id');
            $table->integer('jumlah');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_pengembalian')->nullable();
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->timestamps();

            $table->foreign('peminjam_id')->references('id')->on('peminjams')->onDelete('cascade');
            $table->foreign('barang_id')->references('id')->on('barangs')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('barang_peminjaman');
    }
};