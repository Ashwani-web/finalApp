<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoalStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goal_status', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('cat_id')->length(10)->unsigned();
            $table->integer('sub_cat_id')->length(10)->unsigned();
            $table->integer('user_id')->length(10)->unsigned();
            $table->timestamp('start_time')->nullable()->default(null);
            $table->timestamp('end_time')->nullable()->default(null);
            $table->integer('total_seconds')->length(10)->unsigned();
            $table->string('allocated_st',100);
            $table->string('note',200);
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
        Schema::dropIfExists('goal_status');
    }
}
