<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('room_type_id')->default(3);
            $table->unsignedInteger('center_id')->default(1);
            $table->string('room_photo')->default('_room.jpg')->nullable();
            $table->string('room_number');
            $table->string('room_name');
            $table->text('room_description');
            $table->string('room_xp_code')->nullable();
            $table->smallInteger('room_status')->default(1);
            $table->timestamps();
        });
        // 1 - sala ćwiczeniowa
        // 2 - sala obsługi
        // 3 - sala magazynowa
        // 4 - sala inna (np. korytarz, łącznik)

        Schema::table('rooms', function (Blueprint $table) {
            $table->foreign('center_id')
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
        Schema::dropIfExists('rooms');
    }
}
