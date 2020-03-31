<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonSubCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_sub_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('lesson_category_id');
            $table->integer('priority')->default(1);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();

//            $table->foreign('created_by')->references('id')->on('users');
//            $table->foreign('lesson_category_id')->references('id')->on('lesson_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lesson_sub_categories');
    }
}
