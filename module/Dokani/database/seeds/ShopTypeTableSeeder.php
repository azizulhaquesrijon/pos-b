<?php

namespace Module\Dokani\database\seeds;

use Illuminate\Database\Seeder;
use Module\Dokani\Models\ShopType;

class ShopTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    
        $shops = array(
            array('id' => '1', 'name' => 'Groceries', 'status' => '1', 'created_at' => '2021-11-28 00:21:18', 'updated_at' => '2021-11-28 00:21:18'),
            array('id' => '2', 'name' => 'Electrionics/Mobile', 'status' => '1', 'created_at' => '2021-11-28 00:21:18', 'updated_at' => '2021-11-28 00:21:18'),
            array('id' => '3', 'name' => 'Pharmacy', 'status' => '1', 'created_at' => '2021-11-28 00:21:18', 'updated_at' => '2021-11-28 00:21:18'),
            array('id' => '4', 'name' => 'Hardware', 'status' => '1', 'created_at' => '2021-11-28 00:21:18', 'updated_at' => '2021-11-28 00:21:18'),
            array('id' => '5', 'name' => 'Wholesaler', 'status' => '1', 'created_at' => '2021-11-28 00:21:18', 'updated_at' => '2021-11-28 00:21:18'),
            array('id' => '6', 'name' => 'Distributor', 'status' => '1', 'created_at' => '2021-11-28 00:21:18', 'updated_at' => '2021-11-28 00:21:18'),
            array('id' => '7', 'name' => 'Departmental Store', 'status' => '1', 'created_at' => '2021-11-28 00:21:18', 'updated_at' => '2021-11-28 00:21:18'),
            array('id' => '8', 'name' => 'Mobile Banking Agent', 'status' => '1', 'created_at' => '2021-11-28 00:21:18', 'updated_at' => '2021-11-28 00:21:18'),
            array('id' => '9', 'name' => 'Mobile Recharge & Banking', 'status' => '1', 'created_at' => '2021-11-28 00:21:18', 'updated_at' => '2021-11-28 00:21:18'),
            array('id' => '10', 'name' => 'Costemics', 'status' => '1', 'created_at' => '2021-11-28 00:21:18', 'updated_at' => '2021-11-28 00:21:18'),
            array('id' => '11', 'name' => 'Handicrafs', 'status' => '1', 'created_at' => '2021-11-28 00:21:18', 'updated_at' => '2021-11-28 00:21:18'),
            array('id' => '12', 'name' => 'Tailor', 'status' => '1', 'created_at' => '2021-11-28 00:21:18', 'updated_at' => '2021-11-28 00:21:18'),
            array('id' => '13', 'name' => 'Jwellary Shop', 'status' => '1', 'created_at' => '2021-11-28 00:21:18', 'updated_at' => '2021-11-28 00:21:18'),
            array('id' => '14', 'name' => 'Readymade Garments', 'status' => '1', 'created_at' => '2021-11-28 00:21:18', 'updated_at' => '2021-11-28 00:21:18'),
            array('id' => '15', 'name' => 'Service', 'status' => '1', 'created_at' => '2021-11-28 00:21:18', 'updated_at' => '2021-11-28 00:21:18'),
            array('id' => '16', 'name' => 'Others', 'status' => '1', 'created_at' => '2021-11-28 00:21:18', 'updated_at' => '2021-11-28 00:21:18')
        );

        foreach ($shops as $key => $shop) {
            ShopType::firstOrCreate(
                [ 'id'       => $shop['id'] ],
                [
                'name'       => $shop['name'],
                'status'     => $shop['status'],
                'created_at' => $shop['created_at'],
                'updated_at' => $shop['updated_at'],
                ]
         );
        }
    }
}
