<?php


namespace Module\HRM\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Traits\Trycatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Module\HRM\Models\Holiday;

class HolidayController extends Controller
{


    use Trycatch;
    private $holiday;




    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        return $this->load(Holiday::dokani()
            ->searchByFields(['title','date','status'])
            ->latest()
            ->paginate(31));
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

                'title'             => 'required',
                'date'              => 'required',
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

            'data'      => $this->holiday,
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

                'title'             => 'required',
                'date'              => 'required',
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

            'data'      => $this->holiday,
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
            $holiday = Holiday::dokani()->find($id);

            $holiday->delete();
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

        $month = date("m",strtotime($request->date));

        $this->holiday = Holiday::updateOrCreate(
            [
                'id'            => $id,
            ],
            [
                'title'         => $request->title,
                'date'          => $request->date,
                'month'         => $month,
                'status'        => $request->status ?? 1,
                'dokan_id'      => auth()->user()->id,
                'created_by'    => auth()->user()->id,
            ]);


        return $this->holiday;
    }





}
