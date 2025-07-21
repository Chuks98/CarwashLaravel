<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id(); // Auto-increment ID
            $table->string('firstname');
            $table->string('lastname');
            $table->string('phone');
            $table->string('email')->unique(); // unique like in Mongoose
            $table->string('password');
            $table->string('role');
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
