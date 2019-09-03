<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->length(10)->unsigned();
            $table->string('first_name',100);
            $table->string('last_name',100);
            $table->string('user_name',100)->unique()->nullable()->default(null);
            $table->enum('gender', ['male', 'female'])->nullable()->default(null);
            $table->string('email',100)->unique()->nullable()->default(null);
            $table->string('country',50)->nullable()->default(null);
            $table->string('city',50)->nullable()->default(null);
            $table->string('password',100);
            $table->string('facebook_token',300)->nullable()->default(null);
            $table->string('google_token',300)->nullable()->default(null);
            $table->string('linkedIn_token',1000)->nullable()->default(null);
            $table->timestamp('last_login');
            $table->string('user_image',1000)->nullable()->default(null);
            $table->enum('image_type',['facebook','linkedIn','inApp'])->nullable()->default(null);
            $table->boolean('verified')->default(false);
            $table->string('remember_token')->nullable()->default(null);
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
        Schema::dropIfExists('users');
    }
}
