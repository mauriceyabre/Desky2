<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('absence_logs', function (Blueprint $table) {
            $table->id();

            $table->string('cause', 10)->default('holidays');
            $table->boolean('is_validated')->default(true);
            $table->foreignId('attendance_id')->constrained()->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('absence_logs');
    }
};
