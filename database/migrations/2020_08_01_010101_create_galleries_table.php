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

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::dropIfExists('galleries');
        
    }
}
