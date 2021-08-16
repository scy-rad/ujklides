<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_types', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('item_type_master_id');
            $table->unsignedInteger('item_type_parent_id')->default(0);
            $table->smallInteger('item_type_sort');
            $table->string('item_type_photo')->default('');
            $table->string('item_type_name');
            $table->string('item_type_code');
            $table->text('item_type_description');
            $table->smallInteger('item_type_status')->default(1);
            $table->timestamps();
        // 1 - pokazuj
        // 2 - nie pokazuj
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_types');
    }
}
