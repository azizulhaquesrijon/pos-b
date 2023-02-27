<?php


namespace Module\Dokani\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\FileSaver;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class UserController extends Controller
{

    use FileSaver;

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
        $data['users'] = User::latest()->whereDokanId(auth()->id())->paginate(25);
        return $data;
    }



    /*
    |--------------------------------------------------------------------------
    | STORE METHOD
    |--------------------------------------------------------------------------
   */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'name'          => 'required',
            'mobile'        => 'required',
            'pin'           => 'required|digits:4',
            'designation'   => 'nullable',
        ]);

        if ($validator->fails()) {

            return response()->json([

                'data'      => $validator->errors()->first(),
                'message'   => "Validation Error",
                'status'    => 0,
            ]);
        }
        try {



            $user = User::create($request->except('image'));

            if ($user) {
                return response()->json([

                    'data'      => $user,
                    'message'   => "Success",
                    'status'    => 1,
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([

                'data'      => $th->getMessage(),
                'message'   => "Server Error",
                'status'    => 0,
            ]);
        }
    }



    /*
     |--------------------------------------------------------------------------
     | SHOW METHOD
     |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        return response()->json([

            'data'      => User::find($id),
            'message'   => "Success",
            'status'    => 1,
        ]);
    }





    /*
     |--------------------------------------------------------------------------
     | UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function update($id, Request $request)
    {

        $validator = Validator::make($request->all(),[
            'name'          => 'required',
            'mobile'        => 'required',
            'pin'           => 'required|digits:4',
            'designation'   => 'nullable',
        ]);
        if ($validator->fails()) {

            return response()->json([

                'data'      => $validator->errors()->first(),
                'message'   => "Validation Error",
                'status'    => 0,
            ]);
        }
        try {
            $user = $this->storeOrUpdate($request, $id);
            if ($user) {

                return response()->json([

                    'data'      => $user,
                    'message'   => "Success",
                    'status'    => 1,
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([

                'data'      => $th->getMessage(),
                'message'   => "Server Error",
                'status'    => 0,
            ]);
        }
    }






    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTORY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        try {
            $user = User::find($id);
            if (file_exists($user->image)) {
                unlink($user->image);
            }
            $user->delete();
            return response()->json([

                'data'      => 'User delete success',
                'message'   => "Success",
                'status'    => 1,
            ]);
        } catch (\Throwable $th) {
            return response()->json([

                'data'      => 'User was used another table',
                'message'   => "Server Error",
                'status'    => 0,
            ]);
        }

    }




    /*
     |--------------------------------------------------------------------------
     | STORE/UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    private function storeOrUpdate($request, $id = null)
    {
        $data = $request->all();

        $user = User::updateOrCreate([
            'id'    => $id
        ], $data);

        $this->upload_file($request->image, $user, 'image', 'users');
    }

}
