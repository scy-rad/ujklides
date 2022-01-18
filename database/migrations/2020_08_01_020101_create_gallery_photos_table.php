<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
 
class CreateGalleryPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gallery_photos');
    }
}
