<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomStoragesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('room_storages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('room_storage_type_id')->default(1);
            $table->unsignedInteger('room_id');
            $table->string('room_storage_number');
            $table->string('room_storage_name');
            $table->string('room_storage_description');
            $table->smallInteger('room_storage_shelf_count');
            $table->smallInteger('room_storage_sort');
            $table->smallInteger('room_storage_status')->default(1);
            $table->timestamps();
            
            // 1 - storage ćwiczeniowy
            // 2 - storage magazynowy
            // 3 - storage ćwiczeniowo-magazynowy
        });

        Schema::table('room_storages', function (Blueprint $table) {
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
        Schema::dropIfExists('room_storages');
    }
}
