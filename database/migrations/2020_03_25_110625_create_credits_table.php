<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('remaining_credits')->nullable();
            $table->date('credits_given_date');
            $table->string('customer_email');
            $table->string('payment_id');
            $table->string('product_id');
            $table->unsignedBigInteger('task_class_type_id')->nullable()->default(null);
            $table->timestamps();

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
