<?php

use App\Helper\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnPasswordToLectureCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lecture_categories', function (Blueprint $table) {
            $table->string('password')
                ->nullable()
                ->default(null)
                ->unique()->after('name');
        });

        //created default password for existing categories
        $ls = \App\LectureCategory::all();
        foreach($ls as $l) {
            $l->password = Helper::generateUniqueValue('lecture_categories', 'password');
            $l->save();
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lecture_categories', function (Blueprint $table) {
            $table->dropColumn('password');
        });
    }
}
