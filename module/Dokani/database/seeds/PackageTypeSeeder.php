<?php

namespace Module\Dokani\database\seeds;

use Illuminate\Database\Seeder;
use Module\Dokani\Models\PackageType;

class PackageTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = array(
            array('id' => '1','name' => 'Bronze','months' => 1,'price' => 0),
            array('id' => '2','name' => 'Gold','months' => 1,'price' => 50),
            array('id' => '3','name' => 'Platinum','months' => 1,'price' => 100),
        );

        foreach ($types as $key => $item) {
            PackageType::firstOrCreate(
                [ 'id'       => $item['id'] ],
                [
                    'name'              => $item['name'],
                    'months'              => $item['months'],
                    'price'             => $item['price'],
                ]
            );
        }
    }
}
