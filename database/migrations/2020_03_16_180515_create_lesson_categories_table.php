<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedBigInteger('created_by');
            $table->integer('priority')->default(1);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();

//            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lesson_categories');
    }
}
