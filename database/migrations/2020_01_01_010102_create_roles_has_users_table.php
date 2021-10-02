<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesHasUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles_has_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('roles_has_users_users_id')->unsigned();
            $table->integer('roles_has_users_roles_id')->unsigned();
            $table->integer('roles_has_users_center_id')->unsigned();
            $table->timestamps();
        });

        Schema::create('centers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('center_name');
            $table->string('center_short',50);
            $table->string('center_direct',50);
            $table->timestamps();
        });

        Schema::table('roles_has_users', function (Blueprint $table) {
            $table->foreign('roles_has_users_users_id')
                ->references('id')
                ->on('users');
        });

        Schema::table('roles_has_users', function (Blueprint $table) {
            $table->foreign('roles_has_users_roles_id')
                ->references('id')
                ->on('roles');
        });

        Schema::table('roles_has_users', function (Blueprint $table) {
            $table->foreign('roles_has_users_center_id')
                ->references('id')
                ->on('centers');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles_has_users');
        Schema::dropIfExists('centers');
    }
}
