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
            $table->string('uid', 16)->index()->unique();
            $table->foreignId('office');
            $table->foreignId('field')->nullable();
            $table->enum('status', ['pending', 'ongoing', 'trouble', 'completed', 'archived'])->default('pending');
            $table->foreignId('customer');
            $table->foreignId('service');
            $table->string('doc_customer')->nullable()->default(null);
            $table->dateTime('doc_customer_taken_at')->nullable()->default(null);
            $table->string('doc_house')->nullable()->default(null);
            $table->dateTime('doc_house_taken_at')->nullable()->default(null);
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
