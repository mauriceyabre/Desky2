<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();

            $table->string('street', 100)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('postcode', 10)->nullable();
            $table->string('province', 10)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('country_code', 2)->default('IT');

            $table->morphs('addressable');

            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('addresses');
    }
};
