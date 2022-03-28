<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
 
class CreateWorkMonthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_months', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->date('work_month');
            $table->integer('hours_to_work');
            $table->integer('hours_worked')->default(0);
            $table->smallInteger('calculated')->default(0); // 0 - no calculated, 1 - calculated
            $table->timestamps();
        });

            Schema::table('work_months', function (Blueprint $table) {
                $table->foreign('user_id')
                    ->references('id')
                    ->on('users');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('work_months', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('work_months');        
    }
}
