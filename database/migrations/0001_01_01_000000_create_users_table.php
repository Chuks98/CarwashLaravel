<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Basic user info
            $table->string('firstname');
            $table->string('lastname');
            $table->string('phone');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role');

            // Extra fields
            $table->string('address')->nullable();
            $table->string('carName')->nullable();
            $table->string('carModel')->nullable();
            $table->string('plateNumber')->nullable();

            // Status (like Mongoose enum)
            $table->enum('status', [
                'active',
                'inactive',
                // 'canceled',
                // 'expired',
                // 'lost'
            ])->default('inactive');

            // ✅ Paystack Auto-billing fields
            $table->string('authorizationCode')->nullable();
            $table->string('cardType')->nullable();
            $table->string('last4')->nullable();
            $table->string('expMonth')->nullable();
            $table->string('expYear')->nullable();
            $table->string('customerCode')->nullable();

            // ✅ Auto billing toggle
            $table->boolean('autoBilling')->default(false);

            // Laravel default auth fields
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps(); // created_at & updated_at
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
