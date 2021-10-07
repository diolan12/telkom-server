<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Customer extends Migration
{
    static $name = 'customers';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {Schema::defaultStringLength(256);
        Schema::create(self::$name, function (Blueprint $table) {
            $table->id();
            $table->string('no_indihome', 8)->unique()->nullable();
            $table->string('no_telephone', 8)->unique()->nullable();
            $table->string('email')->unique();
            $table->string('name', 32);
            $table->enum('gender', ['male', 'female']);
            $table->string('phone', 16);
            $table->string('whatsapp', 16)->nullable();
            $table->string('address', 256);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(self::$name);
    }
}
