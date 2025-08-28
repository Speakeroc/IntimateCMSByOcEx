<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up() {
        Schema::create('ex_users_group', function (Blueprint $table) {
            $table->id();
            $table->string('name',64);
            $table->text('color')->nullable();
            $table->text('permission');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('ex_users_group');
    }
};
