<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('things', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('things_thing_types_id');
            $table->unsignedInteger('things_room_id');
            $table->string('things_photo')->default('_thing.png');
            $table->string('things_producent');
            $table->string('things_model');
            $table->string('things_inventory_number');
            $table->text('things_description');
            $table->smallInteger('things_status')->default(1);
            $table->timestamps();
        });

        Schema::create('thing_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('thing_types_name');
            $table->smallInteger('thing_types_status')->default(1);
            $table->timestamps();
        });
        
        Schema::table('things', function (Blueprint $table) {
            $table->foreign('things_room_id')
               ->references('id')
                ->on('rooms');
        });

        Schema::table('things', function (Blueprint $table) {
            $table->foreign('things_thing_types_id')
               ->references('id')
                ->on('thing_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::dropIfExists('things');
        Schema::dropIfExists('thing_types');
    }
}
