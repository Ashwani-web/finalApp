<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goal', function (Blueprint $table) {
            $table->increments('id')->length(10)->unsigned();
            $table->integer('user_id')->length(10)->unsigned();
            $table->integer('cat_id')->length(10)->unsigned();
            //$table->integer('sub_cat_id')->length(10)->unsigned();
            //$table->timestamp('start_time')->nullable()->default(null);
            //$table->timestamp('end_time')->nullable()->default(null);
            //$table->longText('note');
            $table->integer('total_seconds')->length(10)->unsigned();
            $table->tinyInteger('is_active')->default(1);
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
        Schema::dropIfExists('goal');
    }
}
