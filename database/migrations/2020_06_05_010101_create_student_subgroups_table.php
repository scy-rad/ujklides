<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentSubgroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('student_subgroups', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('student_group_id');
            $table->string('subgroup_name');
            $table->smallInteger('write_technician_character')->default('0');
            $table->smallInteger('subgroup_status')->default(1);
            $table->timestamps();
        });


        Schema::table('student_subgroups', function (Blueprint $table) {
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
        Schema::dropIfExists('student_subgroups');
    }
}
