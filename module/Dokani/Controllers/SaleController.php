<?php

namespace Module\Dokani\Controllers;

use Illuminate\Http\Request;
use Module\Dokani\Models\CusArea;
use Module\Dokani\Models\CusCategory;
use Module\Dokani\Models\PointSetting;
use Module\Dokani\Models\Product;
use Illuminate\Support\Facades\DB;
use Module\Dokani\Models\Customer;
use App\Http\Controllers\Controller;
use Module\Dokani\Models\BusinessSetting;
use Module\Dokani\Models\Category;
use Module\Dokani\Models\Sale;
use App\Models\User;
use Module\Dokani\Models\Branch;
use Module\Dokani\Models\Courier;
use Module\Dokani\Services\SaleService;
use Module\HRM\Models\Employee;

class SaleController extends Controller
{
    private $service;


    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct(SaleService $saleService)
    {
        $this->service = $saleService;
    }












    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        $data['sales']  = Sale::with('customer')
            ->latest()
            ->dokani()
            ->dateFilter()
            ->searchByField('courier_id')
            ->searchByBranch('branch_id')
            ->searchFromRelation('customer','name')
            ->likeSearch('invoice_no')
            ->paginate(25);

            $data['couriers']   = Courier::dokani()->get();

            if(auth()->user()->type == 'owner'){
                $data['branches'] = $branches = Branch::dokani()->with('employee')->get();
            }else{
                $data['branches'] = $branches = Branch::dokani()->whereHas('users',fn($q)=>$q->where('user_id',auth()->user()->id))->with('employee')->get();
            }

        return view('sales/index', $data);
    }




    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD        == POS Sale
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        $data['categories'] = Category::dokani()->pluck('name', 'id');
        $data['invoice']    = BusinessSetting::where('user_id', User::first()->id)->first();
        $data['customers']  = Customer::dokani()->getCustomer()->get();
        $data['areas']      = CusArea::dokani()->get();
        $data['cus_categories'] = CusCategory::dokani()->get();
        $data['users']      = User::whereDokanId(auth()->id())->get();
        $data['couriers']   = Courier::dokani()->get();
        $data['point']      = PointSetting::dokani()->first();

        if(auth()->user()->type == 'owner'){
            $data['branches'] = $branches = Branch::dokani()->with('employee')->get();
        }else{
            $data['branches'] = $branches = Branch::dokani()->whereHas('users',fn($q)=>$q->where('user_id',auth()->user()->id))->with('employee')->get();
            if($data['branches']->isEmpty()){
                return redirect()->back()->with('error','You do not have any access to any branch');
            }

        }
        $data['employees']   = Employee::dokani()
                                ->when(!$branches->isEmpty(), function($q) use($branches){
                                    $q->where('branch_id',$branches->first()->id);
                                })->get();

        $data['business_setting'] = BusinessSetting::whereUserId(auth()->user()->type == "owner" ? auth()->user()->id : auth()->user()->dokan_id)->first();
        $data['products']         = Product::dokani()->with('unit')
                                    ->withCount(['stocks as available_qty' => function ($query) use($branches) {
                                        $query->when(!$branches->isEmpty(), function($q) use($branches){
                                            $q->where('branch_id', auth()->user()->type == 'owner' ? null : $branches->first()->id)
                                            ->select(DB::raw('SUM(available_quantity)'));
                                        })
                                        ->when($branches->isEmpty(), function($q) {
                                            $q->where('branch_id', null)
                                            ->select(DB::raw('SUM(available_quantity)'));
                                        });
                                    }])
                                    ->where('product_type', 1)
                                    ->latest()
                                    ->paginate(25);

        return view('sales/sales/create-pos', $data);
    }


    /*
     |--------------------------------------------------------------------------
     | CREATE Sale METHOD       == NEW SALE
     |--------------------------------------------------------------------------
    */
    public function createSale()
    {

        $data['invoice']          = BusinessSetting::where('user_id', User::first()->id)->first();
        $data['customers']        = Customer::dokani()->getCustomer()->get();
        $data['areas']            = CusArea::dokani()->get();
        $data['cus_categories']   = CusCategory::dokani()->get();
        $data['users']            = User::whereDokanId(auth()->id())->get();
        $data['couriers']         = Courier::dokani()->get();
        $data['point']            = PointSetting::dokani()->first();
        $data['business_setting'] = BusinessSetting::whereUserId(auth()->id())->first();

        if(auth()->user()->type == 'owner'){
            $data['branches'] = $branches = Branch::dokani()->with('employee')->get();
        }else{
            $data['branches'] = $branches = Branch::dokani()
                                ->whereHas('users',fn($q)=>$q->where('user_id',auth()->user()->id))
                                ->with('employee')
                                ->get();
        }
        $data['employees']   = Employee::dokani()
                                ->when(!$branches->isEmpty(), function($q) use($branches){
                                    $q->where('branch_id',$branches->first()->id);
                                })->get();



        return view('sales/sales/new-sale', $data);
    }












    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        // dd($request->all());
        try {
            // if ($request->customer_id == null){
            //     return redirect()->back()->with('error','Select Customer');
            // }
            // if (!isset($request->branch_id)){
            //     return redirect()->back()->with('error','Select Branch First');
            // }
            if($request->product_ids){
                DB::transaction(function () use ($request) {
                    $this->service->store($request);
                    $this->service->saleDetails($request);
                });
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }

        if($this->service->sale){
            $url = route('dokani.sales.show', $this->service->sale->id) . '?type=' . $request->invoice_type;
            return redirect($url)->with('success','Sale Created Successfully');
        }else{
            return redirect()->back()->with('error','Sale Not Created');
        }
    }













    /*
     |--------------------------------------------------------------------------
     | SHOW METHOD
     |--------------------------------------------------------------------------
    */
    public function show($id, Request $request)
    {
        try {
            $view = $request->type == 'pos-invoice' ? 'sales.pos-invoice' : 'sales.show';
            $data['sale'] = Sale::query()->with('details','account','multi_accounts.account','branch','employee')->find($id);
//             dd($data['sale']->multi_accounts);
            $data['business_settings'] = BusinessSetting::query()->where('user_id',dokanId())->first();

            if(auth()->user()->type != 'owner'){
                $data['branch'] = Branch::dokani()
                                        ->whereHas('users',fn($q)=>$q->where('user_id',auth()->user()->id))
                                        ->first();
            }

            return view($view, $data);
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
        $data['sale'] = Sale::dokani()
            ->where('id',$id)
            ->with('sale_details.product.unit','customer')
            ->first();
        $data['customers']  = Customer::dokani()->getCustomer()->get();
        $data['users']      = User::whereDokanId(auth()->id())->get();
        $data['point']      = PointSetting::dokani()->first();
        $data['couriers']   = Courier::dokani()->get();
//        dd($data);
        return view('sales.sales.edit',$data);
    }













    /*
     |--------------------------------------------------------------------------
     | UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {

        try {
            DB::transaction(function () use ($request , $id) {

                $this->service->saleEdit($request ,$id);
                $this->service->saleDetailsEdit($request ,$id);

            });
        } catch (\Throwable $th) {
            redirectIfError($th, 1);
        }

        $url = route('dokani.sales.show', $this->service->sale->id) . '?type=' . $request->invoice_type;

        return redirect($url);
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
                $this->service->saleDelete($id);
            });

            return redirect()->route('dokani.sales.index')->withMessage('Sale deleted successfully !');
        } catch (\Throwable $th) {

            return redirect()->back()->withError($th->getMessage());
        }
    }
}
