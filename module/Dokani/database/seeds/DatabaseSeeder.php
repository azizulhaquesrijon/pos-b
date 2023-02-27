<?php

namespace Module\Dokani\database\seeds;

use Illuminate\Database\Seeder;
use Module\Dokani\database\seeds\ShopTypeTableSeeder;
use Module\Dokani\database\seeds\CashCustomerSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ShopTypeTableSeeder::class,
            CashCustomerSeeder::class,
            PackageTypeSeeder::class,
            CountrySeeder::class,
//            AccountTypeSeeder::class,
        ]);
    }
}
