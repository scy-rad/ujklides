<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSimmedDescriptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('simmed_descripts', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('simmed_id')->unsigned();
            $table->text('simmed_descript');
            $table->string('simmed_secret')->nullable();
            $table->Integer('user_id')->unsigned();
            $table->timestamps();
            });

        Schema::table('simmed_descripts', function (Blueprint $table) {
            $table->foreign('simmed_id')
               ->references('id')
                ->on('simmeds');
            });
        Schema::table('simmed_descripts', function (Blueprint $table) {
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
        Schema::dropIfExists('simmed_descripts'); 
    }
}
