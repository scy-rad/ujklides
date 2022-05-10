<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTechnicianCharacters2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('technician_characters', function($table) {
            $table->string('character_colour')->default(' ');
			});
        echo "\033[0;31m";
        echo '+-------------------------------------------------------------+'."\n";
        echo '| dopisz kody kolorów (style) w tablicy technician_characters |'."\n";
        echo '+-------------------------------------------------------------+'."\n";
        echo "\033[0m";
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('technician_characters', function($table) {
            $table->dropColumn('character_colour');
        });
    }
}
