<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('vats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ref_id')->nullable();

            $table->tinyInteger('value')->default(22);
            $table->string('description');
        });
    }

    public function down() {
        Schema::dropIfExists('vats');
    }
};
