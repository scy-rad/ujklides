<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
 
class CreateGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('galleries', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('gallery_type')->default(1);
            $table->smallInteger('gallery_sort')->default(1);
            $table->string('gallery_name');
            $table->text('gallery_description');
            $table->string('gallery_folder');
            $table->smallInteger('gallery_status')->default(1);
            $table->timestamps();
        });

        Schema::create('gallery_photos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('gallery_id')->unsigned();
            $table->string('gallery_photo_name');
            $table->string('gallery_photo_title');
            $table->text('gallery_photo_description');
            $table->smallInteger('gallery_photo_sort')->default(1);
            $table->smallInteger('gallery_photo_status')->default(1);
            $table->timestamps();
        });

                Schema::table('gallery_photos', function (Blueprint $table) {
                    $table->foreign('gallery_id')
                        ->references('id')
                        ->on('galleries');
                });

        Schema::create('gallery_for_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('gallery_id')->unsigned();
            $table->integer('item_group_id')->unsigned();
            $table->timestamps();
        });

                Schema::table('gallery_for_groups', function (Blueprint $table) {
                    $table->foreign('gallery_id')
                        ->references('id')
                        ->on('galleries');
                });

                Schema::table('gallery_for_groups', function (Blueprint $table) {
                    $table->foreign('item_group_id')
                        ->references('id')
                        ->on('item_groups');
                });

        Schema::create('gallery_for_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('gallery_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->timestamps();
        });

                Schema::table('gallery_for_items', function (Blueprint $table) {
                    $table->foreign('gallery_id')
                        ->references('id')
                        ->on('galleries');
                });

                Schema::table('gallery_for_items', function (Blueprint $table) {
                    $table->foreign('item_id')
                        ->references('id')
                        ->on('items');
                });

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
        
        
        
        Schema::dropIfExists('gallery_photos');
        Schema::dropIfExists('gallery_for_groups');
        Schema::dropIfExists('gallery_for_items');
        Schema::dropIfExists('gallery_for_rooms');
        Schema::dropIfExists('galleries');
        
    }
}
