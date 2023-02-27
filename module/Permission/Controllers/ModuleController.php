<?php

namespace Module\Permission\Controllers;

use App\Http\Controllers\Controller;

use Module\Permission\Models\Module;
use Exception;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modules = Module::paginate(30);
        return view('module', compact('modules'));
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
        $request->validate(['name' => 'required|unique:modules,name']);

        try {
            Module::create(['name' => $request->name]);

            return redirect()->route('modules.index')->with('message', 'Module Create Successfull');
        } catch (Exception $ex) {
            return $ex->getMessage();
            return redirect()->back()->with('error', 'Some error, please check');
        }
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

    // active and deactive module
    public function activeDeactive(Module $module)
    {
        if ($module->status == 1)
        {
            $module->update(['status'=>0]);
            return redirect()->back()->with('message', $module->name . ' De-ctivated successfully');
        } else {
            $module->update(['status'=>1]);
            return redirect()->back()->with('message', $module->name . ' Activated successfully');
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Module $module)
    {
        $modules = Module::orderBy('name')->paginate(30);
        return view('setting.module', compact('modules', 'module'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Module $module)
    {
        $data = $request->validate(['name' => 'required|unique:modules,name,' . $module->id]);

        try {
            $module->update(['name' => $request->name]);

            return redirect()->route('modules.index')->with('message', 'Module Update Successfull');
        } catch (Exception $ex) {
            return redirect()->route('modules.index')->with('error', 'Some error, please check');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Module $module)
    {
        try {
            $module->delete();
            return redirect()->route('modules.index')->with('message', 'Module deleted Successfull');
        } catch (Exception $ex) {
            return redirect()->back()->with('error', 'Some error, please check');
        }
    }
}
