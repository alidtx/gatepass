<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('department_id')->nullable()->after('user_type');
            $table->unsignedBigInteger('user_source_id')->nullable()->after('department_id');

            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            $table->foreign('user_source_id')->references('id')->on('user_sources')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('users', 'department_id')) //check the column
        {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['department_id']);
                $table->dropColumn('department_id');
            });
        }

        if (Schema::hasColumn('users', 'user_source_id')) //check the column
        {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['user_source_id']);
                $table->dropColumn('user_source_id');
            });
        }
    }
}
