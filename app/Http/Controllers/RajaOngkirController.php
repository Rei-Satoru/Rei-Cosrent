<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RajaOngkirService;
use App\Models\ProfileContact;

class RajaOngkirController extends Controller
{
    protected $raja;

    public function __construct(RajaOngkirService $raja)
    {
        $this->raja = $raja;
    }

    public function provinces()
    {
        $data = $this->raja->provinces();
        return response()->json($data);
    }

    public function cities(Request $request)
    {
        $province = $request->query('province');
        $data = $this->raja->cities($province);
        return response()->json($data);
    }

    public function cost(Request $request)
    {
        $request->validate([
            'destination_city_id' => 'required|numeric',
            'weight' => 'nullable|numeric',
            'courier' => 'nullable|string',
        ]);

        $origin = env('RAJAONGKIR_ORIGIN_CITY_ID');
        if (!$origin) {
            $admin = ProfileContact::first();
            if ($admin && $admin->origin_city_id) {
                $origin = $admin->origin_city_id;
            }
            if (!$origin && $admin && $admin->address) {
                $origin = $this->raja->findCityIdFromAddress($admin->address);
            }
        }

        if (!$origin) {
            return response()->json(['error' => 'Origin city not configured'], 422);
        }

        $weight = $request->input('weight', 1000);
        $courier = $request->input('courier', explode(',', env('RAJAONGKIR_COURIERS', 'jne'))[0]);

        $data = $this->raja->cost($origin, $request->input('destination_city_id'), $weight, $courier);
        return response()->json($data);
    }

    public function shippingCost(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
        ]);

        $admin = ProfileContact::first();
        if (!$admin) {
            return response()->json(['error' => 'Admin profile unavailable'], 422);
        }

        $origin = $admin->origin_city_id;
        if (!$origin && $admin->address) {
            $origin = $this->raja->findCityIdFromAddress($admin->address);
        }

        $destination = $this->raja->findCityIdFromAddress($request->input('address'));
        $weight = 1000;
        $courier = explode(',', env('RAJAONGKIR_COURIERS', 'jne'))[0];

        if ($origin && $destination) {
            $data = $this->raja->cost($origin, $destination, $weight, $courier);
            if ($data && isset($data['rajaongkir']['results'][0]['costs'][0]['cost'][0]['value'])) {
                return response()->json([
                    'ongkir' => $data['rajaongkir']['results'][0]['costs'][0]['cost'][0]['value'],
                    'raw' => $data,
                ]);
            }
        }

        $ongkir = $this->raja->estimateShippingCostLocal($admin->address, $request->input('address'), $weight, $courier);

        return response()->json([
            'ongkir' => $ongkir,
            'raw' => null,
            'warning' => 'Origin or destination lookup failed or RajaOngkir unavailable, using local fallback estimate',
        ]);
    }
}
