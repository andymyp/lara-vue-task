<?php

namespace Database\Seeders;

use App\Models\Today;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TodaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        // Make 5 data
        for ($i = 0; $i < 5; $i++) {
            Today::create([
                'taskid' => Str::random(10),
                'title' => $faker->sentence($nbWords = 4, $variableNbWords = false)
            ]);
        }
    }
}
