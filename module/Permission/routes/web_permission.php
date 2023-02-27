<?php

use App\Console\Commands\ModuleController;
use Illuminate\Support\Facades\Route;
use Module\Permission\Controllers\ParentPermissionController;
use Module\Permission\Controllers\PermissionController;
use Module\Permission\Controllers\SubmoduleController;
use Module\Permission\Controllers\UserPermissionController;

// user permission routes [akash]
Route::group(['prefix' => 'setting'], function () {

    Route::resource('parent-permissions',           ParentPermissionController::class);
    Route::resource('modules',                      ModuleController::class);

    Route::get('active-deactive-module/{module}',   [ModuleController::class, 'activeDeactive'])->name('active.deactive.module');
    Route::resource('submodules',                   SubmoduleController::class);
    Route::resource('permissions',                  PermissionController::class);

    Route::resource('permission-access',            UserPermissionController::class);

    Route::get('permission-access/create/{id}',     [UserPermissionController::class, 'index'])->name('load.existing.users.permission');
    Route::get('select/employee/list',              [UserPermissionController::class, 'employee_list'])->name('employee_list');
    Route::get('permitted/employee/list',           [UserPermissionController::class, 'permittedEmployeeList'])->name('permitted.employee.list');

    Route::get('permission-access-employee',        [UserPermissionController::class, 'employeePermission'])->name('permission-access.employee');
    Route::post('permission-access-employee',       [UserPermissionController::class, 'employeePermissionStore'])->name('permission-access.employee.store');
});




Route::get('setting/views-permitted-users',          [PermissionController::class, 'iew_permitted_users'])->name('permitted.users');
Route::get('user/{id}/status/{status}',             [PermissionController::class, 'userChangeStatus'])->name('user.active.deactive');
Route::delete('setting/permitted-users/delete/{user}', [PermissionController::class, 'permittedUserDelete'])->name('permitted.user.delete');
Route::get('/permitted-users/{id}/edit',            [UserPermissionController::class, 'edit'])->name('edit.permitted.users');
Route::put('/update-permitted/{id}/users',          [UserPermissionController::class, 'update'])->name('update.permission.access');
