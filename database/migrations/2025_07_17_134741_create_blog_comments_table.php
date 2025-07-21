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
        Schema::create('blog_comments', function (Blueprint $table) {
            $table->id();                                        // Primary key
            $table->foreignId('blogId')                         // Link to blog
                  ->constrained('blogs')                         // Reference blogs table
                  ->onDelete('cascade');                         // Delete comments if blog is deleted
            $table->string('name');                              // Commenter name
            $table->string('email');                             // Commenter email
            $table->text('comment');                             // Comment text
            $table->timestamps();                                // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_comments');
    }
};
