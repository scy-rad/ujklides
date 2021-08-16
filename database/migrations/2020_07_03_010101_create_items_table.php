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




        Schema::create('inventories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('room_id');
            $table->string('inventory_name');
            $table->string('inventory_date');
            $table->string('inventory_description');
            $table->smallInteger('inventory_status')->default(1);
            $table->timestamps();
        });

        Schema::create('inventory_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('inventory_id');
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('user_id');
            $table->smallInteger('inventory_item_type_id')->default(0);
            $table->string('inventory_item_description');
            $table->string('inventory_item_date');
            $table->smallInteger('inventory_item_status')->default(1);
            $table->timestamps();
        });


        Schema::table('inventories', function (Blueprint $table) {
            $table->foreign('room_id')
               ->references('id')
                ->on('rooms');
            });

        Schema::table('inventory_items', function (Blueprint $table) {
            $table->foreign('inventory_id')
               ->references('id')
                ->on('inventories');
            });
        
        Schema::table('inventory_items', function (Blueprint $table) {
            $table->foreign('item_id')
               ->references('id')
                ->on('items');
            });

        Schema::table('inventory_items', function (Blueprint $table) {
            $table->foreign('user_id')
               ->references('id')
                ->on('users');
            });
    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {  
        Schema::dropIfExists('inventory_items');
        Schema::dropIfExists('inventories');
        Schema::dropIfExists('items');
        
    }
}
