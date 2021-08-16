<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devitems', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('devitems_device_id');
            $table->unsignedInteger('devitems_room_storage_id');
            $table->smallInteger('devitems_storage_shelf')->default(1);
            $table->string('devitems_serial_number');
            $table->string('devitems_inventory_number');
            $table->date('devitems_purchase_date');
            $table->date('devitems_warranty_date');
            $table->text('devitems_description');
            $table->smallInteger('devitems_status')->default(1);
            $table->timestamps();
        // 1 - sprawny
        // 2 - sprawny z ograniczeniami
        // 3 - wyłączony z użytkowania
        // 4 - w trakcie naprawy
        // 5 - do kasacji
        // 6 - skasowany
        });

        Schema::table('devitems', function (Blueprint $table) {
            $table->foreign('devitems_device_id')
               ->references('id')
                ->on('devices');
        });

        Schema::table('devitems', function (Blueprint $table) {
            $table->foreign('devitems_room_storage_id')
               ->references('id')
                ->on('room_storages');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devitems');
    }
}
