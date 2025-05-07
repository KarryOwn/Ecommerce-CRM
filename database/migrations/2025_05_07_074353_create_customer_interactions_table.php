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
        Schema::create('customer_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['support', 'inquiry', 'complaint', 'feedback', 'other']);
            $table->string('subject');
            $table->text('description');
            $table->text('notes')->nullable();
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open');
            $table->foreignId('handled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::table('customer_interactions', function (Blueprint $table) {
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->dateTime('scheduled_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('follow_up_date')->nullable();
            $table->string('channel')->default('email');
            $table->json('tags')->nullable();
            $table->json('attachments')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_interactions', function (Blueprint $table) {
            $table->dropColumn([
                'priority',
                'scheduled_at',
                'completed_at',
                'follow_up_date',
                'channel',
                'tags',
                'attachments'
            ]);
            $table->dropColumn('notes');
        });

        Schema::dropIfExists('customer_interactions');
    }
};
