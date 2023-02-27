<?php


namespace Module\HRM\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Traits\Trycatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Module\HRM\Models\Advance;
use Module\HRM\Models\Employee;

class AdvanceController extends Controller
{

    use Trycatch;
    private $advance;





    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        return $this->load(Advance::dokani()
            ->searchByFields(['title','date','status'])
            ->with('employee')
            ->latest()
            ->paginate(25));
    }




    /*
        |--------------------------------------------------------------------------
        | STORE METHOD
        |--------------------------------------------------------------------------
       */
    public function store(Request $request)
    {

        try {

            $validator = Validator::make($request->all(), [

                'employee_id'       => 'required',
                'date'              => 'required',
                'amount'            => 'required',
            ]);

            if ($validator->fails()) {

                return response()->json([

                    'data'      => $validator->errors()->first(),
                    'message'   => "Validation Error",
                    'status'    => 0,
                ]);
            }

            $this->storeOrUpdate($request);


        } catch (\Throwable $th) {
            return response()->json([

                'data'      => $th->getMessage(),
                'message'   => "Server Error",
                'status'    => 1,
            ]);
        }

        return response()->json([

            'data'      => $this->advance,
            'message'   => "Success",
            'status'    => 1,
        ]);

    }







    /*
         |--------------------------------------------------------------------------
         | UPDATE METHOD
         |--------------------------------------------------------------------------
        */
    public function update(Request $request, $id)
    {
        try {

            $validator = Validator::make($request->all(), [

                'employee_id'       => 'required',
                'date'              => 'required',
                'amount'            => 'required',
            ]);

            if ($validator->fails()) {

                return response()->json([

                    'data'      => $validator->errors()->first(),
                    'message'   => "Validation Error",
                    'status'    => 0,
                ]);
            }

            $this->storeOrUpdate($request, $id);


        } catch (\Throwable $th) {
            return response()->json([

                'data'      => $th->getMessage(),
                'message'   => "Server Error",
                'status'    => 1,
            ]);
        }

        return response()->json([

            'data'      => $this->advance,
            'message'   => "Success",
            'status'    => 1,
        ]);
    }







    /*
        |--------------------------------------------------------------------------
        | DELETE/DESTORY METHOD
        |--------------------------------------------------------------------------
       */
    public function destroy($id)
    {
        try {
            $advance = Advance::dokani()->find($id);

            $advance->delete();
            return response()->json([

                'message'   => "Delete Success",
                'status'    => 1,
            ]);


        } catch (\Throwable $th) {
            return response()->json([

                'data'      => $th->getMessage(),
                'message'   => "Server Error",
                'status'    => 1,
            ]);
        }
    }









    private function storeOrUpdate($request , $id = null){

        $this->advance = Advance::updateOrCreate(
            [
                'id'            => $id,
            ],
            [
                'employee_id'   => $request->employee_id,
                'date'          => $request->date,
                'amount'        => $request->amount,
                'status'        => $request->status ?? 1,
                'dokan_id'      => auth()->user()->id,
                'created_by'    => auth()->user()->id,
            ]);

        return $this->advance;
    }





}
