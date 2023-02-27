<?php

namespace Module\HRM\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Module\HRM\Models\Holiday;

class HolidayController extends Controller
{




    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['holidays'] = Holiday::dokani()
            ->searchByFields(['title','date','status'])
            ->latest()
            ->paginate(31);

        return view('holiday.index',$data);
    }









    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('holiday.create');
    }










    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $this->storeOrUpdate($request);
        }
        catch (\Throwable $th){

            return redirect()->route('dokani.holidays.index')->withMessage($th->getMessage());
        }

        return redirect()->route('dokani.holidays.index')->withMessage('Success ! ');
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
        $data['holiday'] = Holiday::dokani()->find($id);

        return view('holiday.edit',$data);
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
            $this->storeOrUpdate($request, $id);
        }
        catch (\Throwable $th){

            return redirect()->route('dokani.holidays.index')->withMessage($th->getMessage());
        }

        return redirect()->route('dokani.holidays.index')->withMessage('Success ! ');
    }












    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $holiday = Holiday::dokani()->find($id);

        $holiday->delete();

        return redirect()->route('dokani.holidays.index')->withMessage('Delete Success ! ');
    }







    private function storeOrUpdate($request , $id = null){

        $month = date("m",strtotime($request->date));

        Holiday::updateOrCreate(
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
    }



}
