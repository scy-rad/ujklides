<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
 
class CreateGalleryForRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gallery_for_rooms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('gallery_id')->unsigned();
            $table->integer('room_id')->unsigned();
            $table->timestamps();
        });

                Schema::table('gallery_for_rooms', function (Blueprint $table) {
                    $table->foreign('gallery_id')
                        ->references('id')
                        ->on('galleries');
                });

                Schema::table('gallery_for_rooms', function (Blueprint $table) {
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
        Schema::dropIfExists('gallery_for_rooms');        
    }
}
