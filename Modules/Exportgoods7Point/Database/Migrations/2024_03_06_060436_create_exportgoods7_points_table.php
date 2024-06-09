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
        Schema::create('exportgoods7_points', function (Blueprint $table) {
            $table->id();
            $table->string('out_date')->nullable();
            $table->string('out_time')->nullable();
            $table->string('in_time')->nullable();
            $table->string('loading_purpose')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('driver_licence_no')->nullable();
            $table->string('bolt_seal_no')->nullable();
            $table->string('vehicale_no')->nullable();
            $table->string('vehicle_type')->nullable();
            $table->string('loading_item_name')->nullable();
            $table->string('suplier_company_name')->nullable();
            $table->string('destination_from')->nullable();
            $table->string('clean_inside_cover_van')->nullable();
            $table->string('points7_check')->nullable();
            $table->string('transport_fitness')->nullable();
            $table->string('checked_by_door')->nullable();
            $table->string('bolt_locked_officer_name')->nullable();
            $table->string('shipment_officer_signature')->nullable();
            $table->string('sy_off')->nullable();
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
        Schema::dropIfExists('exportgoods7_points');
    }
};
