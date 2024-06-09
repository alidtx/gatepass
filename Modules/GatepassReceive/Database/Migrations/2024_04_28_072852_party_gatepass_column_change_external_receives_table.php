<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PartyGatepassColumnChangeExternalReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Remove foreign key constraint
        Schema::table('external_receives', function (Blueprint $table) {
            $table->dropForeign(['party_id']);
        });

        // Drop index associated with party_id column
        Schema::table('external_receives', function (Blueprint $table) {
            $table->dropIndex('external_receives_party_id_foreign');
        });

        // Rename party_id column to party and change its data type
        Schema::table('external_receives', function (Blueprint $table) {
            $table->renameColumn('party_id', 'party');
        });

        Schema::table('external_receives', function (Blueprint $table) {
            $table->string('party', 100)->nullable()->change();
            $table->renameColumn('gatepass_no', 'gatepass_no_from_party');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Rename columns back to their original names and types
        Schema::table('external_receives', function (Blueprint $table) {
            $table->renameColumn('party', 'party_id');
        });

        Schema::table('external_receives', function (Blueprint $table) {
            $table->unsignedBigInteger('party_id')->nullable()->change();
            $table->renameColumn('gatepass_no_from_party', 'gatepass_no');
        });

        // Add foreign key constraint back
        Schema::table('external_receives', function (Blueprint $table) {
            $table->foreign('party_id')->references('id')->on('parties')->onDelete('set null');
        });
    }
}
