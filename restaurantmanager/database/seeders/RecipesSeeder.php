<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecipesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('recipes')->insert([
            ['name' => 'recipe 1', 'ingredients' => json_encode(
                [
                    'tomato' => rand(1, 2),
                    'lemon' =>rand(1, 2)
                ]
            )],
            ['name' => 'recipe 2', 'ingredients' => json_encode(
                [
                    'lemon' =>rand(1, 2),
                    'potato' => rand(1, 2)
                ]
            )],
            ['name' => 'recipe 3', 'ingredients' => json_encode(
                [
                    'rice' => rand(1, 2),
                    'ketchup' =>rand(1, 2)
                ]
            )],
            ['name' => 'recipe 4', 'ingredients' => json_encode(
                [
                    'lettuce' => rand(1, 2),
                    'onion' =>rand(1, 2)
                ]
            )],
            ['name' => 'recipe 5', 'ingredients' => json_encode(
                [
                    'cheese' => rand(1, 2),
                    'meat' =>rand(1, 2)
                ]
            )],
            ['name' => 'recipe 6', 'ingredients' => json_encode(
                [
                    'cheese' => rand(1, 2),
                    'chicken' =>rand(1, 2)
                ]
            )]
        ]);
    }
}
