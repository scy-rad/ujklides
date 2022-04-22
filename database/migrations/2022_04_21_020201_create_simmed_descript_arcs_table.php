<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSimmedDescriptArcsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('simmed_descript_arcs', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('simmed_descript_id')->unsigned();
            $table->text('simmed_descript');
            $table->string('simmed_secret')->nullable();
            $table->Integer('user_id')->unsigned();
            $table->timestamps();
            });

        Schema::table('simmed_descript_arcs', function (Blueprint $table) {
            $table->foreign('simmed_descript_id')
               ->references('id')
                ->on('simmed_descripts');
            });
        Schema::table('simmed_descript_arcs', function (Blueprint $table) {
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
        Schema::dropIfExists('simmed_descript_arcs');
        
    }
}
