<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('doc_title');
            $table->string('doc_subtitle');
            $table->text('doc_description');
            $table->datetime('doc_date');
            $table->smallInteger('doc_status')->default(0);
			$table->timestamps();
        });

        // 1 - dostępny
        // 0 - niedostępny

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('docs');
        
    }
}
