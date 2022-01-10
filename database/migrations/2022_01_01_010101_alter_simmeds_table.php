<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSimmedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('simmeds', function($table) {
            $table->date('send_simmed_date')->default('2022-01-01');
            $table->time('send_simmed_time_begin')->default('00:00:01');
			$table->time('send_simmed_time_end')->default('00:00:02');
            $table->smallInteger('send_simmed_type_id')->default('0');
			$table->unsignedInteger('send_student_subject_id')->default('0');
            $table->unsignedInteger('send_student_group_id')->default('0');
            $table->unsignedInteger('send_student_subgroup_id')->default('0');
            $table->unsignedInteger('send_room_id')->default('0');
            $table->unsignedInteger('send_simmed_leader_id')->default('0');
            $table->unsignedInteger('send_simmed_technician_id')->default('0');
            $table->unsignedInteger('send_simmed_technician_character_id')->default('0');
            $table->smallInteger('send_simmed_status')->default('0');
			$table->smallInteger('send_simmed_status2')->default('0');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('simmeds', function($table) {
            $table->dropColumn('send_simmed_date');
            $table->dropColumn('send_simmed_time_begin');
			$table->dropColumn('send_simmed_time_end');
            $table->dropColumn('send_simmed_type_id');
			$table->dropColumn('send_student_subject_id');
            $table->dropColumn('send_student_group_id');
            $table->dropColumn('send_student_subgroup_id');
            $table->dropColumn('send_room_id');
            $table->dropColumn('send_simmed_leader_id');
            $table->dropColumn('send_simmed_technician_id');
            $table->dropColumn('send_simmed_technician_character_id');
            $table->dropColumn('send_simmed_status');
			$table->dropColumn('send_simmed_status2');
        

        });
    }
}
