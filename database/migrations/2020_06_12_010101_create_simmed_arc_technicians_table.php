<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSimmedArcTechniciansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


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



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('simmed_arc_technicians');
    }
}
