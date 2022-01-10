<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStudentsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::table('student_groups', function($table) {
			$table->smallInteger('write_technician_character_default')->default('0');
        });

        Schema::table('student_subgroups', function (Blueprint $table) {
            $table->smallInteger('write_technician_character')->default('0');
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->unsignedInteger('simmed_technician_character_propose_id')->default('1');
        });

        Schema::table('simmed_temps', function (Blueprint $table) {
            $table->unsignedInteger('simmed_technician_character_propose_id')->default('0');
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->foreign('simmed_technician_character_propose_id')
                ->references('id')
                ->on('technician_characters');
        });



    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_groups', function($table) {
            $table->dropColumn('write_technician_character_default_id');
        });

        Schema::table('student_subgroups', function($table) {
            $table->dropColumn('write_technician_character_id');
        });

        Schema::table('rooms', function($table) {
            $table->dropColumn('simmed_technician_character_propose_id');
        });

    }
}
