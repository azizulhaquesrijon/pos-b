<?php

namespace Module\Dokani\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Module\Dokani\Models\Category;
use Module\Dokani\Models\StockTransfer;
use Module\Dokani\Services\StockTransferService;

class StockTransferController extends Controller
{
    private $service;


    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct()
    {
        $this->service = new StockTransferService();
    }












    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        $data['stockTransfers'] = StockTransfer::paginate(25);
        $data['categories']     = Category::pluck('name', 'id');

        return view('branches/stock-transfers/index', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        
        return view('branches/stock-transfers/create');
    }













    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $this->service->validate($request);

        try {
            DB::transaction(function() use($request){
                
                $this->service->store($request);

                $this->service->storeDetails($request);
            });

        } catch (\Throwable $th) {
            throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
        return redirect()->route('dokani.stock-transfers.index')->with('message', 'Stock Transfer have been created success.');
    }
    












    /*
     |--------------------------------------------------------------------------
     | SHOW METHOD
     |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $data['stock_transfer'] = StockTransfer::with('details', 'from_branch', 'to_branch')->find($id);
        return view('branches/stock-transfers/view', $data);
    }
    












    /*
     |--------------------------------------------------------------------------
     | EDIT METHOD
     |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $data['stock_transfer'] = StockTransfer::with('details', 'from_branch', 'to_branch')->find($id);
        return view('branches.stock-transfers.edit', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function update($id, Request $request)
    {
        try {
            
            DB::transaction(function() use($id, $request){

                $this->service->update($id, $request);

                $this->service->updateDetails($request);

            });

        } catch (\Throwable $th) {
            
            return redirect()->back()->with('error', $th->getMessage());
        }
        return redirect()->route('dokani.stock-transfers.index')->with('message', 'Stock Transfer have been updated success.');
    }




    





    /*
     |--------------------------------------------------------------------------
     | APPROVE METHOD
     |--------------------------------------------------------------------------
    */
    public function approve($id)
    {
        $data['stock_transfer'] = StockTransfer::with('details', 'from_branch', 'to_branch')->find($id);
        
        return view('branches.stock-transfers.approve', $data);
    }






    /*
     |--------------------------------------------------------------------------
     | APPROVE SUBMIT METHOD
     |--------------------------------------------------------------------------
    */
    public function approveSubmit($id)
    {

        try {
            $this->service->approve($id);
        } catch (\Throwable $th) {
            throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }

        return redirect()->route('dokani.stock-transfers.index')->with('message', 'Stock Transfer have been approved.');
    }












    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTORY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        try {
            $stock_transfer = StockTransfer::find($id);
            
            if ($stock_transfer->is_approved) {
                return redirect()->back()->with('error', 'Sorry! You can not delete this stock transfer.');
            }

            DB::transaction(function() use($stock_transfer){

                $stock_transfer->details()->delete();
                $stock_transfer->delete();

            });

        } catch (\Throwable $th) {

            return redirect()->back()->with('error', 'Sorry! You can not delete this stock transfer.');

        }

        return redirect()->back()->with('message', 'Requested data successfully deleted.');

    }
}
