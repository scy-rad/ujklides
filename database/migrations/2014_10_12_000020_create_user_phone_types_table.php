<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPhoneTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_phone_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_phone_type_name');
            $table->string('user_phone_type_short');
            $table->string('user_phone_type_glyphicon');
            $table->smallInteger('user_phone_type_sort')->default(100);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_phone_types');
    }
}
