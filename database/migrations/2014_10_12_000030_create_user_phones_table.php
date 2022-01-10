<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPhonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_phones', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('user_phone_type_id')->default(1); //stac-służ, stac-pryw, kom-służ, kom-pryw
            $table->string('phone_number');
            $table->boolean('phone_for_coordinators');
            $table->boolean('phone_for_technicians');
            $table->boolean('phone_for_trainers');
            $table->boolean('phone_for_guests');
            $table->boolean('phone_for_anonymouse');
            $table->timestamps();
        });

        
        Schema::table('user_phones', function (Blueprint $table) {
            $table->foreign('user_id')
               ->references('id')
                ->on('users');
            });

        Schema::table('user_phones', function (Blueprint $table) {
            $table->foreign('user_phone_type_id')
               ->references('id')
                ->on('user_phone_types');
            });
        
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_phones');
    }
}
