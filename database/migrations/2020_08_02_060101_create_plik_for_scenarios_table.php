<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
 
class CreatePlikForScenariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plik_for_scenarios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('plik_id')->unsigned();
            $table->integer('scenario_id')->unsigned();
            $table->timestamps();
        });

                Schema::table('plik_for_scenarios', function (Blueprint $table) {
                    $table->foreign('plik_id')
                        ->references('id')
                        ->on('pliks');
                });

                Schema::table('plik_for_scenarios', function (Blueprint $table) {
                    $table->foreign('scenario_id')
                        ->references('id')
                        ->on('scenarios');
                });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plik_for_scenarios');
    }
}
