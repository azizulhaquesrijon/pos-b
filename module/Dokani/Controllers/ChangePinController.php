<?php

namespace Module\Dokani\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ChangePinController extends Controller
{




    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct()
    {
    }









    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {

        return view('change-pin.index');
    }







    /*
     |--------------------------------------------------------------------------
     | UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function update(Request $request)
    {
        $id = auth()->user()->id;
        $user = User::find($id);
        $request->validate([

            'new_pin'      => 'required|size:8',
        ]);

        if ($user->pin == $request->old_pin){

            if ($request->new_pin == $request->confirm_pin){

                User::updateOrCreate(['id'    => $id], ['pin' =>  $request->new_pin]);

            }
            else{
                return redirect()->back()->withError('New pin & confirm pin different !');
            }
        }
        else{
            return redirect()->back()->withError('Pin dont Matched !');
        }

        return redirect()->route('home')->withMessage('Pin update success !');
    }


}
