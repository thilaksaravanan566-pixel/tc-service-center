<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    /**
     * Display the Delivery Partner Dashboard with Map tracking
     */
    public function index()
    {
        // Mocking some live delivery assignments for the map view
        $deliveries = [
            [
                'id' => 'DEL-2026-01',
                'customer' => 'Mr. Sharma',
                'address' => 'Aura Luxury Apartments, Block C, OMR',
                'status' => 'Pending Pickup',
                'lat' => 12.9716, 
                'lng' => 80.2536, // OMR area
                'price' => 1250
            ],
            [
                'id' => 'DEL-2026-02',
                'customer' => 'David Warner',
                'address' => 'Silicon Valley Tech Park, Guindy',
                'status' => 'Out for Delivery',
                'lat' => 13.0067,
                'lng' => 80.2206, // Guindy context
                'price' => 450
            ],
            [
                'id' => 'DEL-2026-03',
                'customer' => 'Priya Ramesh',
                'address' => 'Phoenix Market City, Velachery',
                'status' => 'Enroute',
                'lat' => 12.9915,
                'lng' => 80.2160, 
                'price' => 6700
            ]
        ];

        return view('delivery.dashboard', compact('deliveries'));
    }
}
