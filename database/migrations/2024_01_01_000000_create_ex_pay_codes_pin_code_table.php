<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ex_pay_codes_pin_code', function (Blueprint $table) {
            $table->id();
            $table->integer('pay_id')->nullable();
            $table->text('pin_code')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ex_pay_codes_pin_code');
    }
};
