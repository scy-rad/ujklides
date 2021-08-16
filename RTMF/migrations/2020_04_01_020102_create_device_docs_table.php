<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_docs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('device_docs_device_id')->unsigned();
            $table->integer('device_docs_doc_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('device_docs', function (Blueprint $table) {
            $table->foreign('device_docs_device_id')
                ->references('id')
                ->on('devices');
        });

        Schema::table('device_docs', function (Blueprint $table) {
            $table->foreign('device_docs_doc_id')
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
        Schema::dropIfExists('device_docs');
    }
}
