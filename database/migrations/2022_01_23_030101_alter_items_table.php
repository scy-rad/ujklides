<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
 
class AlterItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->unsignedInteger('service_mail_id')->nullable();
        });

        Schema::table('items', function (Blueprint $table) {
            $table->foreign('service_mail_id')
                ->references('id')
                ->on('service_mails');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['service_mail_id']);
        });

        Schema::table('items', function($table) {
            $table->dropColumn('service_mail_id');
        });


    }
}
