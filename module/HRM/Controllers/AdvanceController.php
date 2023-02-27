<?php

namespace Module\HRM\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Module\HRM\Models\Advance;
use Module\HRM\Models\Employee;

class AdvanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['advances'] = Advance::dokani()
            ->searchByFields(['date','status'])
            ->searchFromRelation('employee','name')
            ->with('employee')
            ->latest()
            ->paginate(25);

        return view('advance.index',$data);
    }









    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['employees'] = Employee::dokani()->get();

        return view('advance.create', $data);
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

            return redirect()->route('dokani.advances.index')->withMessage($th->getMessage());
        }

        return redirect()->route('dokani.advances.index')->withMessage('Success ! ');
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
        $data['advance'] = Advance::dokani()->find($id);
        $data['employees'] = Employee::dokani()->get();

        return view('advance.edit',$data);
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

            return redirect()->route('dokani.advances.index')->withMessage($th->getMessage());
        }

        return redirect()->route('dokani.advances.index')->withMessage('Success ! ');
    }












    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $advance = Advance::dokani()->find($id);

        $advance->delete();

        return redirect()->route('dokani.advances.index')->withMessage('Delete Success ! ');
    }







    private function storeOrUpdate($request , $id = null){

        Advance::updateOrCreate(
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
    }
}
