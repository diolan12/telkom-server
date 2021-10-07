<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Account extends Migration
{
    static $name = 'accounts';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {Schema::defaultStringLength(256);
        Schema::create(self::$name, function (Blueprint $table) {
            $table->id();
            $table->string('nik', 8)->index()->unique();
            $table->string('email')->unique();
            $table->string('name', 32);
            $table->enum('gender', ['male', 'female']);
            $table->string('phone', 16);
            $table->string('whatsapp', 16)->nullable();
            $table->string('photo', 16);
            $table->string('password', 256);
            $table->foreignId('role');
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
