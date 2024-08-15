<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecipesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('recipes')->insert([
            ['name' => 'Pierogi', 'ingredients' => json_encode(
                [
                    'tomato' => rand(1, 2),
                    'lemon' =>rand(1, 2)
                ]
            )],
            ['name' => 'Grilled Portuguese', 'ingredients' => json_encode(
                [
                    'lemon' =>rand(1, 2),
                    'potato' => rand(1, 2)
                ]
            )],
            ['name' => 'Japanese rice', 'ingredients' => json_encode(
                [
                    'rice' => rand(1, 2),
                    'ketchup' =>rand(1, 2)
                ]
            )],
            ['name' => 'Blackberry Crumble', 'ingredients' => json_encode(
                [
                    'lettuce' => rand(1, 2),
                    'onion' =>rand(1, 2)
                ]
            )],
            ['name' => 'Strawberry Pie', 'ingredients' => json_encode(
                [
                    'cheese' => rand(1, 2),
                    'meat' =>rand(1, 2)
                ]
            )],
            ['name' => 'Chicken Alfredo', 'ingredients' => json_encode(
                [
                    'cheese' => rand(1, 2),
                    'chicken' =>rand(1, 2)
                ]
            )]
        ]);
    }
}
