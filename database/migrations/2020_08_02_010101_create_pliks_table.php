<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
 
class CreatePliksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pliks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('plik_directory');
            $table->string('plik_name');
            $table->string('plik_version');
            $table->string('plik_title');
            $table->integer('plik_type_id')->unsigned();
            $table->text('plik_description');
            $table->smallInteger('plik_status')->default(1);
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
        Schema::dropIfExists('pliks');  
    }
}
