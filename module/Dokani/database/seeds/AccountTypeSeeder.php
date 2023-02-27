<?php

namespace Module\Dokani\database\seeds;

use Illuminate\Database\Seeder;
use Module\Dokani\Models\Account;
use Module\Dokani\Models\AccountType;

class AccountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = array(
            array('id' => '1','name' => 'Cash', 'opening_balance' => 0 , 'balance' => 0,            'status' => 1),
            array('id' => '2','name' => 'Card', 'opening_balance' => 0 , 'balance' => 0,            'status' => 1),
            array('id' => '3','name' => 'Bank',  'opening_balance' => 0 , 'balance' => 0,           'status' => 1),
            array('id' => '4','name' => 'Mobile Wallet', 'opening_balance' => 0 , 'balance' => 0,   'status' => 1),
        );

        foreach ($types as $key => $item) {
            Account::updateOrCreate(
                [ 'id'       => $item['id'] ],
                [
                    'name'                      => $item['name'],
                    'opening_balance'           => $item['opening_balance'],
                    'balance'                   => $item['balance'],
                    'status'                    => $item['status'],
                ]
            );
        }
    }
}
