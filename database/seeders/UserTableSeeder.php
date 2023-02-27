<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        User::UpdateOrCreate(
            [
                'id'            => 1,
            ],
            [
            'name'              => 'System Admin',
            'email'             => 'admin@gmail.com',
            'pin'               =>  12345678,
            'type'              => 'owner',
            'mobile'            => '01',
            'image'             => null,
            'designation'       => null,
            'employee_id'       => null,
            'dokan_id'          => null,
            'email_verified_at' =>  now(),
        ]);
    }
}
