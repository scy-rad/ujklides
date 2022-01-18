<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

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
        
    }
}
