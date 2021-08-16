<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_titles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_title_short');
            $table->smallInteger('user_title_sort')->default(100);
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('user_title_id');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('about')->default('');
            $table->string('user_fotka')->default('_user.jpg');
            $table->tinyInteger('user_status')->default(0);
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('simmed_notify')->default(1);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('user_phone_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_phone_type_name');
            $table->string('user_phone_type_short');
            $table->string('user_phone_type_glyphicon');
            $table->smallInteger('user_phone_type_sort')->default(100);
            $table->timestamps();
        });

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

        
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('user_title_id')
               ->references('id')
                ->on('user_titles');
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
        Schema::dropIfExists('user_phone_types');
        Schema::dropIfExists('users');
        Schema::dropIfExists('user_titles');
        
    }
}
