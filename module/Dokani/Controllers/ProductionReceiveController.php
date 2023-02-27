<?php

namespace Module\Dokani\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Dokani\Models\BusinessSetting;
use Module\Dokani\Models\ProductionReceive;
use Module\Dokani\Services\ProductionReceiveService;

class ProductionReceiveController extends Controller
{
    private $productionReceiveService;


    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct(ProductionReceiveService $productionReceiveService)
    {
        $this->productionReceiveService = $productionReceiveService;
    }


    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        $data['production_issue']  = ProductionReceive::latest()
            ->dokani()
            // ->dateFilter()
            // ->searchByField('courier_id')
            // ->searchFromRelation('customer','name')
            // ->likeSearch('invoice_no')
            ->paginate(25);

        return view('productions/production-receive/index', $data);
    }




    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {

        try {
            return view('productions/production-receive/create');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }



    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        try {

            if ($request->product_ids){
                $this->productionReceiveService->store($request);
            }else{
                return redirect()->back()->with('error',"Select Product First");
            }

            return redirect()->back()->with('success',"Production Issue Success");

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }



    /*
     |--------------------------------------------------------------------------
     | SHOW METHOD
     |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        try {
            $data['production_receive'] = ProductionReceive::with('production_receive_details.product')->find($id);
            $data['business_settings']  = BusinessSetting::query()->where('user_id',dokanId())->first();

            return view('productions/production-receive/show', $data);
        } catch (\Throwable $th) {
             return back()->withError($th->getMessage());
        }
    }



    /*
     |--------------------------------------------------------------------------
     | EDIT METHOD
     |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
    //         $data['sale'] = Sale::dokani()
    //             ->where('id',$id)
    //             ->with('sale_details.product.unit','customer')
    //             ->first();
    //         $data['customers']  = Customer::dokani()->getCustomer()->get();
    //         $data['users']      = User::whereDokanId(auth()->id())->get();
    //         $data['point']      = PointSetting::dokani()->first();
    //         $data['couriers']   = Courier::dokani()->get();
    // //        dd($data);
    //         return view('sales.sales.edit',$data);
        }


    /*
     |--------------------------------------------------------------------------
     | UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        //
    }





    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTORY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        try {
            $this->productionReceiveService->delete($id);

            return redirect()->route('dokani.production-receives.index')->withMessage('Production Receive deleted successfully !');
        } catch (\Throwable $th) {

            return redirect()->back()->withError($th->getMessage());
        }
    }
}
