<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

		Schema::create('student_subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('student_subject_name')->default('');
            $table->string('student_subject_name_en')->default('');
            $table->smallInteger('student_subject_status')->default(1);
            $table->timestamps();
        });


		Schema::table('simmeds', function (Blueprint $table) {
            $table->foreign('student_subject_id')
                ->references('id')
                ->on('student_subjects');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('simmeds', function (Blueprint $table) {
            $table->dropForeign(['student_subject_id']);
        });
        Schema::dropIfExists('student_subjects');

    }
}
