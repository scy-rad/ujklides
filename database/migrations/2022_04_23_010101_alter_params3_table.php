<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterParams3Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('params', function($table) {
            $table->time('worktime_time_begin')->default('07:30');
            $table->time('worktime_time_end')->default('15:30');
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
            $table->dropColumn('worktime_time_begin');
            $table->dropColumn('worktime_time_end');
        });
    }
}
