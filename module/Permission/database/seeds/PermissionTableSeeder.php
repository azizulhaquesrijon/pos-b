<?php

namespace Module\Permission\database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Module\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//         Schema::disableForeignKeyConstraints();
//         DB::table('permissions')->truncate();
//         // DB::truncate('inv_statuses');
//         Schema::enableForeignKeyConstraints();
        foreach ($this->getDatas() ?? [] as $permission) {

            Permission::updateOrCreate([
                'slug'                    => $permission['slug'],
            ], [
                'name'                  => $permission['name'],
                'submodule_id'          => $permission['submodule_id'],
                'created_by'            => 1,
                'updated_by'            => 1,
            ]);
        }
    }

    private function getDatas()
    {
        return [
            ['id' => '1', 'name' => 'Sale',                                 'slug'  => 'dokani.sales.index',                   'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '1', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '2', 'name' => 'Sale Create',                          'slug'  => 'dokani.sales.create',                  'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '1', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '3', 'name' => 'Sale View',                            'slug'  => 'dokani.sales.show',                  'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '1', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '4', 'name' => 'Sale Delete',                          'slug'  => 'dokani.sales.delete',                  'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '1', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '5', 'name' => 'Collection List',                      'slug'  => 'dokani.collections.index',             'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '2', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '6', 'name' => 'Collection Create',                    'slug'  => 'dokani.collections.create',            'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '2', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '7', 'name' => 'Collection Delete',                    'slug'  => 'dokani.collections.delete',            'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '2', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '8', 'name' => 'Purchase List',                        'slug'  => 'dokani.purchases.index',               'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '4', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '9', 'name' => 'Purchase Create',                      'slug'  => 'dokani.purchases.create',              'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '4', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '10', 'name' => 'Purchase View',                       'slug'  => 'dokani.purchases.show',              'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '4', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '11', 'name' => 'Purchase Delete',                     'slug'  => 'dokani.purchases.delete',              'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '4', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '12', 'name' => 'Due Payment List',                    'slug'  => 'dokani.payments.index',                'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '5', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '13', 'name' => 'Due Payment Create',                  'slug'  => 'dokani.payments.create',               'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '5', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '14', 'name' => 'Due Payment Delete',                  'slug'  => 'dokani.payments.delete',               'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '5', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '15', 'name' => 'Product List',                        'slug'  => 'dokani.products.index',       'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '6', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '16', 'name' => 'Product Create',                      'slug'  => 'dokani.products.create',       'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '6', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '17', 'name' => 'Product Update',                      'slug'  => 'dokani.products.edit',         'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '6', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '18', 'name' => 'Product Delete',                      'slug'  => 'dokani.products.delete',       'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '6', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '19', 'name' => 'Product Barcode',                     'slug'  => 'dokani.product-barcode.index', 'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '6', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '20', 'name' => 'Unit List',                           'slug'  => 'dokani.units.index',           'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '7', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '21', 'name' => 'Unit Create',                         'slug'  => 'dokani.units.store',           'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '7', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '22', 'name' => 'Unit Update',                         'slug'  => 'dokani.units.update',          'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '7', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '23', 'name' => 'Unit Delete',                         'slug'  => 'dokani.units.delete',          'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '7', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '24', 'name' => 'Category List',                       'slug'  => 'dokani.categories.index',      'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '8', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '25', 'name' => 'Category Create',                     'slug'  => 'dokani.categories.store',      'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '8', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '26', 'name' => 'Category Update',                     'slug'  => 'dokani.categories.update',     'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '8', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '27', 'name' => 'Category Delete',                     'slug'  => 'dokani.categories.delete',     'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '8', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '28', 'name' => 'Supplier List',                       'slug'  => 'dokani.suppliers.index',               'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '12', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '29', 'name' => 'Supplier Create',                     'slug'  => 'dokani.suppliers.store',               'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '12', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '30', 'name' => 'Supplier Edit',                       'slug'  => 'dokani.suppliers.edit',                'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '12', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '31', 'name' => 'Supplier Delete',                     'slug'  => 'dokani.suppliers.delete',              'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '12', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '32', 'name' => 'Customer List',                       'slug'  => 'dokani.customers.index',               'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '11', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '33', 'name' => 'Customer Create',                     'slug'  => 'dokani.customers.store',               'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '11', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '34', 'name' => 'Customer Edit',                       'slug'  => 'dokani.customers.edit',                'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '11', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '35', 'name' => 'Customer Delete',                     'slug'  => 'dokani.customers.delete',              'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '11', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '36', 'name' => 'Inventory',                           'slug'  => 'dokani.reports.inventory',             'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '18', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '37', 'name' => 'Alert Inventory',                     'slug'  => 'dokani.reports.alert-inventory',       'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '18', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '38', 'name' => 'Sales',                               'slug'  => 'dokani.reports.sales',                 'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '18', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '39', 'name' => 'Purchase',                            'slug'  => 'dokani.reports.purchase',              'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '18', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '40', 'name' => 'Cash Flow',                           'slug'  => 'dokani.reports.cash-flow',             'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '18', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '41', 'name' => 'Receiveable Due',                     'slug'  => 'dokani.reports.receivable-due',       'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '18', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '42', 'name' => 'Payable Due',                         'slug'  => 'dokani.reports.payable-due',          'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '18', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '43', 'name' => 'Customer Ledger',                     'slug'  => 'dokani.reports.customer-ledger',       'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '18', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '44', 'name' => 'Supplier Ledger',                     'slug'  => 'dokani.reports.supplier-ledger',       'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '18', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '45', 'name' => 'User List',                           'slug'  => 'dokani.users.index',                   'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '27', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '46', 'name' => 'User Create',                         'slug'  => 'dokani.users.store',                   'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '27', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '47', 'name' => 'User Update',                         'slug'  => 'dokani.users.update',                  'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '27', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '48', 'name' => 'User Delete',                         'slug'  => 'dokani.users.delete',                  'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '27', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '49', 'name' => 'Voucher Payment Create',              'slug'  => 'dokani.voucher-payments.store',      'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '13', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '50', 'name' => 'Voucher Payment List',                'slug'  => 'dokani.voucher-payments.index',      'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '13', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '51', 'name' => 'Voucher Payment Delete',              'slug'  => 'dokani.voucher-payments.delete',     'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '13', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '54', 'name' => 'Account List',                        'slug'  => 'dokani.ac.accounts.index',               'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '14', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '52', 'name' => 'Fund Transfer List',                  'slug'  => 'dokani.ac.fund-transfers.index',        'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '15', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '53', 'name' => 'Fund Transfer Create',                'slug'  => 'dokani.ac.fund-transfers.create',       'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '15', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '55', 'name' => 'G Parties Create',                    'slug'  => 'dokani.ac.parties.store',                'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '16', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '56', 'name' => 'G Parties List',                      'slug'  => 'dokani.ac.parties.index',                'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '16', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '57', 'name' => 'G Parties Edit',                      'slug'  => 'dokani.ac.parties.edit',                 'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' =>  '16', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '58', 'name' => 'G Parties Delete',                    'slug'  => 'dokani.ac.parties.delete',               'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '16', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '59', 'name' => 'Chart of Account Create',             'slug'  => 'dokani.ac.charts.store',                 'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '17', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '60', 'name' => 'Chart of Account List',               'slug'  => 'dokani.ac.charts.index',                 'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '17', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '61', 'name' => 'Chart of Account Edit',               'slug'  => 'dokani.ac.charts.edit',                  'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' =>  '17', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '62', 'name' => 'Chart of Account Delete',             'slug'  => 'dokani.ac.charts.delete',                'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '17', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '63', 'name' => 'Sales Return Exchange',               'slug'  => 'dokani.sale-return-exchanges.create',   'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' =>  '3', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '64', 'name' => 'Sales Return List',                   'slug'  => 'dokani.sale-return-exchanges.index',    'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' =>  '3', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '69', 'name' => 'Business Setting',                    'slug'  => 'dokani.business-profile.index',          'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' =>  '37', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '65', 'name' => 'Order',                               'slug'  => 'dokani.orders.index',                   'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '38', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '66', 'name' => 'Order Create',                        'slug'  => 'dokani.orders.create',                  'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '38', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '67', 'name' => 'Order Edit',                          'slug'  => 'dokani.orders.edit',                    'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '38', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '68', 'name' => 'Order Delete',                        'slug'  => 'dokani.orders.destroy',                 'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '38', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '69', 'name' => 'Employee',                            'slug'  => 'dokani.employees.index',                   'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '31', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '70', 'name' => 'Employee Create',                     'slug'  => 'dokani.employees.create',                  'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '31', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '71', 'name' => 'Employee Edit',                       'slug'  => 'dokani.employees.edit',                    'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '31', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '72', 'name' => 'Employee Delete',                     'slug'  => 'dokani.employees.destroy',                 'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '31', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '73', 'name' => 'Attendance Management',               'slug'  => 'dokani.attendance.index',                   'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '32', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '74', 'name' => 'Attendance Management Create',        'slug'  => 'dokani.attendance.create',                  'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '32', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '75', 'name' => 'Advance',                             'slug'  => 'dokani.advances.index',                   'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '33', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '76', 'name' => 'Advance Create',                      'slug'  => 'dokani.advances.create',                  'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '33', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '77', 'name' => 'Advance Edit',                        'slug'  => 'dokani.advances.edit',                    'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '33', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '78', 'name' => 'Advance Delete',                      'slug'  => 'dokani.advances.destroy',                 'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '33', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '79', 'name' => 'Salary',                              'slug'  => 'dokani.salaries.index',                   'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '34', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '80', 'name' => 'Salary Create',                       'slug'  => 'dokani.salaries.create',                  'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '34', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '81', 'name' => 'Salary Show',                         'slug'  => 'dokani.salaries.show',                    'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '34', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '82', 'name' => 'Payroll Setting',                     'slug'  => 'dokani.payrolls.edit',                   'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '35', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '83', 'name' => 'Holiday',                             'slug'  => 'dokani.holidays.index',                   'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '36', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '84', 'name' => 'Holiday Create',                      'slug'  => 'dokani.holidays.create',                  'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '36', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '85', 'name' => 'Holiday Edit',                        'slug'  => 'dokani.holidays.edit',                    'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '36', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '86', 'name' => 'Holiday Delete',                      'slug'  => 'dokani.holidays.destroy',                 'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '36', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '87', 'name' => 'Courier',                             'slug'  => 'dokani.couriers.index',                   'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '39', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '88', 'name' => 'Courier Create',                      'slug'  => 'dokani.couriers.create',                  'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '39', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '89', 'name' => 'Courier Edit',                        'slug'  => 'dokani.couriers.edit',                    'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '39', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '90', 'name' => 'Courier Delete',                      'slug'  => 'dokani.couriers.destroy',                 'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '39', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '91', 'name' => 'Point',                               'slug'  => 'dokani.points.index',                   'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '40', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '92', 'name' => 'Point Create',                        'slug'  => 'dokani.points.create',                  'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '40', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '93', 'name' => 'Point Edit',                          'slug'  => 'dokani.points.edit',                    'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '40', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '94', 'name' => 'Point Delete',                        'slug'  => 'dokani.points.destroy',                 'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '40', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '95', 'name' => 'Refer',                               'slug'  => 'dokani.refers.index',                   'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '41', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '96', 'name' => 'Refer Create',                        'slug'  => 'dokani.refers.create',                  'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '41', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '97', 'name' => 'Refer Edit',                          'slug'  => 'dokani.refers.edit',                    'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '41', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '98', 'name' => 'Refer Delete',                        'slug'  => 'dokani.refers.destroy',                 'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '41', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '99', 'name' => 'Refer Balance Transfer',              'slug'  => 'dokani.refer.transfer.index',           'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '11', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '100', 'name' => 'Product Ledger',                     'slug'  => 'dokani.reports.product_ledger',               'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '18', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '101', 'name' => 'VAT Report',                         'slug'  => 'dokani.reports.vats',                       'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '18', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '102', 'name' => 'Refer Wise Customer',                'slug'  => 'dokani.reports.refer-wise-customer',                       'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '18', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '103', 'name' => 'Income Expense Report',              'slug'  => 'dokani.reports.income-expense',                       'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '18', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '104', 'name' => 'Sales Return Show',                  'slug'  => 'dokani.sale-return-exchanges.show',    'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' =>  '3', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '105', 'name' => 'Chart of Account',                   'slug'  => 'dokani.reports.chart-of-account',       'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '18', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],


            ['id' => '106', 'name' => 'Investor Create',                    'slug'  => 'dokani.ac.investors.create',                 'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '42', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '107', 'name' => 'Investor List',                      'slug'  => 'dokani.ac.investors.index',                 'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '42', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '108', 'name' => 'Investor Edit',                      'slug'  => 'dokani.ac.investors.edit',                 'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' =>  '42', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '109', 'name' => 'Investor Delete',                    'slug'  => 'dokani.ac.investors.delete',                'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '42', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '110', 'name' => 'Withdraw List',                      'slug'  => 'dokani.ac.investor.withdraw.list',                'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '43', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '111', 'name' => 'Withdraw Create',                    'slug'  => 'dokani.ac.investor.withdraw',                'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '43', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '112', 'name' => 'Withdraw View',                      'slug'  => 'dokani.ac.investor.withdraw.show',                'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '43', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '113', 'name' => 'Withdraw Delete',                    'slug'  => 'dokani.ac.investor.withdraw.delete',                'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '43', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '114', 'name' => 'Disbursement',                       'slug'  => 'dokani.ac.disbursement',                'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '44', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '115', 'name' => 'Chart of Account Report',            'slug'  => 'dokani.reports.chart-of-account','description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '18', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '116', 'name' => 'Profit Report',                      'slug'  => 'dokani.reports.profit','description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '18', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '117', 'name' => 'Investor Ledger Report',             'slug'  => 'dokani.reports.investor-ledger','description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '18', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '118', 'name' => 'Client Add ',                        'slug'  => 'dokani.customers.client.add','description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '45', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '119', 'name' => 'Client View',                        'slug'  => 'dokani.customers.client.view','description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '45', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '119', 'name' => 'Customer SMS',                       'slug'  => 'dokani.customers.sms.send','description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '29', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '120', 'name' => 'Supplier SMS',                       'slug'  => 'dokani.suppliers.sms.send','description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '29', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '121', 'name' => 'Manage SMS',                         'slug'  => 'dokani.manage.sms','description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '29', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '122', 'name' => 'Category List',                       'slug'  => 'dokani.cus_categories.index',               'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '46', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '123', 'name' => 'Category Create',                     'slug'  => 'dokani.cus_categories.create',               'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '46', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '124', 'name' => 'Category Edit',                       'slug'  => 'dokani.cus_categories.edit',                'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '46', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '125', 'name' => 'Category Delete',                     'slug'  => 'dokani.cus_categories.delete',              'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '46', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '126', 'name' => 'Area List',                           'slug'  => 'dokani.cus_areas.index',               'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '47', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '127', 'name' => 'Area Create',                         'slug'  => 'dokani.cus_areas.create',               'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '47', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '128', 'name' => 'Area Edit',                           'slug'  => 'dokani.cus_areas.edit',                'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '47', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '129', 'name' => 'Area Delete',                         'slug'  => 'dokani.cus_areas.delete',              'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '47', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '130', 'name' => 'Account Create',                        'slug'  => 'dokani.ac.accounts.create',               'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '14', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '131', 'name' => 'Account Edit',                          'slug'  => 'dokani.ac.accounts.edit',               'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '14', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '132', 'name' => 'Brand List',                           'slug'  => 'dokani.brands.index',           'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '7', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '133', 'name' => 'Brand Create',                         'slug'  => 'dokani.brands.store',           'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '7', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '134', 'name' => 'Brand Update',                         'slug'  => 'dokani.brands.update',          'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '7', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],
            ['id' => '135', 'name' => 'Brand Delete',                         'slug'  => 'dokani.brands.delete',          'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '7', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '136', 'name' => 'Sales Return Delete',               'slug'  => 'dokani.sale-return-exchanges.delete',   'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' =>  '3', 'created_at' => '2019-12-25 09:58:21', 'updated_at' => '2019-12-25 09:58:21', 'status' => '1'],

            ['id' => '137', 'name' => 'Production issue List',                 'slug'  => 'dokani.production-issue.index',           'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '49', 'created_at' => '2023-01-17 12:58:21', 'updated_at' => '2023-01-17 12:58:21', 'status' => '1'],
            ['id' => '138', 'name' => 'Production issue Create',               'slug'  => 'dokani.production-issue.store',           'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '49', 'created_at' => '2023-01-17 12:58:21', 'updated_at' => '2023-01-17 12:58:21', 'status' => '1'],
            ['id' => '139', 'name' => 'Production issue Update',               'slug'  => 'dokani.production-issue.update',          'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '49', 'created_at' => '2023-01-17 12:58:21', 'updated_at' => '2023-01-17 12:58:21', 'status' => '1'],
            ['id' => '140', 'name' => 'Production issue Delete',               'slug'  => 'dokani.production-issue.delete',          'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '49', 'created_at' => '2023-01-17 12:58:21', 'updated_at' => '2023-01-17 12:58:21', 'status' => '1'],

            ['id' => '141', 'name' => 'Production receive List',               'slug'  => 'dokani.production-receive.index',           'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '50', 'created_at' => '2023-01-17 12:58:21', 'updated_at' => '2023-01-17 12:58:21', 'status' => '1'],
            ['id' => '142', 'name' => 'Production receive Create',             'slug'  => 'dokani.production-receive.store',           'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '50', 'created_at' => '2023-01-17 12:58:21', 'updated_at' => '2023-01-17 12:58:21', 'status' => '1'],
            ['id' => '143', 'name' => 'Production receive Update',             'slug'  => 'dokani.production-receive.update',          'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '50', 'created_at' => '2023-01-17 12:58:21', 'updated_at' => '2023-01-17 12:58:21', 'status' => '1'],
            ['id' => '144', 'name' => 'Production receive Delete',             'slug'  => 'dokani.production-receive.delete',          'description' => NULL, 'created_by' => '1', 'updated_by' => '1', 'submodule_id' => '50', 'created_at' => '2023-01-17 12:58:21', 'updated_at' => '2023-01-17 12:58:21', 'status' => '1'],


        ];
    }
}
