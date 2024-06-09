<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGatepassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gatepasses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('type_id')->nullable();
            $table->string('gate_pass_no')->nullable();
            $table->unsignedBigInteger('to_location_id')->nullable();
            $table->unsignedBigInteger('party_id')->nullable();
            $table->timestamp('creation_datetime')->nullable();
            $table->string('challan_no')->unique()->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('created_by_department')->nullable();
            $table->text('note')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0=created,1=updated,2=approved,3=rejected,4=finally-approved');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approval_datetime')->nullable();
            $table->unsignedBigInteger('second_approved_by')->nullable();
            $table->timestamp('second_approval_datetime')->nullable();
            $table->string('tag', 20)->nullable();
            $table->unsignedBigInteger('to_person_id')->nullable();
            $table->unsignedBigInteger('to_person_department_id')->nullable();
            $table->string('external_to_person')->nullable();
            $table->string('mobile')->nullable();
            $table->string('purpose')->nullable();
            $table->string('vehicle_no')->nullable();
            $table->unsignedBigInteger('from_location_id')->nullable();
            $table->string('carrying_person')->nullable();
            $table->unsignedBigInteger('party_challan_no')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('type_id')->references('id')->on('gatepass_types')->onDelete('set null');
            $table->foreign('to_location_id')->references('id')->on('locations')->onDelete('set null');
            $table->foreign('party_id')->references('id')->on('parties')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('second_approved_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by_department')->references('id')->on('departments')->onDelete('set null');
            $table->foreign('to_person_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('to_person_department_id')->references('id')->on('departments')->onDelete('set null');
            $table->foreign('from_location_id')->references('id')->on('locations')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gatepasses');
    }
}
