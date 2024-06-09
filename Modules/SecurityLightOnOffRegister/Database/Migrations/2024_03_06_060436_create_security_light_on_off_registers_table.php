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
        Schema::create('security_light_on_off_registers', function (Blueprint $table) {
            $table->id();
            $table->string('date')->nullable();
            $table->string('designation')->nullable();
            $table->string('name')->nullable();
            $table->string('on_time')->nullable();
            $table->string('off_time')->nullable();
            $table->string('security_signature')->nullable();
            $table->string('security_officer_signature')->nullable();
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
        Schema::dropIfExists('security_light_on_off_registers');
    }
};
