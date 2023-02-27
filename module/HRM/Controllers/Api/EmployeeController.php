<?php


namespace Module\HRM\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\FileSaver;
use App\Traits\Trycatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Module\HRM\Models\Employee;
use Module\HRM\Services\EmployeeServices;

class EmployeeController extends Controller
{


    use Trycatch;
    use FileSaver;

    private $employee;
    private $service;






    /*
        |--------------------------------------------------------------------------
        | CONSTRUCTOR
        |--------------------------------------------------------------------------
       */
    public function __construct(EmployeeServices $employeeServices)
    {
        $this->service = $employeeServices;
    }






    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        return $this->load(Employee::dokani()->latest()->paginate(20));
    }




    /*
         |--------------------------------------------------------------------------
         | STORE METHOD
         |--------------------------------------------------------------------------
        */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [

                'name'              => 'required',
                'designation'       => 'required',
                'mobile'            => 'required',
                'salary'            => 'required',
                'joining_date'      => 'required',
            ]);

            if ($validator->fails()) {

                return response()->json([

                    'data'      => $validator->errors()->first(),
                    'message'   => "Validation Error",
                    'status'    => 0,
                ]);
            }

            DB::transaction(function () use ($request) {

                $request->validate([

                    'name'              => 'required',
                    'designation'       => 'required',
                    'mobile'            => 'required',
                    'salary'            => 'required',
                    'joining_date'      => 'required',
                ]);

                isset($request->isUser) ? $this->isUser = 1 : $this->isUser = 0;

                $this->employee = Employee::create(
                    [
                    'name'          => $request->name,
                    'designation'   => $request->designation,
                    'mobile'        => $request->mobile,
                    'address'       => $request->address,
                    'father_name'   => $request->father_name,
                    'salary'        => $request->salary,
                    'joining_date'  => $request->joining_date,
                    'is_user'       => $this->isUser,
                    'dokan_id'      => auth()->user()->id,
                    'created_by'    => auth()->user()->id,
                    'status'        => $request->status ?? 1,
                ]);

                $this->upload_file($request->image, $this->employee, 'image', 'assets/uploads/employee');
                $this->upload_file($request->nid_image, $this->employee, 'nid_image', 'assets/uploads/employee/nid');

                if ($request->is_user == 1){

                    $data = $request->validate([
                        'name'          => 'required',
                        'mobile'        => 'required',
                        'pin'           => 'required|digits:4',
                        'designation'   => 'nullable',
                    ]);



                    $data['type']               =  'user';
                    $data['employee_id']        =  $this->employee->id;

                    $data['dokan_id'] =  auth()->id();

                    $user = User::create($data);
                }
            });


        } catch (\Throwable $th) {
            return response()->json([

                'data'      => $th->getMessage(),
                'message'   => "Server Error",
                'status'    => 1,
            ]);
        }

        return response()->json([

            'message'   => "Success",
            'status'    => 1,
        ]);

    }






    /*
         |--------------------------------------------------------------------------
         | UPDATE METHOD
         |--------------------------------------------------------------------------
        */
    public function update(Request $request, $id)
    {


        try {
            $validator = Validator::make($request->all(), [

                'name'              => 'required',
                'designation'       => 'required',
                'mobile'            => 'required',
                'salary'            => 'required',
                'joining_date'      => 'required',
            ]);

            if ($validator->fails()) {

                return response()->json([

                    'data'      => $validator->errors()->first(),
                    'message'   => "Validation Error",
                    'status'    => 0,
                ]);
            }

            DB::transaction(function () use ($request, $id) {

                $request->validate([

                    'name'              => 'required',
                    'designation'       => 'required',
                    'mobile'            => 'required',
                    'salary'            => 'required',
                    'joining_date'      => 'required',
                ]);

                isset($request->isUser) ? $this->isUser = 1 : $this->isUser = 0;

                $this->employee = Employee::updateOrCreate([
                    'id'            => $id,
                ], [
                    'name'          => $request->name,
                    'designation'   => $request->designation,
                    'mobile'        => $request->mobile,
                    'address'       => $request->address,
                    'father_name'   => $request->father_name,
                    'salary'        => $request->salary,
                    'joining_date'  => $request->joining_date,
                    'is_user'       => $this->isUser,
                    'dokan_id'      => auth()->user()->id,
                    'created_by'    => auth()->user()->id,
                    'status'        => $request->status ?? 1,
                ]);

                $this->upload_file($request->image, $this->employee, 'image', 'assets/uploads/employee');
                $this->upload_file($request->nid_image, $this->employee, 'nid_image', 'assets/uploads/employee/nid');

                if ($request->is_user == 1){

                    $data = $request->validate([
                        'name'          => 'required',
                        'mobile'        => 'required',
                        'pin'           => 'required|digits:4',
                        'designation'   => 'nullable',
                    ]);



                    $data['type']               =  'user';
                    $data['employee_id']        =  $this->employee->id;

                    if (!$id) {
                        $data['dokan_id'] =  auth()->id();
                    }

                    $user = User::updateOrCreate([
                        'employee_id'    => $id
                    ], $data);
                }
            });

            return response()->json([

                'message'   => "Update Success",
                'status'    => 1,
            ]);


        } catch (\Throwable $th) {
            return response()->json([

                'data'      => $th->getMessage(),
                'message'   => "Server Error",
                'status'    => 1,
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
            $employee = Employee::dokani()->where('id',$id)
                ->with('user')
                ->first();

            if (file_exists($employee->image)) {
                unlink($employee->image);
            }
            if (file_exists($employee->nid_image)) {
                unlink($employee->nid_image);
            }
            $employee->delete();

            if ($employee->user){
                $employee->user->delete();
            }

            return response()->json([

                'message'   => "Delete Success",
                'status'    => 1,
            ]);


        } catch (\Throwable $th) {
            return response()->json([

                'data'      => $th->getMessage(),
                'message'   => "Server Error",
                'status'    => 1,
            ]);
        }
    }







}
