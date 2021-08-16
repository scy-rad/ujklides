<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('equipment_types_photo')->default('_equipment.png');
            $table->string('equipment_types_name');
            $table->text('equipment_types_description');
			$table->timestamps();
        });
        
        Schema::create('equipments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('equipments_equipment_types_id');
            $table->string('equipments_photo')->default('');
            $table->string('equipments_producent')->default('');
            $table->string('equipments_model')->default('standard');
            $table->string('equipments_size')->default('');
            $table->text('equipments_description');
            $table->smallInteger('equipments_status')->default(1);
			$table->timestamps();
        });

        Schema::create('equipment_room_storages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ers_equipment_id');
            $table->unsignedInteger('ers_room_storage_id');
            $table->smallInteger('ers_planned');
            $table->smallInteger('ers_current');
            $table->smallInteger('equipments_status')->default(1);
			$table->timestamps();
        });

        Schema::table('equipments', function (Blueprint $table) {
            $table->foreign('equipments_equipment_types_id')
               ->references('id')
                ->on('equipment_types');
        });
        
        Schema::table('equipment_room_storages', function (Blueprint $table) {
            $table->foreign('ers_equipment_id')
               ->references('id')
                ->on('equipments');
        });

        Schema::table('equipment_room_storages', function (Blueprint $table) {
            $table->foreign('ers_room_storage_id')
               ->references('id')
                ->on('room_storages');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipment_room_storages');
        Schema::dropIfExists('equipments');
        Schema::dropIfExists('equipment_types');
    }
}
