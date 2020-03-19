<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermanentClassSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permanent_class_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('day');
            $table->time('time');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('location_id');
            $table->boolean('is_deleted')->default(false);
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
        Schema::dropIfExists('permanent_class_schedules');
    }
}
