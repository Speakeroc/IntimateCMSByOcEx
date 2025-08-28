<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ex_pay_codes', function (Blueprint $table) {
            $table->id();
            $table->string('pay_link');
            $table->integer('nominal');
            $table->integer('bonus')->default(0)->nullable();
            $table->integer('status')->default(0)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ex_pay_codes');
    }
};
