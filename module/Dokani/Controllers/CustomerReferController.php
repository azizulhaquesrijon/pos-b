<?php

namespace Module\Dokani\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Module\Dokani\Models\CustomerRefer;
use Module\Dokani\Models\CustomerReferSetting;

class CustomerReferController extends Controller
{
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
        $data['refers'] = CustomerRefer::latest()->dokani()->paginate(25);
        $data['refer'] = CustomerReferSetting::dokani()->first();
        return view('refers.index', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
//        $data['point'] = PointSetting::dokani()->first();
        return view('refers.create');
    }













    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {

        try {

            $this->storeOrUpdate($request);

        } catch (\Throwable $th) {
            return redirect()->back()->withInput($request->all())->withError($th->getMessage());
        }

        return redirect()->route('dokani.refers.index')->withMessage('Refer added success !');
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
        $data['refer'] = CustomerRefer::find($id);
        return view('refers.edit', $data);
    }











//    /*
//     |--------------------------------------------------------------------------
//     | EDIT METHOD
//     |--------------------------------------------------------------------------
//     */
//    public function editReferValue(Request $request , $id){
//
//        $this->pointValueStoreUpdate($request , $id);
//
//        return redirect()->back()->withMessage('Refer edit success !');
//    }











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

        return redirect()->route('dokani.refers.index')->withMessage('Refer edited success !');
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


    /*
     |--------------------------------------------------------------------------
     | STORE/UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    private function storeOrUpdate($request, $id = null)
    {
        $data = $request->validate([
            'start_price'      => 'required',
            'end_price'        => 'required',
            'refer_point'      => 'required',
        ]);


        CustomerRefer::updateOrCreate([
            'id'    => $id
        ], $data);
    }


    private function pointValueStoreUpdate($request, $id = null){

        CustomerReferSetting::updateOrCreate([
            'id'        => $id,
            'dokan_id'  => dokanId(),
        ],[
            'refer_value'   => $request->refer_value,
        ]);
    }






}
