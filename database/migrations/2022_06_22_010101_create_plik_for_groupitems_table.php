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

    /*
    UPDATE `items` SET `item_photo` = concat('storage/images/',item_photo);
    UPDATE `items` SET `item_photo` = ""  WHERE `item_photo` = 'storage/images/';

    UPDATE `galleries` SET `gallery_folder` = concat('storage/images/',gallery_folder);
    UPDATE `galleries` SET `gallery_folder` = ""  WHERE `gallery_folder` = 'storage/images/';

    UPDATE `item_groups` SET `item_group_photo` = concat('storage/images/',item_group_photo);
    UPDATE `item_groups` SET `item_group_photo` = ""  WHERE `item_group_photo` = 'storage/images/';

    UPDATE `item_types` SET `item_type_photo` = concat('storage/images/',item_type_photo);
    UPDATE `item_types` SET `item_type_photo` = ""  WHERE `item_type_photo` = 'storage/images/';

    UPDATE `rooms` SET `room_photo` = concat('storage/images/',room_photo);
    UPDATE `rooms` SET `room_photo` = ""  WHERE `room_photo` = 'storage/images/';

    UPDATE `users` SET `user_fotka` = concat('storage/images/avatars/',user_fotka);
    UPDATE `users` SET `user_fotka` = ""  WHERE `user_fotka` = 'storage/images/avatars/';
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
