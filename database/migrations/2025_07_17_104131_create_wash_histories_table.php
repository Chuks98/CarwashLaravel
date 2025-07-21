<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wash_histories', function (Blueprint $table) {
            $table->id();

            // Basic wash history fields
            $table->string('firstname');
            $table->string('email');
            $table->string('carName');
            $table->string('carModel');
            $table->string('washedBy'); // Person who washed the car
            $table->text('notes')->nullable();

            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wash_histories');
    }
};
