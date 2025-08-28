<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ex_web_visor', function (Blueprint $table) {
            $table->id();
            $table->string('unique_code', 32);
            $table->string('ip_address', 50);
            $table->string('browser', 255)->nullable();
            $table->string('language', 255)->nullable();
            $table->string('device', 255)->nullable();
            $table->string('country', 255)->nullable();
            $table->string('operating_system', 255)->nullable();
            $table->string('source', 255)->nullable();
            $table->timestamp('visited_at');
            $table->string('latitude',50)->nullable();
            $table->string('longitude',50)->nullable();
            $table->bigInteger('visit_count')->default(1);
            $table->timestamps();

            $table->index(['unique_code', 'visited_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('ex_web_visor');
    }
};
