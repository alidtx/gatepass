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
        Schema::create('wastage_carrying_labors', function (Blueprint $table) {
            $table->id();
            $table->string('date')->nullable();
            $table->string('entry')->nullable();
            $table->string('name')->nullable();
            $table->string('company')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('issue_card')->nullable();
            $table->string('return_card')->nullable();
            $table->string('signature')->nullable();
            $table->string('outter')->nullable();
            $table->string('Siki_signature')->nullable();
            $table->string('Siki_officer_signature')->nullable();
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
        Schema::dropIfExists('wastage_carrying_labors');
    }
};
