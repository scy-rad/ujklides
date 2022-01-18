<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
 
class CreateGalleryForItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gallery_for_items');
    }
}
