<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('attendance_project', function (Blueprint $table) {
            $table->id();

            $table->foreignId('attendance_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->smallInteger('duration', false, true)->default(0);

            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('attendance_project');
    }
};
