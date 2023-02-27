<?php

namespace Module\Dokani\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Module\Dokani\Models\PointConfigure;
use Module\Dokani\Models\PointSetting;

class PointConfigureController extends Controller
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
        $data['points'] = PointConfigure::latest()->dokani()->paginate(25);
        $data['point'] = PointSetting::dokani()->first();
        return view('points.index', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        $data['point'] = PointSetting::dokani()->first();
        return view('points.create', $data);
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

        return redirect()->route('dokani.points.index')->withMessage('Point added success !');
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
        $data['point'] = PointConfigure::find($id);
        return view('points.edit', $data);
    }











    /*
     |--------------------------------------------------------------------------
     | EDIT METHOD
     |--------------------------------------------------------------------------
     */
    public function editPointValue(Request $request , $id){

        $this->pointValueStoreUpdate($request , $id);

        return redirect()->back()->withMessage('Point edit success !');
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

        return redirect()->route('dokani.points.index')->withMessage('Point edited success !');
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
            'point'            => 'required',
        ]);


        PointConfigure::updateOrCreate([
            'id'    => $id
        ], $data);
    }


    private function pointValueStoreUpdate($request, $id = null){

        PointSetting::updateOrCreate([
            'id'        => $id,
            'dokan_id'  => dokanId(),
        ],[
            'point_value'   => $request->point_value,
        ]);
    }




}
