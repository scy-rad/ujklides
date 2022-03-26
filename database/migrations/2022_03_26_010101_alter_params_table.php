<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterParamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('params', function($table) {
            $table->date('statistics_start')->default('2022-02-20');
            $table->smallInteger('simmed_days_edit_back')->default('3');
			$table->smallInteger('worktime_days_edit_back')->default('3');
		});

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('params', function($table) {
            $table->dropColumn('statistics_start');
            $table->dropColumn('simmed_days_edit_back');
			$table->dropColumn('worktime_days_edit_back');
        });
    }
}
