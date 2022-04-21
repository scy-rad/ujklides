<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTechnicianCharactersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('technician_characters', function($table) {
            $table->smallInteger('working_character')->default('1');
			});
            // 1 - working character
            // 0 - free character

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('technician_characters', function($table) {
            $table->dropColumn('working_character');
        });
    }
}
