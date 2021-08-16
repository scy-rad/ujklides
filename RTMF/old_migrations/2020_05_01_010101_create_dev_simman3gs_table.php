<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevSimman3gsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dev_simman3gs', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('dev_simman3gs_cisnienie_up');
            $table->smallInteger('dev_simman3gs_cisnienie_down');
            $table->smallInteger('dev_simman3gs_tetno');
            $table->smallInteger('dev_simman3gs_oddechy');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dev_simman3gs');
    }
}
