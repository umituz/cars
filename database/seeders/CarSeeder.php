<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Car;
use Illuminate\Database\Seeder;

/**
 * Class CarSeeder
 * @package Database\Seeders
 */
class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Car::factory(1)->hasBrand()->times(1)->create();
    }
}
