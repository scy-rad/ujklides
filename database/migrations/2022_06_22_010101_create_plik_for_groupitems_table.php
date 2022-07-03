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
            $table->integer('plik_id')->unsigned()->nullable();
            $table->integer('plik_type_id')->unsigned()->nullable();
            $table->string('plik_directory')->nullable();
            $table->string('plik_name')->nullable();
            $table->string('plik_title')->nullable();
            $table->text('plik_description')->nullable();
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
            $table->foreign('plik_type_id')
                ->references('id')
                ->on('plik_types');
        });

        Schema::table('plik_for_groupitems', function (Blueprint $table) {
            $table->foreign('plik_id')
                ->references('id')
                ->on('pliks');
        });



        // Schema::table('pliks', function (Blueprint $table) {
        //     $table->dropForeign(['plik_type_id']);
        // });

    }

    /*
    INSERT INTO `plik_for_groupitems` 
    (`id`, `item_id`, `item_group_id`, `plik_id`, `plik_type_id`, `plik_directory`, `plik_name`, `plik_title`, `plik_description`)
    SELECT plik_for_groups.id, null as item_id, item_group_id, plik_id, 1 as plik_type_id, 
    plik_directory, plik_name, plik_title, plik_description 
    FROM `plik_for_groups` 
    LEFT JOIN pliks on plik_for_groups.plik_id=pliks.id;
    */

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
