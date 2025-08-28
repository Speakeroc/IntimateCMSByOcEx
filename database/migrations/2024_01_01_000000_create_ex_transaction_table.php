<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ex_transaction', function (Blueprint $table) {
            $table->id();
            $table->string('type', 200)->nullable();
            $table->string('short', 200)->nullable();
            $table->string('pincode', 200)->nullable();
            $table->string('price', 50)->nullable();
            $table->integer('post_id')->nullable();
            $table->string('post_name', 50)->nullable();
            $table->integer('user_id')->nullable();
            $table->bigInteger('order_id')->nullable();
            $table->integer('order_status_id')->nullable();
            $table->integer('pay_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ex_transaction');
    }
};
