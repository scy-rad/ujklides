<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
 
class CreateServiceMailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_mails', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('service_firm_id')->unsigned();
            $table->string('name');
            $table->string('title');
            $table->string('address_1')->nullable();
            $table->string('address_2')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone2')->nullable();
            $table->string('description')->nullable();
            $table->smallInteger('status')->default(1);
            $table->timestamps();
        });

            Schema::table('service_mails', function (Blueprint $table) {
                $table->foreign('service_firm_id')
                    ->references('id')
                    ->on('service_firms');
            });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_mails');        
    }
}
