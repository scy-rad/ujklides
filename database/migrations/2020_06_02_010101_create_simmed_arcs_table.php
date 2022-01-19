<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSimmedArcsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('simmed_arcs', function (Blueprint $table) {
            $table->increments('id');
            $table->date('simmed_date');
            $table->time('simmed_time_begin');
			$table->time('simmed_time_end');
            $table->smallInteger('simmed_type_id')->default(1);
            $table->string('simmed_alternative_title')->nullable();
			$table->unsignedInteger('student_subject_id')->nullable();
            $table->unsignedInteger('student_group_id')->nullable();
            $table->unsignedInteger('student_subgroup_id')->nullable();
            $table->unsignedInteger('room_id');
            $table->unsignedInteger('simmed_leader_id')->nullable();
            $table->unsignedInteger('simmed_technician_id')->nullable();
            $table->unsignedInteger('simmed_technician_character_id')->nullable();
            $table->smallInteger('simmed_status')->default(1);
			$table->smallInteger('simmed_status2')->default(1);
            $table->timestamps();
            $table->unsignedInteger('simmed_id');
            $table->unsignedInteger('user_id');
            $table->smallInteger('change_code')->default(0);
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('simmed_arcs');
    }
}
