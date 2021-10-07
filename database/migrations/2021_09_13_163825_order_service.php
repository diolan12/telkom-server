<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OrderService extends Migration
{
    static $name = 'order_services';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {Schema::defaultStringLength(256);
        Schema::create(self::$name, function (Blueprint $table) {
            $table->id();
            $table->foreignId('order');
            $table->foreignId('service');
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
