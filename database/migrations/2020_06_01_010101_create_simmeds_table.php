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
            $table->unsignedInteger('user_id');
            $table->timestamps();
        });


		//simmed_type_id:
        // 1 - zajęcia ze studentami
        // 2 - szkolenie
        // 3 - prace serwisowe
        // 4 - wyłączenie z użytkowania

        // simmed_status
        //   aktualny opis w modelu
        // 1 - zaimportowane
        // 2 - 
		// 3 - 
        // 4 - odwołane
        // 5 - dopisane (nie usuwane podczas importu)

        Schema::table('simmeds', function (Blueprint $table) {
            $table->foreign('simmed_technician_character_id')
                ->references('id')
                ->on('technician_characters');
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
        Schema::table('simmeds', function (Blueprint $table) {
            $table->foreign('user_id')
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
        Schema::dropIfExists('simmeds');
    }
}
