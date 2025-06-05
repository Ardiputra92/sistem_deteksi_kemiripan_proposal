<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Threshold;

class ThresholdSeeder extends Seeder
{
    public function run()
    {
        Threshold::create(['value' => 25]);
    }
}

