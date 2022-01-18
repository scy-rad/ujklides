<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
 
class CreatePlikTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plik_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('plik_type_menu');
            $table->string('plik_type_menu_code');
            $table->string('plik_type_name');
            $table->smallInteger('plik_type_sort')->default(100);
            $table->timestamps();
        });

            Schema::table('pliks', function (Blueprint $table) {
                $table->foreign('plik_type_id')
                    ->references('id')
                    ->on('plik_types');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pliks', function (Blueprint $table) {
            $table->dropForeign(['plik_type_id']);
        });
        Schema::dropIfExists('plik_types');   
    }
}
