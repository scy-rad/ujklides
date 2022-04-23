<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
 
class CreateWorkTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_times', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('work_time_types_id')->unsigned();
            $table->date('date');
            $table->time('time_begin');
			$table->time('time_end');
            $table->text('description')->nullable();
            $table->smallInteger('status')->default(1); // 1 - active
            $table->timestamps();
        });

            Schema::table('work_times', function (Blueprint $table) {
                $table->foreign('work_time_types_id')
                    ->references('id')
                    ->on('work_time_types');
            });

            Schema::table('work_times', function (Blueprint $table) {
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
        Schema::table('work_times', function (Blueprint $table) {
            $table->dropForeign(['work_time_types_id']);
        });
        Schema::table('work_times', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('work_times');        
    }
}
