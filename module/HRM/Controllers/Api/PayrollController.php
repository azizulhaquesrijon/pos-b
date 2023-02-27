<?php


namespace Module\HRM\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Traits\Trycatch;
use Illuminate\Http\Request;
use Module\HRM\Models\Payroll;

class PayrollController extends Controller
{

    use Trycatch;
    private $payroll;

    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        return $this->load(Payroll::dokani()->where('dokan_id', auth()->user()->id)->first());
    }







    /*
         |--------------------------------------------------------------------------
         | UPDATE METHOD
         |--------------------------------------------------------------------------
        */
    public function update(Request $request)
    {
        try {
            $this->payroll = Payroll::updateOrCreate(
                [
                    'dokan_id'            => auth()->user()->id,
                ],
                [
                    'start_time'         => $request->start_time,
                    'end_time'           => $request->end_time,
                    'working_hours'      => $request->working_hours,
                    'dokan_id'           => auth()->user()->id,
                    'created_by'         => auth()->user()->id,
                ]);
        }
        catch (\Throwable $th){

            return response()->json([

                'data'      => $th->getMessage(),
                'message'   => "Server Error",
                'status'    => 1,
            ]);
        }

        return response()->json([

            'data'      => $this->payroll,
            'message'   => "Success",
            'status'    => 1,
        ]);
    }

}
