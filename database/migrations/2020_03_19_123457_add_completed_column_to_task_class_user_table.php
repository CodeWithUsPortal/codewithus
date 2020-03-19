<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompletedColumnToTaskClassUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('task_class_user', function (Blueprint $table) {
            $table->unsignedBigInteger('completed')->nullable()->default(null)->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('task_class_user', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
}
