<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
 
class CreateFaultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faults', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fault_title', 150);
            $table->datetime('start_date');
            $table->datetime('close_date')->nullable();
            $table->text('notification_description');
            $table->text('repair_description')->nullable();
            $table->integer('item_id')->unsigned();
            $table->integer('notifier_id')->unsigned();
            $table->integer('repairer_id')->unsigned()->nullable();
            
            $table->smallInteger('fault_status')->default(1);
            $table->timestamps();
        });

            
            Schema::table('faults', function (Blueprint $table) {
                $table->foreign('item_id')
                    ->references('id')
                    ->on('items');
            });
            Schema::table('faults', function (Blueprint $table) {
                $table->foreign('notifier_id')
                    ->references('id')
                    ->on('users');
            });

            Schema::table('faults', function (Blueprint $table) {
                $table->foreign('repairer_id')
                    ->references('id')
                    ->on('users');
            });

        

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::dropIfExists('faults');
        
        
    }
}
