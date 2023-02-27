<?php

namespace Module\Dokani\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\FileSaver;
use Module\Dokani\Models\Supplier;
use Module\Dokani\Services\LedgerService;
use Mpdf\Tag\Sup;

class SupplierController extends Controller
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
        $data['suppliers'] = Supplier::latest()->dokani()->likeSearch('name')->paginate(25);
        return view('suppliers.index', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('suppliers.create');
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

        return redirect()->back()->withMessage('Supplier added success !');
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
        $data['supplier'] = Supplier::find($id);
        return view('suppliers.edit', $data);
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

        return redirect()->route('dokani.suppliers.index')->withMessage('Supplier edited success !');
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

                $supplier = Supplier::find($id);

                if (file_exists($supplier->image)) {
                    unlink($supplier->image);
                }

                $supplier->supplierLedgers->each->delete();

                $supplier->delete();

            });

        } catch (\Throwable $th) {
            return redirect()->back()->withError($th->getMessage());
        }

        return redirect()->route('dokani.suppliers.index')->withMessage('Supplier delete success !');
    }






    /*
     |--------------------------------------------------------------------------
     | STORE/UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    private function storeOrUpdate($request, $id = null)
    {
//        dd($request->all());
        $data = $request->validate([
            'name'          => 'required',
            'mobile'        => 'nullable',
            'address'       => 'nullable',
        ]);

        $data['opening_due'] =  $request->opening_due ?? 0;
        $data['balance']     = $request->balance;
        if (!$id){
            $data['balance'] = $request->opening_due;
        }



        $supplier = Supplier::updateOrCreate([
            'id'    => $id
        ], $data);

        $this->upload_file($request->image, $supplier, 'image', 'suppliers');
    }
}
