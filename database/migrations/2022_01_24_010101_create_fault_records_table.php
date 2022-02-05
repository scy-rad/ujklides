<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
 
class CreateFaultRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fault_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fault_id')->unsigned();
            $table->string('header');
            $table->text('description');
            $table->smallInteger('mailto_technicians')->default(0); //1 - from system; 2 - outside
            $table->smallInteger('mailto_coordinators')->default(0);
            $table->smallInteger('mailto_service')->default(0);
            $table->datetime('mailto_technicians_date')->nullable();
            $table->datetime('mailto_coordinators_date')->nullable();
            $table->datetime('mailto_service_date')->nullable();
            
            $table->timestamps();
        });

            Schema::table('fault_records', function (Blueprint $table) {
                $table->foreign('fault_id')
                    ->references('id')
                    ->on('faults');
            });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fault_records', function (Blueprint $table) {
            $table->dropForeign(['fault_id']);
        });
        Schema::dropIfExists('fault_records');        
    }
}
