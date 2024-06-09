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
        Schema::create('security_duty_registers', function (Blueprint $table) {
            $table->id();
            $table->string('id_no')->nullable();
            $table->string('rank')->nullable();
            $table->string('name')->nullable();
            $table->string('post')->nullable();
            $table->string('from')->nullable();
            $table->string('to')->nullable();
            $table->string('hours')->nullable();
            $table->string('sat')->nullable();
            $table->string('sun')->nullable();
            $table->string('mon')->nullable();
            $table->string('tues')->nullable();
            $table->string('wed')->nullable();
            $table->string('thu')->nullable();
            $table->string('friday')->nullable();
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
        Schema::dropIfExists('security_duty_registers');
    }
};
