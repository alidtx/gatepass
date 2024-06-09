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
        Schema::create('keylock_checks', function (Blueprint $table) {
            $table->id();
            $table->string('date')->nullable();
            $table->string('num_of_key')->nullable();;
            $table->string('okey')->nullable();;
            $table->string('broken')->nullable();;
            $table->string('missing')->nullable();;
            $table->string('additional')->nullable();;
            $table->string('action')->nullable();;
            $table->string('authorized_by')->nullable();;
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
        Schema::dropIfExists('keylock_checks');
    }
};
