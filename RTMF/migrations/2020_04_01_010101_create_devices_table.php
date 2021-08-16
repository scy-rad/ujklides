<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('devices_lib_group_id');
            $table->string('devices_photo')->default('_device.png');
            $table->string('devices_name');
            $table->text('devices_description');
            $table->smallInteger('devices_status')->default(1);
            $table->timestamps();
        // 1 - pokazuj
        // 2 - nie pokazuj
        });

        Schema::table('devices', function (Blueprint $table) {
            $table->foreign('devices_lib_group_id')
               ->references('id')
                ->on('libraries');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devices');
    }
}
