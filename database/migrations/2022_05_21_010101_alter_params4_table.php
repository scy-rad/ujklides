<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterParams4Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('params', function($table) {
            $table->string('unit_name');
            $table->string('unit_name_wersal');
		});

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('params', function($table) {
            $table->dropColumn('unit_name');
            $table->dropColumn('unit_name_wersal');
        });
    }
}
