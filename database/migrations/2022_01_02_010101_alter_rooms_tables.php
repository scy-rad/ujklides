<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRoomsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->unsignedInteger('simmed_technician_character_propose_id')->default('1');
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->foreign('simmed_technician_character_propose_id')
                ->references('id')
                ->on('technician_characters');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropForeign(['simmed_technician_character_propose_id']);
        });

        Schema::table('rooms', function($table) {
            $table->dropColumn('simmed_technician_character_propose_id');
        });

    }
}
