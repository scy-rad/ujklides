<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocForItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doc_for_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->unsigned();
            $table->integer('doc_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('doc_for_items', function (Blueprint $table) {
            $table->foreign('item_id')
                ->references('id')
                ->on('items');
        });

        Schema::table('doc_for_items', function (Blueprint $table) {
            $table->foreign('doc_id')
                ->references('id')
                ->on('docs');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doc_for_items');
        
    }
}
