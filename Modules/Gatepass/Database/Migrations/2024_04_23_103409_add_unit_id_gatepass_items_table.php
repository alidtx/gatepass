<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUnitIdGatepassItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gatepass_items', function (Blueprint $table) {
            $table->unsignedBigInteger('unit_id')->nullable()->after('item_id');

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
        if (Schema::hasColumn('gatepass_items', 'unit_id')) //check the column
        {
            Schema::table('gatepass_items', function (Blueprint $table) {
                $table->dropForeign(['unit_id']);
                $table->dropColumn('unit_id');
            });
        }
    }
}
