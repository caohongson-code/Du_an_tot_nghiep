<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [];

        for ($i = 0; $i < 10; $i++) {
            $products[] = [
                'category_id'     => rand(1, 3), // tạm thời
                'product_name'    => fake()->words(3, true),
                'price'           => fake()->randomFloat(2, 10000, 1000000),
                'discount_price'  => fake()->optional()->randomFloat(2, 5000, 900000),
                'image'           => fake()->imageUrl(640, 480, 'products', true),
                'quantity'        => fake()->numberBetween(1, 100),
                'views'           => fake()->numberBetween(0, 1000),
                'description'     => fake()->paragraph(),
                'status'          => fake()->numberBetween(0, 1),
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ];
        }

        DB::table('products')->insert($products);
    }
}
