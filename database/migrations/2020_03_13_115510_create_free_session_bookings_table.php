<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFreeSessionBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('free_session_bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('location_id')->nullable();
            $table->string('student_name');
            $table->integer('student_age')->nullable();
            $table->string('phone_number');
            $table->string('email')->nullable();
            $table->unsignedBigInteger('topic_id')->nullable();
            $table->string('ad_source');
            $table->unsignedBigInteger('free_session_time_slot_id')->nullable();
            $table->text('expectations');
            $table->timestamps();
     
            $table->foreign('location_id')->references('id')->on('locations');
            $table->foreign('topic_id')->references('id')->on('topics');
            $table->foreign('free_session_time_slot_id')->references('id')->on('free_session_time_slots');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('free_session_bookings');
    }
}
