<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Jenssegers\Agent\Agent;

class Device extends Model
{
    protected $fillable = [
        'user_id',
        'device_uuid',
        'fingerprint_hash',
        'user_agent',
        'trusted',
        'latitude',
        'longitude',
        'last_used_at',
    ];

    protected $casts = [
        'trusted' => 'boolean',
        'last_used_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function readableName(): string
    {
        $ua = $this->user_agent;

        $browser = 'Unknown Browser';
        $platform = 'Unknown OS';

        if (str_contains($ua, 'Chrome'))
            $browser = 'Chrome';
        elseif (str_contains($ua, 'Firefox'))
            $browser = 'Firefox';
        elseif (str_contains($ua, 'Safari'))
            $browser = 'Safari';
        elseif (str_contains($ua, 'Edge'))
            $browser = 'Edge';

        if (str_contains($ua, 'Windows'))
            $platform = 'Windows';
        elseif (str_contains($ua, 'Mac'))
            $platform = 'MacOS';
        elseif (str_contains($ua, 'Linux'))
            $platform = 'Linux';
        elseif (str_contains($ua, 'Android'))
            $platform = 'Android';
        elseif (str_contains($ua, 'iPhone'))
            $platform = 'iPhone';

        return "$browser on $platform";
    }
    public function getCityCountry($lat, $lng)
    {
        $apiKey = env('OPENCAGE_API_KEY'); // Make sure to add your API key to your .env file

        $response = Http::get("https://api.opencagedata.com/geocode/v1/json", [
            'q' => "{$lat},{$lng}",
            'key' => $apiKey,
            'language' => 'en', // Optional: specify language of the response
            'no_annotations' => 1, // Optional: reduce unnecessary data
        ]);

        if ($response->successful()) {
            $data = $response->json();

            // Check if we got results from the API
            if (isset($data['results'][0])) {
                $city = $data['results'][0]['components']['city']
                    ?? $data['results'][0]['components']['town']
                    ?? $data['results'][0]['components']['village']
                    ?? null;

                $country = $data['results'][0]['components']['country'] ?? null;

                return [
                    'city' => $city,
                    'country' => $country,
                ];
            }
        }

        return null; // If no results found or API fails
    }
}