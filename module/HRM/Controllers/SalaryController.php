<?php

namespace Module\HRM\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Module\HRM\Models\Employee;
use Module\HRM\Models\Holiday;
use Module\HRM\Models\Payroll;
use Module\HRM\Models\Salary;

class SalaryController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['salaries'] = Salary::dokani()
            ->searchByField('date')
            ->with('employee')
            ->latest()
            ->paginate(20);

        return view('salary.index', $data);
    }









    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $date = $request->date ?? Carbon::now()->format('Y/m');
        $data['getMonth'] = substr($request->date,5);
        if ($data['getMonth'] == false){
            $data['getMonth'] = Carbon::now()->format('m');
        }
        //dd($data['getMonth']);
        $data['employees'] = Employee::dokani()->get();
        $data['payroll'] = Payroll::dokani()->first();
        $data['holidays'] = Holiday::dokani()->where('month',$data['getMonth'])->count();
        $data['date'] = $request->date;
        return view('salary.create', $data);
    }




//    public function getSalary(Request $request)
//    {
//
//        $date = $request->date ?? Carbon::now()->format('Y/m');
//        $data['getMonth'] = substr($request->date,5);
//        if ($data['getMonth'] == false){
//            $data['getMonth'] = Carbon::now()->format('m');
//        }
//        //dd($data['getMonth']);
//        $data['employees'] = Employee::dokani()->get();
//        $data['payroll'] = Payroll::dokani()->first();
//        $data['holidays'] = Holiday::dokani()->where('month',$data['getMonth'])->count();
//        $data['date'] = $request->date;
//        return view('salary.create', $data);
//    }






    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id = null)
    {
        //return $request->alldata;
        $salaries = json_decode($request->alldata);

        foreach ($salaries as $item){

            //return $item->total_days;
             Salary::updateOrCreate([
                'id'            => $id,
            ], [
                'employee_id'                   => $item->employee_ids,
                'date'                          => $request->date,
                'total_payable_salary'          => $request->total_payable_salary,
                'total_days'                    => $item->total_days,
                'working_days'                  => $item->working_days,
                'total_present'                 => $item->total_present,
                'salary'                        => $item->salary,
                'payable_salary'                => $item->payable_salary,
                'total_absent'                  => $item->total_absent,
                'total_off_days'                => $item->total_off_days,
                'total_leave'                   => $item->total_leave,
                'advance'                       => $item->advance,
                'ot_hours'                      => $item->ot_hours,
                'ot_amounts'                    => $item->ot_amounts,
                'dokan_id'                      => auth()->user()->id,
                'created_by'                    => auth()->user()->id,
            ]);
        }

        return redirect()->route('dokani.salaries.index')->withMessage('Add Success ! ');
    }












    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $salary = Salary::dokani()->find($id);
        $data['salaries'] = Salary::dokani()->where('date',$salary->date)->with('employee')->get();

        $data['salary'] = Salary::dokani()->where('id',$id)->select('date')->first();

        return view('salary.print', $data);
    }
















    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }





//    public function printSalary($id){
//        dd($id);
//        $data['salary'] = Salary::dokani()->where('date',Carbon::now()->format('Y/m'))->with('employee')->get();
//
//
//        return view('salary.print',$data);
//    }







    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }












    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }



}
