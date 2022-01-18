<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('item_group_id');
            $table->unsignedInteger('room_storage_id');
            $table->unsignedInteger('room_storage_current_id');
            $table->smallInteger('item_storage_shelf')->default(1);
            $table->string('item_photo')->default('');
            $table->string('item_serial_number');
            $table->string('item_inventory_number');
            $table->date('item_purchase_date');
            $table->date('item_warranty_date');
            $table->text('item_description');
            $table->smallInteger('item_status')->default(1);
            $table->timestamps();
            // 1 - sprawny
            // 2 - sprawny z ograniczeniami
            // 3 - wyłączony z użytkowania
            // 4 - w trakcie naprawy
            // 5 - do kasacji
            // 6 - skasowany
            });

        Schema::table('items', function (Blueprint $table) {
            $table->foreign('item_group_id')
               ->references('id')
                ->on('item_groups');
            });
        Schema::table('items', function (Blueprint $table) {
            $table->foreign('room_storage_id')
               ->references('id')
                ->on('room_storages');
            });
        
        Schema::table('items', function (Blueprint $table) {
            $table->foreign('room_storage_current_id')
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
        Schema::dropIfExists('items');
        
    }
}
