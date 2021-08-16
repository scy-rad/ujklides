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
        Schema::create('plik_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('plik_type_menu');
            $table->string('plik_type_menu_code');
            $table->string('plik_type_name');
            $table->smallInteger('plik_type_sort')->default(100);
            $table->timestamps();
        });

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

            Schema::table('pliks', function (Blueprint $table) {
                $table->foreign('plik_type_id')
                    ->references('id')
                    ->on('plik_types');
            });

        Schema::create('plik_for_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('plik_id')->unsigned();
            $table->integer('item_group_id')->unsigned();
            $table->timestamps();
        });

                Schema::table('plik_for_groups', function (Blueprint $table) {
                    $table->foreign('plik_id')
                        ->references('id')
                        ->on('pliks');
                });

                Schema::table('plik_for_groups', function (Blueprint $table) {
                    $table->foreign('item_group_id')
                        ->references('id')
                        ->on('item_groups');
                });

        Schema::create('plik_for_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('plik_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->timestamps();
        });

                Schema::table('plik_for_items', function (Blueprint $table) {
                    $table->foreign('plik_id')
                        ->references('id')
                        ->on('pliks');
                });

                Schema::table('plik_for_items', function (Blueprint $table) {
                    $table->foreign('item_id')
                        ->references('id')
                        ->on('items');
                });

        Schema::create('plik_for_rooms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('plik_id')->unsigned();
            $table->integer('room_id')->unsigned();
            $table->timestamps();
        });

                Schema::table('plik_for_rooms', function (Blueprint $table) {
                    $table->foreign('plik_id')
                        ->references('id')
                        ->on('pliks');
                });

                Schema::table('plik_for_rooms', function (Blueprint $table) {
                    $table->foreign('room_id')
                        ->references('id')
                        ->on('rooms');
                });

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
        Schema::dropIfExists('plik_for_rooms');
        Schema::dropIfExists('plik_for_groups');
        Schema::dropIfExists('plik_for_items');
        Schema::dropIfExists('pliks');
        Schema::dropIfExists('plik_types');
        
        
    }
}
