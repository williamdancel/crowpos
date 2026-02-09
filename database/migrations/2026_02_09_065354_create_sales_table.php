<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);

            $table->string('payment_method', 30)->default('cash'); // cash|gcash|maya|debit_card
            $table->string('payment_reference')->nullable();       // ✅ for non-cash

            $table->decimal('amount_paid', 12, 2)->default(0);
            $table->decimal('change', 12, 2)->default(0);

            $table->timestamps();

            $table->index('created_at');
            $table->index('payment_method');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
