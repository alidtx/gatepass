<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsExternalReceivesTable extends Migration
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
            $table->dropForeign(['to_person_id']);
        });

        // Drop index associated with party_id column
        Schema::table('external_receives', function (Blueprint $table) {
            $table->dropIndex('external_receives_to_person_id_foreign');
        });

        // Rename party_id column to party and change its data type
        Schema::table('external_receives', function (Blueprint $table) {
            $table->renameColumn('to_person_id', 'to_person');
        });

        Schema::table('external_receives', function (Blueprint $table) {
            $table->string('to_person', 100)->nullable()->change();
            $table->unsignedBigInteger('to_department_id')->nullable()->after('to_person');

            $table->foreign('to_department_id')->references('id')->on('departments')->onDelete('set null');
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
            $table->renameColumn('to_person', 'to_person_id');
            $table->dropForeign(['to_department_id']);
        });

        Schema::table('external_receives', function (Blueprint $table) {
            $table->unsignedBigInteger('to_person_id')->nullable()->change();
            $table->dropColumn('to_department_id');
        });

        // Add foreign key constraint back
        Schema::table('external_receives', function (Blueprint $table) {
            $table->foreign('to_person_id')->references('id')->on('users')->onDelete('set null');
        });
    }
}
