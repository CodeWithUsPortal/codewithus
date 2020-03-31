<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddressColumnToLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->text('address')->nullable()->default(null)->after('location_name');
        });

        Schema::table('task_classes', function (Blueprint $table) {
            $table->boolean('is_free_session')->default(0)->after('location_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn('address');
        });

        Schema::table('task_classes', function (Blueprint $table) {
            $table->dropColumn('is_free_session');
        });
    }
}
