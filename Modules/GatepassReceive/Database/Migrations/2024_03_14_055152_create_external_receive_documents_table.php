<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExternalReceiveDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('external_receive_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('external_receive_id')->nullable();
            $table->string('document_name', 100);
            $table->string('document', 100);
            $table->timestamps();

            $table->foreign('external_receive_id')->references('id')->on('external_receives')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('external_receive_documents');
    }
}
