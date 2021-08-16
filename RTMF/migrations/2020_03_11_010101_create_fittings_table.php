<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFittingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fittings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('fittings_room_id');
            $table->string('fittings_photo')->default('_fitting.png');
            $table->string('fittings_name');
            $table->string('fittings_model');
            $table->string('fittings_inventory_number');
            $table->text('fittings_description');
            $table->smallInteger('fittings_status')->default(1);
			$table->timestamps();
        });

        Schema::table('fittings', function (Blueprint $table) {
            $table->foreign('fittings_room_id')
               ->references('id')
                ->on('rooms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fittings');
    }
}
