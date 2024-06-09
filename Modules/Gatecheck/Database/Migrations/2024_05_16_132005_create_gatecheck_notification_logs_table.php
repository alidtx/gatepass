<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGatecheckNotificationLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gatecheck_notification_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gatecheck_id');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->foreign('gatecheck_id')->references('id')->on('gatechecks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gatecheck_notification_logs');
    }
}
