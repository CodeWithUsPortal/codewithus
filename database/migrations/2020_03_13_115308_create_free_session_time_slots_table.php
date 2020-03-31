<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFreeSessionTimeSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('free_session_time_slots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('day_id');
            $table->unsignedBigInteger('time_id');
            $table->unsignedBigInteger('location_id');
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();

            $table->foreign('day_id')->references('id')->on('days');
            $table->foreign('time_id')->references('id')->on('times');
            $table->foreign('location_id')->references('id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('free_session_time_slots');
    }
}
