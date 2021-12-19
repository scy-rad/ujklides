<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSimmedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('simmeds', function (Blueprint $table) {
            $table->increments('id');
            $table->date('simmed_date');
            $table->time('simmed_time_begin');
			$table->time('simmed_time_end');
            $table->smallInteger('simmed_type_id')->default(1);
            $table->string('simmed_alternative_title')->default('');
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
        });

        Schema::create('simmed_arcs', function (Blueprint $table) {
            $table->increments('id');
            $table->date('simmed_date');
            $table->time('simmed_time_begin');
			$table->time('simmed_time_end');
            $table->smallInteger('simmed_type_id')->default(1);
            $table->string('simmed_alternative_title')->default('');
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
            $table->smallInteger('change_code')->default(0);
        });


		//type:
        // 1 - zajęcia ze studentami
        // 2 - szkolenie
        // 3 - prace serwisowe
        // 4 - wyłączenie z użytkowania

        // status
        // 1 - rezerwacja
        // 2 - potwierdzone
		// 3 - zrealizowane
        // 4 - odwołane

        // technician_character_id
        // 1 - przygotowanie
        // 2 - dyżur
        // 3 - obecność

		Schema::create('student_subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('student_subject_name')->default('');
            $table->string('student_subject_name_en')->default('');
            $table->smallInteger('student_subject_status')->default(1);
            $table->timestamps();
        });

		Schema::create('student_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('student_group_name');
            $table->string('student_group_code')->default('');
            $table->unsignedInteger('center_id')->nullable();
            $table->smallInteger('student_group_status')->default(1);
            $table->timestamps();
        });

        Schema::create('student_subgroups', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('student_group_id');
            $table->string('subgroup_name');
            $table->smallInteger('subgroup_status')->default(1);
            $table->timestamps();
        });

        Schema::table('student_groups', function (Blueprint $table) {
            $table->foreign('center_id')
                ->references('id')
                ->on('centers');
        });

        Schema::table('student_subgroups', function (Blueprint $table) {
            $table->foreign('student_group_id')
                ->references('id')
                ->on('student_groups');
        });


		Schema::table('simmeds', function (Blueprint $table) {
            $table->foreign('student_subject_id')
                ->references('id')
                ->on('student_subjects');
        });

		Schema::table('simmeds', function (Blueprint $table) {
            $table->foreign('student_group_id')
                ->references('id')
                ->on('student_groups');
        });

        Schema::table('simmeds', function (Blueprint $table) {
            $table->foreign('room_id')
                ->references('id')
                ->on('rooms');
        });
        Schema::table('simmeds', function (Blueprint $table) {
            $table->foreign('simmed_leader_id')
                ->references('id')
                ->on('users');
        });
        Schema::table('simmeds', function (Blueprint $table) {
            $table->foreign('simmed_technician_id')
                ->references('id')
                ->on('users');
        });


        Schema::create('simmed_temps', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('import_number');
            $table->text('import_row');
            $table->unsignedInteger('simmed_id')->default(0);
            $table->unsignedInteger('simmed_tmp_id');
            $table->date('simmed_date');
            $table->time('simmed_time_begin');
			$table->time('simmed_time_end');
            $table->smallInteger('simmed_type_id')->default(1);
            $table->string('simmed_alternative_title')->default('');

            $table->unsignedInteger('student_subject_id')->nullable();
            $table->unsignedInteger('student_group_id')->nullable();
            $table->unsignedInteger('student_subgroup_id')->nullable();
            $table->unsignedInteger('room_id');
            $table->unsignedInteger('simmed_leader_id')->nullable();
            $table->unsignedInteger('simmed_technician_id')->nullable();
            $table->unsignedInteger('simmed_technician_character_id')->nullable();

            $table->string('student_subject_txt')->default('');
            $table->string('student_group_txt')->default('');
            $table->string('student_subgroup_txt')->default('');
            $table->string('room_xp_txt')->default('');
            $table->string('room_xls_txt')->default('');
            $table->string('simmed_leader_txt')->default('');

            $table->unsignedInteger('simmed_merge')->default(0);
            $table->smallInteger('tmp_status')->default(0);
            $table->timestamps();
        });

        Schema::create('simmed_temp_rooms', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('room_id');
            $table->date('simmed_date_begin');
			$table->date('simmed_date_end');
            $table->integer('import_count');
            $table->integer('import_number');
            $table->smallInteger('import_status')->default(0); //0-w trakcie; 1-skończony

            $table->timestamps();
        });

        Schema::table('simmed_temp_rooms', function (Blueprint $table) {
            $table->foreign('room_id')
                ->references('id')
                ->on('rooms');
        });


        Schema::create('simmed_arc_technicians', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('simmed_id');
            $table->unsignedInteger('technician_id')->nullable();
            $table->unsignedInteger('user_id');
            $table->timestamps();
        });
        Schema::table('simmed_arc_technicians', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });


        Schema::create('simmed_temp_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->mediumtext('post_data');
            $table->timestamps();
        });



        Schema::create('technician_characters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('character_name')->default('');
            $table->string('character_short')->default('');
            $table->string('character_ico')->default('');
            $table->timestamps();
        });
        Schema::table('simmeds', function (Blueprint $table) {
            $table->foreign('simmed_technician_character_id')
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
        Schema::dropIfExists('simmeds');
        Schema::dropIfExists('simmed_arcs');
        Schema::dropIfExists('student_subgroups');
		Schema::dropIfExists('student_groups');
        Schema::dropIfExists('student_subjects');
        Schema::dropIfExists('simmed_temps');
        Schema::dropIfExists('simmed_temp_rooms');
        Schema::dropIfExists('simmed_arc_technicians');
        Schema::dropIfExists('simmed_temp_posts');
        Schema::dropIfExists('technician_characters');

    }
}
