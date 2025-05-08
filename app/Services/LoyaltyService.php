<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Order;
use App\Models\LoyaltyPoint;
use App\Models\LoyaltyTransaction;
use App\Models\LoyaltyTier;

class LoyaltyService
{
    public function checkAndUpdateTier(LoyaltyPoint $loyalty)
    {
        $currentPoints = $loyalty->lifetime_points;
        
        // Get eligible tier based on points
        $newTier = LoyaltyTier::where('required_points', '<=', $currentPoints)
            ->orderBy('required_points', 'desc')
            ->first();

        if ($newTier && $loyalty->tier !== $newTier->name) {
            $oldTier = $loyalty->tier;
            $loyalty->tier = $newTier->name;
            $loyalty->save();

            // Record tier upgrade transaction
            LoyaltyTransaction::create([
                'customer_id' => $loyalty->customer_id,
                'points' => 0,
                'type' => 'tier_upgrade',
                'source' => "Upgraded from {$oldTier} to {$newTier->name}",
            ]);

            return true;
        }

        return false;
    }

    public function addPoints(int $customerId, int $points, string $source, ?string $type = 'earn')
    {
        $loyalty = LoyaltyPoint::firstOrCreate(
            ['customer_id' => $customerId],
            ['points' => 0, 'lifetime_points' => 0, 'tier' => 'bronze']
        );

        // Get tier multiplier
        $tier = LoyaltyTier::where('name', $loyalty->tier)->first();
        $adjustedPoints = (int) ($points * $tier->point_multiplier);

        // Create transaction
        LoyaltyTransaction::create([
            'customer_id' => $customerId,
            'points' => $adjustedPoints,
            'type' => $type,
            'source' => $source
        ]);

        // Update points
        $loyalty->points += $adjustedPoints;
        $loyalty->lifetime_points += $adjustedPoints;
        $loyalty->save();

        // Check for tier upgrade
        $this->checkAndUpdateTier($loyalty);

        return $loyalty->fresh();
    }
}