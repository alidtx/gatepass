<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExternalReceiveItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('external_receive_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('external_receive_id');
            $table->unsignedBigInteger('item_id')->nullable();
            $table->float('document_qty', 10,2);
            $table->float('received_qty', 10,2);
            $table->timestamps();

            $table->foreign('external_receive_id')->references('id')->on('external_receives')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('external_receive_items');
    }
}
