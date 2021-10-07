<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Order extends Migration
{
    static $name = 'orders';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {Schema::defaultStringLength(256);
        Schema::create(self::$name, function (Blueprint $table) {
            $table->id();
            $table->string('uid', 8)->index()->unique();
            $table->foreignId('office');
            $table->foreignId('field')->nullable();
            $table->enum('status', ['pending', 'received', 'ongoing', 'completed']);
            $table->foreignId('customer');
            $table->foreignId('service_type');
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
