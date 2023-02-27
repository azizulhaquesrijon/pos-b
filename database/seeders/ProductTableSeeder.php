<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Module\Dokani\Models\Product;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::factory()->count(500)->create();
    }
}
