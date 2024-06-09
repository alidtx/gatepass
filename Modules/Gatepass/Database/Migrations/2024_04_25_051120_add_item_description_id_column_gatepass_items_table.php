<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemDescriptionIdColumnGatepassItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gatepass_items', function (Blueprint $table) {
            $table->unsignedBigInteger('item_description_id')->nullable()->after('item_id');

            $table->foreign('item_description_id')
            ->references('id')
            ->on('item_descriptions')
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
        if (Schema::hasColumn('gatepass_items', 'item_description_id')) //check the column
        {
            Schema::table('gatepass_items', function (Blueprint $table) {
                $table->dropForeign(['item_description_id']);
                $table->dropColumn('item_description_id');
            });
        }
    }
}
