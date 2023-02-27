<?php

namespace Module\Dokani\database\seeds;

use Illuminate\Database\Seeder;
use Module\Dokani\Models\Customer;


class CashCustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Customer::updateOrCreate(['name' => 'Cash Customer'],[
            'dokan_id'          => 1,
            'created_by'        => 1,
            'name'              => 'Cash Customer',
        ]);
    }
}
