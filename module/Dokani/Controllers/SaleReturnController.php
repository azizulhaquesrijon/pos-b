<?php

namespace Module\Dokani\Controllers;

use Illuminate\Http\Request;
use Module\Dokani\Models\Product;
use Illuminate\Support\Facades\DB;
use Module\Dokani\Models\Customer;
use App\Http\Controllers\Controller;
use Module\Dokani\Models\SaleReturn;
use Module\Dokani\Services\SaleReturnService;

class SaleReturnController extends Controller
{
    private $service;


    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct(SaleReturnService $saleReturnService)
    {
        $this->service = $saleReturnService;
    }












    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        $data['sale_returns'] = SaleReturn::dokani()->with('details')->latest()->paginate(25);
        return view('sales/return/index', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        $data['customers'] = Customer::dokani()->get();

        $data['products'] = Product::dokani()
            ->select('name', 'sell_price', 'id', 'unit_id', 'barcode')
            ->with('unit:name,id')
            ->has('sale_details')
            ->withCount(['sale_details as returnable_qty' => function ($query) {
                return $query->select(DB::raw('SUM(quantity)'));
            }])
            ->get();
        return view('sales.return.create', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $this->service->store($request);
                $this->service->saleDetails($request);
            });
        } catch (\Throwable $th) {
            redirectIfError($th, 1);
        }


        return redirect(route('dokani.sale-returns.index'))->withMessage('Sale return added successfully !');
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
        # code...
    }













    /*
     |--------------------------------------------------------------------------
     | UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function update($id, Request $request)
    {
        # code...
    }












    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTORY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        # code...
    }
}
