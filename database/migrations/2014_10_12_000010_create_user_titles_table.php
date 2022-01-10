<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTitlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_titles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_title_short');
            $table->smallInteger('user_title_sort')->default(100);
            $table->timestamps();
        });


        
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('user_title_id')
               ->references('id')
                ->on('user_titles');
            });
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['user_title_id']);
        });
        Schema::dropIfExists('user_titles');
        
    }
}
