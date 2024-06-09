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
        Schema::create('id_missing_registers', function (Blueprint $table) {
            $table->id();
            $table->string('date_time')->nullable();
            $table->string('card_type')->nullable();
            $table->string('card_no')->nullable();
            $table->string('reporter_name')->nullable();
            $table->string('reporter_sign')->nullable();
            $table->string('card_release_date')->nullable();
            $table->string('security_officer_sign')->nullable();
            $table->string('admin_manager_sign')->nullable();
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
        Schema::dropIfExists('id_missing_registers');
    }
};
