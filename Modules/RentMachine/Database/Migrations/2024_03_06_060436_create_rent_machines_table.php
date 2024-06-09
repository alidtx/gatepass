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
        Schema::create('rent_machines', function (Blueprint $table) {
            $table->id();
            $table->string('date')->nullable();
            $table->string('time')->nullable();
            $table->string('gatepass_no')->nullable();
            $table->string('challan_no')->nullable();
            $table->string('party')->nullable();
            $table->string('in_date')->nullable();
            $table->string('times')->nullable();
            $table->string('machine_name')->nullable();
            $table->string('sl_no')->nullable();
            $table->string('qty')->nullable();
            $table->string('security_sign')->nullable();
            $table->string('depositor_signature')->nullable();
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
        Schema::dropIfExists('rent_machines');
    }
};
