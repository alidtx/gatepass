<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGatechecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gatechecks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from_location_id')->nullable();
            $table->unsignedBigInteger('released_by')->nullable();
            $table->timestamp('release_date_time')->nullable();
            $table->unsignedBigInteger('gatepass_id')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0=Created,1=Release,2=Held');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('from_location_id')->references('id')->on('locations')->onDelete('set null');
            $table->foreign('released_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('gatepass_id')->references('id')->on('gatepasses')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gatechecks');
    }
}
