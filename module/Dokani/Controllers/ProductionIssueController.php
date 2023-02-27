<?php

namespace Module\Dokani\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Dokani\Models\BusinessSetting;
use Module\Dokani\Models\ProductionIssue;
use Module\Dokani\Services\ProductionIssueService;

class ProductionIssueController extends Controller
{
    private $productionIssueService;


    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct(ProductionIssueService $productionIssueService)
    {
        $this->productionIssueService = $productionIssueService;
    }












    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        $data['production_issue']  = ProductionIssue::latest()
                                    ->dokani()
                                    ->paginate(25);

        return view('productions/production-issue/index', $data);
    }




    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {

        try {
            return view('productions/production-issue/create');
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
                $this->productionIssueService->store($request);
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
            $data['production_issue'] = ProductionIssue::with('production_issue_details')->find($id);
            $data['business_settings'] = BusinessSetting::query()->where('user_id',dokanId())->first();

            return view('productions/production-issue/show', $data);
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
            // DB::transaction(function () use ($id) {
                $this->productionIssueService->delete($id);
            // });

            return redirect()->back()->withMessage('Production deleted successfully !');
        } catch (\Throwable $th) {

            return redirect()->back()->withError($th->getMessage());
        }
    }
}
