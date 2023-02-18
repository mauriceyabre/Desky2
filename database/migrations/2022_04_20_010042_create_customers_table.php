<?php

use App\Enums\CustomerCategory;
use App\Enums\CustomerType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration {
    public function up() {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ref_id')->nullable();

            $table->tinyText('name');
            $table->enum('type', CustomerType::values())->default('company'); // company | person | pa | condo
            $table->string('vat_number')->nullable()->unique();
            $table->string('tax_code')->nullable()->unique();

            $table->string('email')->nullable()->unique();
            $table->string('pec')->nullable()->unique();
            $table->string('phone', 20)->nullable();
            $table->text('description')->nullable();

            $table->string('sdi_code', 7)->nullable();
            $table->boolean('e_invoice')->default(true);
            $table->foreignId('default_vat_id')->nullable()->constrained('vats')->nullOnDelete();
            $table->json('default_vat_entity')->nullable();

            $table->string('bank_name', 50)->nullable();
            $table->string('bank_iban', 30)->nullable();
            $table->string('bank_swift', 15)->nullable();

            $table->enum('category', CustomerCategory::values())->nullable(); // interior-design | product-design | architecture | furniture-shop | industrial | real-estate | other

            $table->string('website')->nullable();
            $table->json('social_links')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down() {
        Schema::dropIfExists('customers');
    }
}
