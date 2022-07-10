<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('item_type_id');
            $table->string('item_group_photo')->default('');
            $table->string('item_group_name');
            $table->string('item_group_producent');
            $table->string('item_group_model');
            $table->text('item_group_description')->nullable();
            $table->smallInteger('item_group_status')->default(1);
            $table->timestamps();
        });

        Schema::table('item_groups', function (Blueprint $table) {
            $table->foreign('item_type_id')
               ->references('id')
                ->on('item_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_groups');
    }
}
