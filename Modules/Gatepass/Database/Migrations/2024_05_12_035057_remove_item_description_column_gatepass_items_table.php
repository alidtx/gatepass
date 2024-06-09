<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveItemDescriptionColumnGatepassItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gatepass_items', function (Blueprint $table) {
            $table->dropForeign(['item_description_id']);
            $table->dropColumn('item_description_id');

            $table->string('item_description')->nullable()->after('item_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gatepass_items', function (Blueprint $table) {
            $table->unsignedBigInteger('item_description_id')->nullable()->after('item_id');

            $table->foreign('item_description_id')
            ->references('id')
            ->on('item_descriptions')
            ->onDelete('set null');

            $table->dropColumn('item_description');
        });
    }
}
