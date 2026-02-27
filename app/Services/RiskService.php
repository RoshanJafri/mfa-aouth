<?php

namespace App\Services;

use App\Services\GeoService;
use Illuminate\Support\Carbon;

class RiskService
{
    protected int $score = 0;
    protected GeoService $geo;

    public function __construct(GeoService $geo)
    {
        $this->geo = $geo;
    }

    /**
     * Calculate risk score for a login attempt
     *
     * @param array $params
     * [
     *   'user_devices' => Collection|array of Device,
     *   'device_uuid' => string,
     *   'fingerprint_hash' => string,
     *   'latitude' => float,
     *   'longitude' => float,
     *   'last_login' => LoginLog|null
     * ]
     */
    public function calculate(array $params): int
    {
        $userDevices = $params['user_devices'] ?? [];
        $deviceUuid = $params['device_uuid'] ?? null;
        $fingerprint = $params['fingerprint_hash'] ?? null;
        $lastLogin = $params['last_login'] ?? null;

        // Check if current device is trusted
        $trustedDeviceMatch = false;
        foreach ($userDevices as $device) {
            if ($device->trusted && $device->device_uuid === $deviceUuid) {
                $trustedDeviceMatch = true;
                break;
            }
        }

        if (!$trustedDeviceMatch) {
            $this->score += 40; //
        }

        if (!$trustedDeviceMatch && $fingerprint) {
            $fingerprintMismatch = true;
            foreach ($userDevices as $device) {
                if ($device->fingerprint_hash === $fingerprint) {
                    $fingerprintMismatch = false;
                    break;
                }
            }
            if ($fingerprintMismatch) {
                $this->score += 30;
            }
        }

        
        if ($lastLogin) {
            $distance = $this->geo->distanceKm(
                $params['latitude'],
                $params['longitude'],
                $lastLogin->latitude,
                $lastLogin->longitude
            );

            if ($distance > 100) {
                $this->score += 20;
            }

            // Impossible travel
            $speed = $this->geo->calculateSpeedKmH(
                $distance,
                $lastLogin->created_at,
                Carbon::now()
            );

            if ($speed > 900) {
                $this->score += 70;
            }
        }

        return max($this->score, 0);
    }
}