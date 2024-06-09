<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGatepassItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gatepass_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gatepass_id')->nullable();
            $table->unsignedBigInteger('item_id')->nullable();
            $table->float('qty', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('gatepass_id')->references('id')->on('gatepasses')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gatepass_items');
    }
}
