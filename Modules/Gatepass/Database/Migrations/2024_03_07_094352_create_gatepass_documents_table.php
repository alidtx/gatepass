<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGatepassDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gatepass_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gatepass_id')->nullable();
            $table->string('document_name');
            $table->string('document');
            $table->timestamps();

            $table->foreign('gatepass_id')->references('id')->on('gatepasses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gatepass_documents');
    }
}
