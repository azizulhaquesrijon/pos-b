<?php

namespace Module\Permission\database\seeds;

use Illuminate\Database\Seeder;
use Module\Permission\Models\ParentPermission;

class ParentPermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getDatas() ?? [] as $parent_permission) {
            ParentPermission::updateOrCreate([
                'name'          => $parent_permission['name']
            ], [
                'id'            => $parent_permission['id'],
                'submodule_id'  => $parent_permission['submodule_id']
            ]);
        }
    }

    private function getDatas()
    {
        return $parent_permissions = [
            ['id' => '1', 'name' => 'Employee',     'submodule_id' => '3', 'created_at' => '2019-12-25 20:56:14', 'updated_at' => '2019-12-25 20:56:14'],
            ['id' => '2', 'name' => 'Salary Setup', 'submodule_id' => '3', 'created_at' => '2019-12-25 21:05:01', 'updated_at' => '2019-12-25 21:05:01'],

        ];
    }
}
