<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSimmedDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('simmed_devices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('simmed_devices_simmed_id');
            $table->unsignedInteger('simmed_devices_devitem_id');
            $table->unsignedInteger('simmed_devices_parametrs_id');
            $table->timestamps();
        });
		
		Schema::table('simmed_devices', function (Blueprint $table) {
            $table->foreign('simmed_devices_simmed_id')
               ->references('id')
                ->on('simmeds');
        });
		
		Schema::table('simmed_devices', function (Blueprint $table) {
            $table->foreign('simmed_devices_devitem_id')
               ->references('id')
                ->on('devitems');
        });
		
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('simmed_devices');
    }
}
