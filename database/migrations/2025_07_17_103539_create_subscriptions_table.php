<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            
            // Foreign key to users table (like userId in Mongoose)
            $table->foreignId('userId')->constrained('users')->onDelete('cascade');
            
            // Plan type
            $table->enum('plan', ['basic', 'premium', 'complex']);
            
            // Status with enum options
            $table->enum('status', [
                'active',
                'inactive',
                'canceled',
                'expired',
                'lost',
                'failed',
                'abandoned',
                'reversed'
            ])->default('inactive');
            
            // Price in Naira
            $table->decimal('price', 10, 2);
            
            // Dates
            $table->timestamp('startDate')->useCurrent();  // default: now
            $table->timestamp('nextBillingDate')->nullable(); 
            
            // Unique reference for Paystack
            $table->string('reference')->unique();
            
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
