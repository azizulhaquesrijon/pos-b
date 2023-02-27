<?php


namespace Module\HRM\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Traits\Trycatch;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Module\HRM\Models\Attendance;
use Module\HRM\Models\Employee;

class AttendanceController extends Controller
{

    use Trycatch;

    private $attendance;

    /*
         |--------------------------------------------------------------------------
         | INDEX METHOD
         |--------------------------------------------------------------------------
        */
    public function index(Request $request)
    {
        return $this->load(Employee::dokani()
            ->with('attendance', function ($query) use ($request) {
                $query->where('date', $request->date ?? Carbon::now()->format('Y/m/d'));
            })
            ->latest()
            ->paginate(50));
    }









    /*
         |--------------------------------------------------------------------------
         | STORE METHOD
         |--------------------------------------------------------------------------
        */
    public function store(Request $request)
    {
//        return response()->json([
//
//            'data'      => $request->all(),
//        ]);
        try {
            $month = date("m",strtotime($request->date));
            foreach ($request->employee_id ?? [] as $key => $employee_id) {

                $this->attendance = Attendance::updateOrCreate(
                    [
                        'employee_id'   => $employee_id,
                        'date'          => $request->date,
                    ],
                    [
                        'employee_id' => $employee_id,
                        'date' => $request->date,
                        'month' => $month,
                        'ot_hour' => $request->ot_hour[$key],
                        'status' => $request->status[$key],
                        'dokan_id' => auth()->user()->id,
                        'created_by' => auth()->user()->id,
                    ]);

            }


        } catch (\Throwable $th) {
            return response()->json([

                'data'      => $th->getMessage(),
                'message'   => "Server Error",
                'status'    => 1,
            ]);
        }

        return response()->json([

            'data'      => $this->attendance,
            'message'   => "Success",
            'status'    => 1,
        ]);

    }



}
