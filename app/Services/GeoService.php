<?php

namespace App\Services;

class GeoService
{
    
    public function distanceKm(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
    ): float {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a =
            sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) *
            cos(deg2rad($lat2)) *
            sin($dLon / 2) *
            sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
    
    public function calculateSpeedKmH(
        float $distanceKm,
        \Carbon\Carbon $from,
        \Carbon\Carbon $to
    ): float {
        $seconds = $to->diffInSeconds($from);

        if ($seconds <= 0) {
            return 0;
        }

        $hours = $seconds / 3600;

        return $distanceKm / $hours;
    }
}