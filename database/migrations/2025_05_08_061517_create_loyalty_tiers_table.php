<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loyalty_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('required_points');
            $table->decimal('point_multiplier', 8, 2)->default(1.0);
            $table->json('benefits')->nullable();
            $table->timestamps();
        });

        // Insert default tiers
        DB::table('loyalty_tiers')->insert([
            [
                'name' => 'bronze',
                'required_points' => 0,
                'point_multiplier' => 1.0,
                'benefits' => json_encode(['Basic rewards'])
            ],
            [
                'name' => 'silver',
                'required_points' => 1000,
                'point_multiplier' => 1.2,
                'benefits' => json_encode(['10% bonus points', 'Free shipping'])
            ],
            [
                'name' => 'gold',
                'required_points' => 5000,
                'point_multiplier' => 1.5,
                'benefits' => json_encode(['50% bonus points', 'Priority support', 'Exclusive deals'])
            ],
            [
                'name' => 'platinum',
                'required_points' => 10000,
                'point_multiplier' => 2.0,
                'benefits' => json_encode(['100% bonus points', 'VIP support', 'Special events', 'Early access'])
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalty_tiers');
    }
};
