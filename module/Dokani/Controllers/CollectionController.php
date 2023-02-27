<?php

namespace Module\Dokani\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Module\Dokani\Models\BusinessSetting;
use Module\Dokani\Models\CashFlow;
use Module\Dokani\Models\Customer;
use App\Http\Controllers\Controller;
use Module\Dokani\Models\Collection;
use Module\Dokani\Models\CustomerLedger;
use Module\Dokani\Requests\CollectionRequest;
use Module\Dokani\Services\AccountService;
use Module\Dokani\Services\LedgerService;

class CollectionController extends Controller
{
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
        $data['collections'] = Collection::dokani()->searchFromRelation('customer', 'name')->paginate(25);
        return view('sales/collection/index', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        $data['customers'] = Customer::dokani()->get();

        return view('sales.collection.create', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(CollectionRequest $request)
    {
//        dd($request->all());
        try {
            $request->store();
        } catch (\Throwable $th) {
            redirectIfError($th);
        }
        return redirect()->back()->with('message', 'Collection successfull');
    }













    /*
     |--------------------------------------------------------------------------
     | SHOW METHOD
     |--------------------------------------------------------------------------
    */
    public function show($id)
    {

        $data['collection'] = Collection::dokani()->with(['customer','account'])->find($id);
        $data['business_settings'] = BusinessSetting::query()->where('user_id',dokanId())->first();

        return view('sales.collection.show', $data);

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
        DB::transaction(function () use ($id) {

            $collection = Collection::find($id);

            $customer   = Customer::find($collection->customer_id);
            $customer->increment('balance', $collection->paid_amount);

            (new AccountService())->decreaseBalance($collection->account_id, $collection->paid_amount);

            $ledger = CustomerLedger::dokani()->where('source_type','Collection')->where('source_id',$id)->first();
            $cash_flow = CashFlow::dokani()->where('transactionable_type','Collection')->where('transactionable_id',$id)->first();
            $cash_flow->delete();
            $ledger->delete();

            $collection->delete();

        });

        return redirect()->back()->with('message', 'Successfully Deleted');

    }
}
