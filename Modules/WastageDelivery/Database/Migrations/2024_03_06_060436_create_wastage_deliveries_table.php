<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wastage_deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('date')->nullable();
            $table->string('name')->nullable();
            $table->string('from')->nullable();
            $table->string('till')->nullable();
            $table->string('jhoot')->nullable();
            $table->string('carton')->nullable();
            $table->string('poly')->nullable();
            $table->string('chot')->nullable();
            $table->string('comment')->nullable();
            $table->string('jhoot_kg')->nullable();
            $table->string('carton_kg')->nullable();
            $table->string('poly_kg')->nullable();
            $table->string('chot_kg')->nullable();
            $table->string('pipe')->nullable();
            $table->string('pipe_kg')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wastage_deliveries');
    }
};
