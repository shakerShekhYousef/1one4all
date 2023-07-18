<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //subcategory_id=0
        Schema::disableForeignKeyConstraints();
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
			$table->string('mobile', 60)->nullable(true);
			$table->string('age')->nullable();
            $table->string('password');
			$table->unsignedBigInteger('role_id')->default(3);
            $table->bigInteger('subcategory_id')->unsigned()->nullable();
			$table->text('bio')->nullable();
			$table->unsignedBigInteger('level_id')->nullable();
			$table->text('profile_pic')->nullable();
            $table->text('country_code')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->integer('otp')->nullable();
            $table->boolean('approved')->default(true);
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('subcategory_id')->references('id')->on('sub_categories');
            $table->foreign('level_id')->references('id')->on('levels');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
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
