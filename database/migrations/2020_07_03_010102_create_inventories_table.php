<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        Schema::create('inventories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('room_id');
            $table->string('inventory_name');
            $table->string('inventory_date');
            $table->string('inventory_description');
            $table->smallInteger('inventory_status')->default(1);
            $table->timestamps();
        });

        Schema::table('inventories', function (Blueprint $table) {
            $table->foreign('room_id')
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
        Schema::dropIfExists('inventories');
        
    }
}
