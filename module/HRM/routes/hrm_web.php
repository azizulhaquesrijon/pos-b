<?php


use Illuminate\Support\Facades\Route;
use Module\HRM\Controllers\EmployeeController;
use Module\HRM\Controllers\AttendanceController;
use Module\HRM\Controllers\HolidayController;
use Module\HRM\Controllers\AdvanceController;
use Module\HRM\Controllers\SalaryController;
use Module\HRM\Controllers\PayrollController;

Route::group(['prefix' => 'dokani', 'as' => 'dokani.', 'middleware' => ['auth', 'permission']], function () {

    /*
   |--------------------------------------------------
   | Resources Route
   |--------------------------------------------------
   */
    Route::resources([
        'employees'         => EmployeeController::class,
        'attendance'        => AttendanceController::class,
        'holidays'          => HolidayController::class,
        'advances'          => AdvanceController::class,
        'salaries'          => SalaryController::class,
        'payrolls'          => PayrollController::class,
    ]);

//    Route::get('salary', [SalaryController::class,'getSalary'])->name('salaries.get-salary');
});
