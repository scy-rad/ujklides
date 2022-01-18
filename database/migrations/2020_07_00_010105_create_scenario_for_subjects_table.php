<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScenarioForSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

		Schema::create('scenario_for_subjects', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('scenario_id');
			$table->unsignedInteger('student_subject_id');
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
		
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::dropIfExists('scenario_for_subjects');
    }
}
