<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Module\Dokani\Models\Customer;

class CustomerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Customer::factory()->count(500)->create();
    }
}
