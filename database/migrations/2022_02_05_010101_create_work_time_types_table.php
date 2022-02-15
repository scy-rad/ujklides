<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
 
class CreateWorkTimeTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_time_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->smallInteger('time_character')->default(0); // 0 - all work
                                                                // 1 - in work
                                                                // 2 - out work
            
            $table->string('colour');
            $table->string('short_name');
            $table->string('long_name');
            $table->text('description');

            $table->smallInteger('status')->default(1); // 1 - active
            
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
        Schema::dropIfExists('work_time_types');        
    }
}
