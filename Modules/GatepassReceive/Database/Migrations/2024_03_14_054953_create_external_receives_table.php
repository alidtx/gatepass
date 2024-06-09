<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExternalReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('external_receives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('to_location_id')->nullable();
            $table->unsignedBigInteger('received_by')->nullable();
            $table->timestamp('receive_date_time')->nullable();
            $table->unsignedBigInteger('party_id')->nullable();
            $table->string('receive_no', 50)->unique();
            $table->string('gatepass_no', 50)->nullable();
            $table->string('challan_no', 50)->nullable();
            $table->unsignedBigInteger('to_person_id')->nullable();
            $table->tinyInteger('status')->comment('0=Full Received,1=Short Received,2=Excess Received');
            $table->string('tag', 20)->nullable();
            $table->text('note');
            $table->timestamps();
            $table->softDeletes();
            
            
            $table->foreign('to_location_id')->references('id')->on('locations')->onDelete('set null');
            $table->foreign('party_id')->references('id')->on('parties')->onDelete('set null');
            $table->foreign('received_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('to_person_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('external_receives');
    }
}
