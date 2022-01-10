<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSimmedTempsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

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

            $table->string('simmed_trap')->default('');
        });

        // Schema::create('simmed_temp_rooms', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->unsignedInteger('room_id');
        //     $table->date('simmed_date_begin');
		// 	$table->date('simmed_date_end');
        //     $table->integer('import_count');
        //     $table->integer('import_number');
        //     $table->smallInteger('import_status')->default(0); //0-w trakcie; 1-skończony

        //     $table->timestamps();
        // });

        // Schema::table('simmed_temp_rooms', function (Blueprint $table) {
        //     $table->foreign('room_id')
        //         ->references('id')
        //         ->on('rooms');
        // });


        // Schema::create('simmed_temp_posts', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->mediumtext('post_data');
        //     $table->timestamps();
        // });



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('simmed_temps');
        // Schema::dropIfExists('simmed_temp_rooms');
        // Schema::dropIfExists('simmed_temp_posts');

    }
}
