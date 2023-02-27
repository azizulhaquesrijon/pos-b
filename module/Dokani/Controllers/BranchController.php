<?php

namespace Module\Dokani\Controllers;

use App\Traits\FileSaver;
use Illuminate\Http\Request;
use Module\Dokani\Models\Branch;
use App\Http\Controllers\Controller;

class BranchController extends Controller
{
    use FileSaver;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['branches'] =  Branch::query()->dokani()->paginate(25);
        return view('branches.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('branches.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
        ]);

        try {

            $this->updateOrCreate($request, null);

        } catch (\Throwable $th) {

            return redirect()->back()->with('error', $th->getMessage());

        }
        return redirect()->back()->with('message', 'Branch have been created.');
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
        $branch = Branch::find($id);
        return view('branches.edit', compact('branch'));
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

            $this->updateOrCreate($request, $id);

        } catch (\Throwable $th) {

            return redirect()->back()->with('error', $th->getMessage());

        }
        return redirect()->back()->with('message', 'Branch have been created.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            Branch::destroy($id);

        } catch (\Throwable $th) {

            return redirect()->back()->with('error', 'You can not delete this branch.');

        }
        return redirect()->back()->with('message', 'Branch have been deleted.');
    }





    public function updateOrCreate($request, $id = null)
    {
        $branch = Branch::updateOrCreate([
            'id'            => $id
        ], [
            'name'          => $request->name,
            'address'       => $request->address,
            'phone_number'  => $request->phone_number,
            'bin_no'        => $request->bin_no,
            'short_name'    => $request->short_name,
            'email'         => $request->email,
        ]);

        $this->upload_file($request->image, $branch, 'image', 'branches');

    }
}