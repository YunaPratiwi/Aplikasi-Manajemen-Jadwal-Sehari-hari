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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->boolean('is_completed')->default(false);
            $table->datetime('due_date')->nullable();
            $table->datetime('reminder_at')->nullable();
            $table->datetime('completed_at')->nullable();
            $table->integer('sort_order')->default(0);
            $table->json('tags')->nullable(); // Array of tags
            $table->json('attachments')->nullable(); // Array of file paths
            $table->integer('estimated_minutes')->nullable();
            $table->integer('actual_minutes')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->json('recurring_settings')->nullable(); // Recurring task settings
            $table->boolean('is_archived')->default(false);
            $table->datetime('archived_at')->nullable();
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['user_id', 'is_completed', 'is_archived']);
            $table->index(['category_id', 'is_completed']);
            $table->index(['priority', 'due_date']);
            $table->index(['status', 'created_at']);
            $table->index('sort_order');
            $table->index('due_date');
            $table->index('reminder_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};