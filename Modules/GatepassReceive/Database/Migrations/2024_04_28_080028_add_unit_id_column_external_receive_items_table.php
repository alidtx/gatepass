<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUnitIdColumnExternalReceiveItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('external_receive_items', function (Blueprint $table) {
            $table->unsignedBigInteger('item_description_id')->nullable()->after('item_id');
            $table->unsignedBigInteger('unit_id')->nullable()->after('item_description_id');

            $table->foreign('item_description_id')
            ->references('id')
            ->on('item_descriptions')
            ->onDelete('set null');
            

            $table->foreign('unit_id')
            ->references('id')
            ->on('units')
            ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('external_receive_items', 'item_description_id')) //check the column
        {
            Schema::table('external_receive_items', function (Blueprint $table) {
                $table->dropForeign(['item_description_id']);
                $table->dropColumn('item_description_id');
            });
        }

        if (Schema::hasColumn('external_receive_items', 'unit_id')) //check the column
        {
            Schema::table('external_receive_items', function (Blueprint $table) {
                $table->dropForeign(['unit_id']);
                $table->dropColumn('unit_id');
            });
        }
    }
}
