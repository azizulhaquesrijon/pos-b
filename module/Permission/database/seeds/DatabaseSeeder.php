<?php

namespace Module\Permission\database\seeds;

use Illuminate\Database\Seeder;

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
            ModuleTableSeeder::class,
            SubmoduleTableSeeder::class,
            ParentPermissionTableSeeder::class,
            PermissionTableSeeder::class,
        ]);
    }
}
