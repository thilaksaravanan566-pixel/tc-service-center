<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SparePart;

class SparePartSeeder extends Seeder
{
    public function run(): void
    {
        $parts = [
            ['name' => 'Crucial 8GB DDR4 RAM', 'category' => 'RAM', 'price' => 2500, 'stock' => 15],
            ['name' => 'Samsung 980 500GB NVMe SSD', 'category' => 'Storage', 'price' => 4500, 'stock' => 10],
            ['name' => 'Logitech G213 Keyboard', 'category' => 'Peripherals', 'price' => 3800, 'stock' => 5],
            ['name' => 'HP Laserjet 12A Toner', 'category' => 'Printer', 'price' => 1200, 'stock' => 20],
        ];

        foreach ($parts as $part) {
            SparePart::create($part);
        }
    }
}