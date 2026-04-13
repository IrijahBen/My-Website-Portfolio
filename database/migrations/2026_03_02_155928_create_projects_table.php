<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category');
            $table->string('image');
            $table->string('author');
            $table->string('author_avatar')->nullable();
            $table->integer('likes')->default(0);
            $table->integer('views')->default(0);
            $table->string('link')->nullable();
            $table->json('tags')->nullable(); // Laravel natively supports JSON columns
            $table->timestamps(); // Automatically creates 'created_at' and 'updated_at'
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
