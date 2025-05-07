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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            $table->enum('status', ['active', 'inactive', 'blocked'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->decimal('lifetime_value', 10, 2)->default(0);
            $table->integer('total_orders')->default(0);
            $table->date('last_purchase_date')->nullable();
            $table->string('acquisition_source')->nullable();
            $table->string('preferred_communication')->default('email');
            $table->json('interests')->nullable();
            $table->string('customer_tier')->default('standard');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'lifetime_value',
                'total_orders',
                'last_purchase_date',
                'acquisition_source',
                'preferred_communication',
                'interests',
                'customer_tier'
            ]);
        });

        Schema::dropIfExists('customers');
    }
};
