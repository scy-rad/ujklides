<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
 
class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('review_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_group_id')->unsigned();
            $table->string('template_title', 150);
            $table->tinyInteger('review_type');    //1 - inny;  2 - miesięczny; 3 - półroczny;    4 - roczny;       51 - producenta (<50 - użytkownika powtarzalny)
            $table->smallInteger('period_days');
            $table->smallInteger('days_before');
            $table->smallInteger('days_after');
            $table->text('template_body');
            $table->tinyInteger('revtemp_status')->default(1);    //1-aktywny;    0-nieaktywny
            $table->timestamps();
        });

            Schema::table('review_templates', function (Blueprint $table) {
                $table->foreign('item_group_id')
                    ->references('id')
                    ->on('item_groups');
            });

        
        Schema::create('reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('review_template_id')->unsigned();
            $table->string('review_title', 150);
            $table->integer('item_id')->unsigned();
            $table->date('start_date');
            $table->date('start_date_from');
            $table->date('start_date_to');
            $table->text('review_body')->nullable();
            $table->datetime('do_date')->nullable();
            $table->integer('reviewer_id')->unsigned()->nullable();
            $table->smallInteger('rev_status')->default(2); //1-do zaplanowania;    2-zaplanowany;    100-zrealizowany
            $table->timestamps();
        });

            
            Schema::table('reviews', function (Blueprint $table) {
                $table->foreign('item_id')
                    ->references('id')
                    ->on('items');
            });
            Schema::table('reviews', function (Blueprint $table) {
                $table->foreign('reviewer_id')
                    ->references('id')
                    ->on('users');
            });

            Schema::table('reviews', function (Blueprint $table) {
                $table->foreign('review_template_id')
                    ->references('id')
                    ->on('review_templates');
            });

        

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('review_templates');
        
        
    }
}
