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
        Schema::create('dendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjam_id')->constrained()->onDelete('cascade');
            $table->integer('jumlah_denda');
            $table->string('keterangan')->nullable();
            $table->string('status')->default('belum_dibayar'); // status pembayaran
            $table->string('snap_token')->nullable(); // dari Midtrans
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dendas');
    }
};
