<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'category_name' => 'Thời trang nam',
                'description' => 'Các sản phẩm thời trang cho nam giới',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'Thời trang nữ',
                'description' => 'Các sản phẩm thời trang cho nữ giới',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'Phụ kiện',
                'description' => 'Phụ kiện đi kèm thời trang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
