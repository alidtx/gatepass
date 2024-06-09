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
        Schema::create('key_controls', function (Blueprint $table) {
            $table->id();
            $table->string('date')->nullable();
            $table->string('deliver_time')->nullable();
            $table->string('number_of_key')->nullable();
            $table->string('type')->nullable();
            $table->string('provider_name')->nullable();
            $table->string('provider_designation')->nullable();
            $table->string('provider_signature')->nullable();
            $table->string('reciever_name')->nullable();
            $table->string('reciever_designation')->nullable();
            $table->string('reciever_signature')->nullable();
            $table->string('security_officer')->nullable();
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
        Schema::dropIfExists('key_controls');
    }
};
