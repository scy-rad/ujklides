<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
 
class CreateWorkTimeToHrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_time_to_hrs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('work_time_types_id')->unsigned();
            $table->date('date');
            $table->time('time_begin')->nullable();
			$table->time('time_end')->nullable();
            $table->integer('minutes')->nullable();
            $table->smallInteger('over_under')->default(0); // 0 - nothing, 1 - over, 2 - under
            $table->time('o_time_begin')->nullable();
			$table->time('o_time_end')->nullable();
            $table->time('o_time_begin2')->nullable();
			$table->time('o_time_end2')->nullable();
            $table->integer('o_minutes')->nullable();
            $table->text('description')->nullable();
            $table->smallInteger('status')->default(1); // 1 - active
            $table->timestamps();
        });

            Schema::table('work_time_to_hrs', function (Blueprint $table) {
                $table->foreign('work_time_types_id')
                    ->references('id')
                    ->on('work_time_types');
            });

            Schema::table('work_time_to_hrs', function (Blueprint $table) {
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
        Schema::table('work_time_to_hrs', function (Blueprint $table) {
            $table->dropForeign(['work_time_types_id']);
        });
        Schema::table('work_time_to_hrs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('work_time_to_hrs');        
    }
}
