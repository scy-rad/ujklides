<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
 
class CreateReviewTemplatesTable extends Migration
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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('review_templates');
    }
}
