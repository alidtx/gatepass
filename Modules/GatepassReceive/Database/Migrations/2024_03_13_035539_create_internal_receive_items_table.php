<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInternalReceiveItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internal_receive_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('internal_receive_id');
            $table->unsignedBigInteger('item_id')->nullable();
            $table->float('received_qty',10,2);
            $table->timestamps();

            $table->foreign('internal_receive_id')->references('id')->on('internal_receives')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('gatepass_items')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('internal_receive_items');
    }
}
