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
        Schema::create('security_patrol_duties', function (Blueprint $table) {
            $table->id();
            $table->string('date')->nullable();
            $table->string('name')->nullable();
            $table->string('designation')->nullable();
            $table->string('from')->nullable();
            $table->string('to')->nullable();
            $table->integer('is_permital_ok')->nullable();
            $table->integer('is_flatlight_ok')->nullable();
            $table->integer('is_camera_ok')->nullable();
            $table->integer('is_window_ok')->nullable();
            $table->integer('is_security_gaurd_ok')->nullable();
            $table->string('permital_box')->nullable();
            $table->string('flatlight_box')->nullable();
            $table->string('camera_box')->nullable();
            $table->string('window_box')->nullable();
            $table->string('security_box')->nullable();
            $table->string('duty_description')->nullable();
            $table->string('security_name')->nullable();
            $table->string('security_officer_name')->nullable();
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
        Schema::dropIfExists('security_patrol_duties');
    }
};
