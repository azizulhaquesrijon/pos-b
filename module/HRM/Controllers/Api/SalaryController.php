<?php


namespace Module\HRM\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Traits\Trycatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Module\HRM\Models\Salary;

class SalaryController extends Controller
{


    use Trycatch;
    private $salary;

    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        return $this->load(Salary::dokani()
            ->searchByField('date')
            ->with('employee')
            ->latest()
            ->paginate(20));
    }






    /*
        |--------------------------------------------------------------------------
        | STORE METHOD
        |--------------------------------------------------------------------------
       */
    public function store(Request $request, $id = null)
    {

        try {
            foreach ($request->employee_id ?? [] as $key => $employee_id) {

                //return $item->total_days;
                $this->salary = Salary::updateOrCreate([
                    'id'            => $id,
                    'date'          => $request->date,
                ], [
                    'employee_id'                   => $employee_id,
                    'date'                          => $request->date,
                    'total_payable_salary'          => $request->total_payable_salary,
                    'total_days'                    => $request->total_days[$key],
                    'working_days'                  => $request->working_days[$key],
                    'total_present'                 => $request->total_present[$key],
                    'salary'                        => $request->salary[$key],
                    'payable_salary'                => $request->payable_salary[$key],
                    'total_absent'                  => $request->total_absent[$key],
                    'total_off_days'                => $request->total_off_days[$key],
                    'total_leave'                   => $request->total_leave[$key],
                    'advance'                       => $request->advance[$key],
                    'ot_hours'                      => $request->ot_hours[$key],
                    'ot_amounts'                    => $request->ot_amounts[$key],
                    'dokan_id'                      => auth()->user()->id,
                    'created_by'                    => auth()->user()->id,
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

            'data'      => $this->salary,
            'message'   => "Success",
            'status'    => 1,
        ]);

    }

}
