<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
 
class AlterWorkMonthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('work_months', function($table) {
            $table->integer('minutes_to_work');
            $table->integer('minutes_worked')->default(0);
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('work_months', function($table) {
            $table->dropColumn('minutes_to_work');
            $table->dropColumn('minutes_worked')->default(0);
        });        
    }
}
