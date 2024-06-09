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
        Schema::create('medical_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('date')->nullable();
            $table->string('out_time')->nullable();
            $table->string('gate_pass_no')->nullable();
            $table->string('party_name')->nullable();
            $table->string('goods_name')->nullable();
            $table->string('unit')->nullable();
            $table->string('qty')->nullable();
            $table->string('security_sign')->nullable();
            $table->string('security_officer_sign')->nullable();
            $table->string('comment')->nullable();
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
        Schema::dropIfExists('medical_rooms');
    }
};
