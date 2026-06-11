<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RajaOngkirService
{
    protected $baseUrl;
    protected $apiKey;
    protected $cityCache;

    // Distance tiers: city_id => distance_category
    // 0 = same as origin, 1 = medium distance (same region), 2 = far distance (different region)
    protected $cityDistanceTiers = [
        // Jawa Barat
        3307 => 0,  // Sukabumi (origin) = tier 0 (terdekat)
        104 => 1,   // Bandung = tier 1 (medium)
        301 => 1,   // Bogor = tier 1 (medium)
        3317 => 1,  // Cirebon = tier 1 (medium)
        3318 => 1,  // Kuningan = tier 1 (medium)
        3319 => 1,  // Garut = tier 1 (medium)
        3320 => 1,  // Tasikmalaya = tier 1 (medium)
        3321 => 1,  // Ciamis = tier 1 (medium)
        
        // DKI Jakarta & Jabodetabek
        154 => 2,   // Jakarta Timur = tier 2 (jauh)
        155 => 2,   // Jakarta Pusat = tier 2 (jauh)
        156 => 2,   // Jakarta Barat = tier 2 (jauh)
        157 => 2,   // Jakarta Selatan = tier 2 (jauh)
        158 => 2,   // Jakarta Utara = tier 2 (jauh)
        
        // Jawa Tengah
        372 => 2,   // Semarang = tier 2 (jauh)
        394 => 2,   // Yogyakarta = tier 2 (jauh)
        390 => 2,   // Surakarta/Solo = tier 2 (jauh)
        376 => 2,   // Salatiga = tier 2 (jauh)
        370 => 2,   // Pekalongan = tier 2 (jauh)
        371 => 2,   // Tegal = tier 2 (jauh)
        
        // Jawa Timur
        375 => 2,   // Surabaya = tier 2 (jauh)
        365 => 2,   // Malang = tier 2 (jauh)
        367 => 2,   // Jember = tier 2 (jauh)
        377 => 2,   // Sidoarjo = tier 2 (jauh)
        378 => 2,   // Gresik = tier 2 (jauh)
        379 => 2,   // Batu = tier 2 (jauh)
    ];

    // Pricing by distance tier (in Rupiah)
    protected $pricingByTier = [
        0 => 12000,  // Tier 0: Same city (termurah)
        1 => 18000,  // Tier 1: Medium distance (medium price)
        2 => 28000,  // Tier 2: Far distance (termahal)
    ];

    public function __construct()
    {
        $this->baseUrl = env('RAJAONGKIR_BASE_URL', 'https://api.rajaongkir.com/starter');
        $this->apiKey = env('RAJAONGKIR_API_KEY');
    }

    protected function headers()
    {
        return [
            'key' => $this->apiKey,
            'Accept' => 'application/json',
        ];
    }

    protected function client()
    {
        return Http::withHeaders($this->headers())->timeout(15);
    }

    public function provinces()
    {
        try {
            $res = $this->client()->get($this->baseUrl . '/province');
            return $res->successful() ? $res->json() : null;
        } catch (\Throwable $e) {
            \Log::warning('RajaOngkir provinces request failed: ' . $e->getMessage());
            return null;
        }
    }

    public function cities($provinceId = null)
    {
        // If API key is not configured, avoid remote call and return null quickly.
        if (!$this->apiKey) {
            return null;
        }

        try {
            $url = $this->baseUrl . '/city';
            $query = [];
            if ($provinceId) {
                $query['province'] = $provinceId;
            }
            $res = $this->client()->get($url, $query);
            return $res->successful() ? $res->json() : null;
        } catch (\Throwable $e) {
            \Log::warning('RajaOngkir cities request failed: ' . $e->getMessage());
            return null;
        }
    }

    public function cost($originCityId, $destinationCityId, $weightGrams = 1000, $courier = 'jne')
    {
        // If API key is missing, do not attempt remote cost calculation.
        if (!$this->apiKey) {
            return null;
        }

        try {
            $res = $this->client()->post($this->baseUrl . '/cost', [
                'origin' => $originCityId,
                'destination' => $destinationCityId,
                'weight' => (int) $weightGrams,
                'courier' => $courier,
            ]);

            return $res->successful() ? $res->json() : null;
        } catch (\Throwable $e) {
            \Log::warning('RajaOngkir cost request failed: ' . $e->getMessage());
            return null;
        }
    }

    public function findCityIdFromAddress(string $address): ?int
    {
        $normalizedAddress = strtolower(trim($address));
        if ($normalizedAddress === '') {
            return null;
        }
        // Fast path: try local map first to avoid remote calls and speed up resolution.
        $local = $this->findCityIdFromAddressLocal($normalizedAddress);
        if ($local) {
            return $local;
        }

        // If API key is missing, don't attempt remote lookup.
        if (!$this->apiKey) {
            return null;
        }

        if ($this->cityCache === null) {
            $response = $this->cities();
            $this->cityCache = $response['rajaongkir']['results'] ?? [];
        }

        $bestMatch = null;
        $bestLength = 0;

        foreach ($this->cityCache as $city) {
            $cityName = strtolower($city['city_name'] ?? '');
            $cityType = strtolower($city['type'] ?? '');
            $fullName = trim($cityType . ' ' . $cityName);
            if ($fullName === '' || $cityName === '') {
                continue;
            }

            if (strpos($normalizedAddress, $fullName) !== false || strpos($normalizedAddress, $cityName) !== false) {
                $length = strlen($fullName);
                if ($length > $bestLength) {
                    $bestLength = $length;
                    $bestMatch = $city;
                }
            }
        }

        if ($bestMatch) {
            return $bestMatch['city_id'] ?? null;
        }

        return null;
    }

    public function findCityIdFromAddressLocal(string $address): ?int
    {
        $map = [
            'kota sukabumi' => 3307,
            'sukabumi' => 3307,
            'kota jakarta timur' => 154,
            'jakarta timur' => 154,
            'jakarta' => 154,
            'kota bandung' => 104,
            'bandung' => 104,
            'kota bogor' => 301,
            'bogor' => 301,
        ];

        foreach ($map as $key => $value) {
            if (strpos($address, $key) !== false) {
                return $value;
            }
        }

        return null;
    }

    public function estimateShippingCostLocal(string $originAddress, string $destinationAddress, int $weightGrams = 1000, string $courier = 'jne'): int
    {
        $originRaw = strtolower(trim($originAddress));
        $destinationRaw = strtolower(trim($destinationAddress));

        // Resolve origin and destination to city IDs
        $originId = null;
        $destinationId = null;

        if (is_numeric($originRaw)) {
            $originId = (int) $originRaw;
        } else {
            $originId = $this->findCityIdFromAddressLocal($originRaw);
        }

        if (is_numeric($destinationRaw)) {
            $destinationId = (int) $destinationRaw;
        } else {
            $destinationId = $this->findCityIdFromAddressLocal($destinationRaw);
        }

        // If either city cannot be resolved, use high default price (unknown = far)
        if (!$originId || !$destinationId) {
            $baseCost = 25000;
        } else if ($originId === $destinationId) {
            // Same city = use tier 0 (termurah)
            $baseCost = $this->pricingByTier[0];
        } else {
            // Different cities: determine tier based on destination ID
            $tier = $this->cityDistanceTiers[$destinationId] ?? 2; // Unknown city = tier 2 (far)
            $baseCost = $this->pricingByTier[$tier] ?? 25000;
        }

        // Add weight surcharge (over 1kg)
        if ($weightGrams > 1000) {
            $extraKg = ceil(($weightGrams - 1000) / 1000);
            $baseCost += $extraKg * 4000;
        }

        return max(10000, $baseCost);
    }
}
