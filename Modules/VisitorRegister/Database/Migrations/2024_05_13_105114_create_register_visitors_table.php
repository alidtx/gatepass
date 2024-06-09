<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegisterVisitorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitor_registers', function (Blueprint $table) {
            $table->id();
            $table->string('visit_date')->nullable();
            $table->string('visit_reason')->nullable();
            $table->string('visitor_name')->nullable();
            $table->string('visitor_def')->nullable();
            $table->string('visitor_address')->nullable();
            $table->string('visitor_contact')->nullable();
            $table->string('in_time')->nullable();
            $table->string('out_time')->nullable();
            $table->string('visitor_issue_id')->nullable();
            $table->integer('photo_id')->nullable();
            $table->integer('body')->nullable();
            $table->integer('bag')->nullable();
            $table->integer('ok')->nullable();
            $table->integer('no')->nullable();
            $table->string('return')->nullable();
            $table->string('visitor')->nullable();
            $table->string('received_by')->nullable();
            $table->string('incharge')->nullable();
            $table->string('admin_sign')->nullable();
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
        Schema::dropIfExists('visitor_registers');
    }
}
