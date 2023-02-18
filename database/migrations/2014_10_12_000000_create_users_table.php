<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() : void {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // ACCOUNT INFO
            $table->string('role_key', 10);
            $table->foreign('role_key')->references('key')->on('roles')->noActionOnDelete();
            $table->tinyText('first_name');
            $table->tinyText('last_name');
            $table->string('email')->unique();
            $table->string('password')->default(Hash::make('password'));
            $table->rememberToken();

            // DETAILS
            $table->string('job', 20)->nullable();
            $table->string('phone', 20)->nullable();
            $table->date('birthday')->nullable();
            $table->text('description')->nullable();

            // BUSINESS DETAILS
            $table->string('iban', 50)->nullable();
            $table->string('tax_id', 50)->nullable();
            $table->string('vat_id', 50)->nullable();

            // DATES
            $table->dateTime('last_login')->nullable();
            $table->date('hire_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() : void {
        Schema::dropIfExists('users');
    }
};
