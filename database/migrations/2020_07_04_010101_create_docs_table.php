<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('doc_title');
            $table->string('doc_subtitle');
            $table->text('doc_description');
            $table->datetime('doc_date');
            $table->smallInteger('doc_status')->default(0);
			$table->timestamps();
        });

        // 1 - dostępny
        // 0 - niedostępny

        Schema::create('doc_for_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_group_id')->unsigned();
            $table->integer('doc_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('doc_for_groups', function (Blueprint $table) {
            $table->foreign('item_group_id')
                ->references('id')
                ->on('item_groups');
        });

        Schema::table('doc_for_groups', function (Blueprint $table) {
            $table->foreign('doc_id')
                ->references('id')
                ->on('docs');
        });

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
        Schema::dropIfExists('doc_for_groups');
        Schema::dropIfExists('doc_for_items');
        Schema::dropIfExists('docs');
        
    }
}
