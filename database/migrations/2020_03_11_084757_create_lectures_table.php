<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLecturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lectures', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('link');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('sub_category_id');
            $table->integer('priority')->default(1);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('sub_category_id')->references('id')->on('lecture_sub_categories');
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
