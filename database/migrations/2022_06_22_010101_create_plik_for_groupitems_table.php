<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlikForGroupitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        Schema::create('plik_for_groupitems', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->unsigned()->nullable();
            $table->integer('item_group_id')->unsigned()->nullable();
            $table->integer('plik_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('plik_for_groupitems', function (Blueprint $table) {
            $table->foreign('item_id')
                ->references('id')
                ->on('items');
        });

        Schema::table('plik_for_groupitems', function (Blueprint $table) {
            $table->foreign('item_group_id')
                ->references('id')
                ->on('item_groups');
        });

        Schema::table('plik_for_groupitems', function (Blueprint $table) {
            $table->foreign('plik_id')
                ->references('id')
                ->on('pliks');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plik_for_groupitems');
        
    }
}
