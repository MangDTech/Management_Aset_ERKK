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
        Schema::create('history_dendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('denda_id')->constrained()->onDelete('cascade');
            $table->string('status');
            $table->string('payment_type')->nullable();
            $table->string('order_id');
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
        Schema::dropIfExists('history_dendas');
    }
};
