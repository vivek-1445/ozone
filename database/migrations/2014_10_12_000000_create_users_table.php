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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('owner_name');
            $table->string('shop_name');
            $table->string('email');
            $table->string('mobile_number');
            $table->string('password');
            $table->string('latitude');
            $table->string('longitude');
            $table->longText('address');
            $table->tinyInteger('is_active')->default(0);
            $table->integer('verify_code')->nullable();
            $table->string('otp_expire')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamp('deleted_at')->nullable();
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
