<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInternalReceivesTable extends Migration
{
    /**le
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internal_receives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('to_location_id')->nullable();
            $table->unsignedBigInteger('gatepass_check_id');
            $table->unsignedBigInteger('received_by')->nullable();
            $table->timestamp('received_date_time')->nullable();
            $table->tinyInteger('status')->comment('0=Full Received,1=Short Received,2=Excess Received');
            $table->string('tag', 20)->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            
            $table->foreign('to_location_id')->references('id')->on('locations')->onDelete('set null');
            $table->foreign('gatepass_check_id')->references('id')->on('gatechecks')->onDelete('cascade');
            $table->foreign('received_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('internal_receives');
    }
}
