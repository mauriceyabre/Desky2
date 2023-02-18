<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration {
    public function up() {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable();
            $table->text('description')->nullable();
            $table->string('phase', 20)->default('deal');
            $table->unsignedTinyInteger('index')->nullable();
            $table->unsignedInteger('quote')->nullable();
            $table->unsignedInteger('deposit')->nullable();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('deadline')->nullable();
            $table->date('started_at')->nullable();
            $table->date('delivered_at')->nullable();
            $table->date('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down() {
        Schema::dropIfExists('projects');
    }
}
