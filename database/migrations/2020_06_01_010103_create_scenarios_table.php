<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScenariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		// 1 - pacjent symulowany
        // 2 - symulator pacjenta
        // 4 - trenażer
        // 8 - bezsprzętowa


		Schema::create('scenarios', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('scenario_author_id');
			$table->smallInteger('scenario_type');
            $table->string('scenario_name');
            $table->string('scenario_code');
			$table->string('scenario_main_problem');
			$table->text('scenario_description');
			$table->smallInteger('scenario_status')->default(1);	
            $table->timestamps();
        });

		Schema::create('scenario_for_subjects', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('scenario_id');
			$table->unsignedInteger('student_subject_id');
			$table->timestamps();
		});

		Schema::create('scenario_for_simmeds', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('scenario_id');
			$table->unsignedInteger('simmed_id');
			$table->timestamps();
		});


		Schema::table('scenario_for_subjects', function (Blueprint $table) {
            $table->foreign('student_subject_id')
                ->references('id')
                ->on('student_subjects');
		});
		
		Schema::table('scenario_for_subjects', function (Blueprint $table) {
            $table->foreign('scenario_id')
                ->references('id')
                ->on('scenarios');
		});
		
		Schema::table('scenario_for_simmeds', function (Blueprint $table) {
            $table->foreign('simmed_id')
                ->references('id')
                ->on('simmeds');
		});
		
		Schema::table('scenario_for_simmeds', function (Blueprint $table) {
            $table->foreign('scenario_id')
                ->references('id')
                ->on('scenarios');
        });

		
        Schema::table('scenarios', function (Blueprint $table) {
            $table->foreign('scenario_author_id')
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
        Schema::dropIfExists('scenario_for_simmeds');
		Schema::dropIfExists('scenario_for_subjects');
		Schema::dropIfExists('scenarios');
    }
}
