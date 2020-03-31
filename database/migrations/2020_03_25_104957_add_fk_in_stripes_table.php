<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFkInStripesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stripes', function (Blueprint $table) {
            $table->unsignedBigInteger('task_class_type_id')->nullable()->default(null);
            $table->unsignedBigInteger('location_id')->nullable()->default(null);

            $table->foreign('task_class_type_id')->references('id')->on('task_classes');
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
        //
    }
}
