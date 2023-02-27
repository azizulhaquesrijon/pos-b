<?php


namespace Module\HRM\Services;


use App\Models\User;
use App\Traits\FileSaver;
use Module\HRM\Models\Employee;

class EmployeeServices
{

    use FileSaver;
    public $employee;
    public $isUser;

    public function storeOrUpdate($request , $id = null){

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
            'dokan_id'      => dokanId(),
            'created_by'    => auth()->user()->id,
            'branch_id'     => $request->branch_id,
            'status'        => $request->status ?? 1,
        ]);

        $this->upload_file($request->image, $this->employee, 'image', 'assets/uploads/employee');
        $this->upload_file($request->nid_image, $this->employee, 'nid_image', 'assets/uploads/employee/nid');

        return $this->employee;
    }



    public function isUser($request , $id = null){

        $data = $request->validate([
            'name'          => 'required',
            'mobile'        => 'required',
            'pin'           => 'required',
            'designation'   => 'nullable',
        ]);



        $data['type']               =  'user';
        $data['employee_id']        =  $this->employee->id;

        if (!$id) {
            $data['dokan_id'] =  auth()->id();
        }

        $user = User::updateOrCreate([
            'employee_id'    => $this->employee->id
        ], $data);

    }
}
