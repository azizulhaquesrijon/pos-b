<?php

namespace Module\Permission\database\seeds;

use Module\Permission\Models\Module;
use Illuminate\Database\Seeder;

class ModuleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getModules() ?? [] as $module) {
            Module::updateOrCreate([
                'id'        => $module['id'],
            ], [
                'name'  => $module['name'],
                'status'    => $module['status'],
            ]);
        }
    }

    private function getModules()
    {
        return [
            ['id' => '2',       'status' => '1', 'name' => 'Sales'],
            ['id' => '3',       'status' => '1', 'name' => 'Purchases'],
            ['id' => '4',       'status' => '1', 'name' => 'Products'],
            ['id' => '5',       'status' => '1', 'name' => 'Customers'],
            ['id' => '6',       'status' => '1', 'name' => 'Suppliers'],
            ['id' => '7',       'status' => '1', 'name' => 'G Accounts'],
            ['id' => '8',       'status' => '1', 'name' => 'Report'],
            ['id' => '9',       'status' => '1', 'name' => 'User Management'],
            ['id' => '10',      'status' => '1', 'name' => 'SMS Management'],
            ['id' => '11',      'status' => '1', 'name' => 'HRM & Payroll'],
            ['id' => '12',      'status' => '1', 'name' => 'Business Setting'],
            ['id' => '13',      'status' => '1', 'name' => 'Sale Return Exchanges'],
            ['id' => '14',      'status' => '1', 'name' => 'Orders'],
            ['id' => '15',      'status' => '1', 'name' => 'Courier'],
            ['id' => '16',      'status' => '1', 'name' => 'Point'],
            ['id' => '17',      'status' => '1', 'name' => 'Refer Customer'],
            ['id' => '18',      'status' => '1', 'name' => 'Production'],

        ];
    }
}
