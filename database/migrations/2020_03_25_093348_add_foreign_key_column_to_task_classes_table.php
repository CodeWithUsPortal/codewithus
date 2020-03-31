<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyColumnToTaskClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('task_classes', function (Blueprint $table) {
            $table->unsignedBigInteger('task_class_type_id')->nullable()->default(null)->after('location_id');
            $table->foreign('task_class_type_id')->references('id')->on('task_classes');
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
