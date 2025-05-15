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
        Schema::create('otps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('peminjam_id'); // Foreign key ke tabel peminjams
            $table->string('otp_code'); // Kolom untuk menyimpan kode OTP
            $table->timestamp('expires_at');
            $table->timestamps();

            //relasi ke tabel peminjams
            $table->foreign('peminjam_id')->references('id')->on('peminjams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('otps');
    }
};
