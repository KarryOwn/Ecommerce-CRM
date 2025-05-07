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
        Schema::create('segment_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('segment_id')->constrained('customer_segments')->onDelete('cascade');
            $table->string('field');
            $table->string('operator');
            $table->string('value');
            $table->enum('condition_type', ['and', 'or'])->default('and');
            $table->string('group_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('segment_rules');
    }
};
