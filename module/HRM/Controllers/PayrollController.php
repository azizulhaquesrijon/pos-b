<?php

namespace Module\HRM\Controllers;

use App\Http\Controllers\Controller;;
use Illuminate\Http\Request;
use Module\HRM\Models\Payroll;

class PayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $data['payroll'] = Payroll::dokani()->where('dokan_id', auth()->user()->id)->first();

        return view('payroll.index', $data);
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
        try {
            Payroll::updateOrCreate(
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

            return redirect()->back()->withMessage($th->getMessage());
        }

        return redirect()->back()->withMessage('Success ! ');
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
