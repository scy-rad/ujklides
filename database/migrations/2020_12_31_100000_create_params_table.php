<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
 
class CreateParamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('params', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('leader_for_simmed')->unsigned();
            $table->integer('technician_for_simmed')->unsigned();
            //$table->string('plik_type_menu');
            //$table->smallInteger('plik_type_sort')->default(100);
            $table->timestamps();
        });

            Schema::table('params', function (Blueprint $table) {
                $table->foreign('leader_for_simmed')
                    ->references('id')
                    ->on('users');
            });

            Schema::table('params', function (Blueprint $table) {
                $table->foreign('technician_for_simmed')
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
        Schema::dropIfExists('params');        
    }
}
