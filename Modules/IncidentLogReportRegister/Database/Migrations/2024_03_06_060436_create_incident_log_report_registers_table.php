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
        Schema::create('incident_log_report_registers', function (Blueprint $table) {
            $table->id();
            $table->string('date')->nullable();
            $table->string('description')->nullable();
            $table->string('report_name')->nullable();
            $table->string('signature')->nullable();
            $table->string('step')->nullable();
            $table->string('security_officer')->nullable();
            $table->string('approved_officer')->nullable();
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
        Schema::dropIfExists('incident_Log_Report_Registers');
    }
};
