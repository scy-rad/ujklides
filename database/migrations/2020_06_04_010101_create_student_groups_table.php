<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

		Schema::create('student_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('student_group_name');
            $table->string('student_group_code')->default('');
            $table->unsignedInteger('center_id')->nullable();
            $table->smallInteger('write_technician_character_default')->default('0');
            $table->smallInteger('student_group_status')->default(1);
            $table->timestamps();
        });


        Schema::table('student_groups', function (Blueprint $table) {
            $table->foreign('center_id')
                ->references('id')
                ->on('centers');
        });


		Schema::table('simmeds', function (Blueprint $table) {
            $table->foreign('student_group_id')
                ->references('id')
                ->on('student_groups');
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
            $table->dropForeign(['student_group_id']);
        });
        Schema::dropIfExists('student_groups');
        

    }
}
