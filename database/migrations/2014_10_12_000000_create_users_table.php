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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('user_title_id');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('about')->default('');
            $table->string('description')->default('');
            $table->string('user_fotka')->default('_user.jpg');
            $table->tinyInteger('user_status')->default(0);
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('simmed_notify')->default(1);
            $table->rememberToken();
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
        Schema::dropIfExists('users');        
    }
}
