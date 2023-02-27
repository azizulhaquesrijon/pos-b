<?php

namespace Module\HRM\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Module\HRM\Models\Attendance;
use Module\HRM\Models\Employee;
use Carbon\Carbon;

class AttendanceController extends Controller
{


    public $status;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        $data['employees'] = Employee::dokani()
            ->with('attendance', function ($query) use ($request) {
                $query->where('date', $request->date ?? Carbon::now()->format('Y/m/d'));
            })
            ->latest()
            ->get();
        //dd($data);

        return view('attendance.index', $data);
    }











    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }












    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->status);
        $month = date("m",strtotime($request->date));
        try {
            foreach ($request->employee_id ?? [] as $key => $employee_id) {

                Attendance::updateOrCreate(
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
            return $th->getMessage();
        }

        return redirect()->back()->withMessage('Update successfully !');
    }












    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
