<?php

namespace Module\Dokani\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Traits\FileSaver;
use Module\Dokani\Models\CusArea;
use Module\Dokani\Models\CusCategory;
use Module\Dokani\Models\Customer;
use Module\Dokani\Services\LedgerService;

class CustomerController extends Controller
{

    use FileSaver;

    private $service;





    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct()
    {
    }












    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        $data['customers'] = Customer::latest()->dokani()->getCustomer()
            ->with(['refer_user','refer_customer'])
            ->where('dokan_id', '!=', null)
            ->likeSearch('name')->paginate(25);
        return view('customers.index', $data);
    }










    /*
     |--------------------------------------------------------------------------
     | CLIENT METHOD
     |--------------------------------------------------------------------------
    */
    public function getClient()
    {
        $data['clients'] = Customer::latest()->dokani()->getClient()
            ->likeSearch('name')->paginate(25);
        return view('customers.clients.index', $data);
    }














    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        $data['customers']  = Customer::dokani()->getCustomer()->get();
        $data['areas']      = CusArea::dokani()->get();
        $data['categories'] = CusCategory::dokani()->get();
        $data['users']      = User::whereDokanId(auth()->id())->get();

        return view('customers.create', $data);
    }










    /*
     |--------------------------------------------------------------------------
     | TRANSFER CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function referTransfer()
    {
        $data['customers'] = Customer::dokani()->get();
        return view('customers.refer-balance-transfer', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {

        try {
            $customer = Customer::dokani()->where('mobile',$request->mobile)->first();
            if ($customer){
                return redirect()->back()->withError('Customer Mobile Already Used !');
            }else {
                $this->storeOrUpdate($request);
            }

        } catch (\Throwable $th) {
            return redirect()->back()->withInput($request->all())->withError($th->getMessage());
        }

        return redirect()->back()->withMessage('Customer added success !');
    }








    /*
         |--------------------------------------------------------------------------
         | STORE METHOD
         |--------------------------------------------------------------------------
        */
    public function referTransferStore(Request $request)
    {

        try {

            $customer = Customer::find($request->customer_id);
            $customer->decrement('balance', $request->amount);
            $customer->decrement('refer_balance', $request->amount);


            (new LedgerService())->customerLedger($request->customer_id, $request->customer_id, 'Refer Balance Transfer', $request->amount, 'Out',null);


        } catch (\Throwable $th) {
            return redirect()->back()->withInput($request->all())->withError($th->getMessage());
        }

        return redirect()->route('dokani.customers.index')->withMessage('Customer balance transfer success !');
    }










    /*
     |--------------------------------------------------------------------------
     | SHOW METHOD
     |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        # code...
    }













    /*
     |--------------------------------------------------------------------------
     | EDIT METHOD
     |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $data['customers']  = Customer::dokani()->get();
        $data['areas']      = CusArea::dokani()->get();
        $data['categories'] = CusCategory::dokani()->get();
        $data['users']      = User::whereDokanId(auth()->id())->get();
        $data['customer'] = Customer::where('id',$id)->with(['area','category'])->first();
        return view('customers/edit', $data);
    }










    /*
     |--------------------------------------------------------------------------
     | UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function update($id, Request $request)
    {
        try {

            $this->storeOrUpdate($request, $id);

        } catch (\Throwable $th) {
            return redirect()->back()->withInput($request->all())->withError($th->getMessage());
        }

        return redirect()->back()->withMessage('Customer edited success !');
    }












    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTORY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {

                $customer = Customer::find($id);

                if (file_exists($customer->image)) {
                    unlink($customer->image);
                }

                $customer->customerLedgers->each->delete();

                $customer->delete();

            });

        } catch (\Throwable $th) {

            return redirect()->back()->withError($th->getMessage());
        }

        return redirect()->route('dokani.customers.index')->withMessage('Customer delete success !');
    }








    public function isDefault($id){

        $customer = Customer::query()->find($id);
        $default_customer = Customer::dokani()->where('is_default', 1)->first();

        if ($default_customer){

            $default_customer->update([
                'is_default'   => 0
            ]);
        }
            $customer->update([
                'is_default'   => 1
            ]);

        return redirect()->back()->withMessage('Default customer create success');

    }








    /*
     |--------------------------------------------------------------------------
     | STORE/UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    private function storeOrUpdate($request, $id = null)
    {
        $data = $request->validate([
            'name'          => 'required',
            'mobile'        => 'nullable',
            'address'       => 'nullable',
        ]);

        $data['opening_due']            =  $request->opening_due ?? 0;
        $data['balance']                = $request->balance;
        if (!$id){
            $data['balance'] = $request->opening_due;
        }
        $data['refer_by_user_id']       =  $request->refer_by_user_id ?? null;
        $data['refer_by_customer_id']   =  $request->refer_by_customer_id ?? null;
        $data['cus_area_id']            =  $request->cus_area_id;
        $data['cus_category_id']        =  $request->cus_category_id;
        $data['is_customer']            =  $request->is_customer;
        $data['discount']               =  $request->discount;
        $data['remark']                 =  $request->remark;




        $customer = Customer::updateOrCreate([
            'id'    => $id
        ], $data);

        $this->upload_file($request->image, $customer, 'image', 'customers');
    }
}
