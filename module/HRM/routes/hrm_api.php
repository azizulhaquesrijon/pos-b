<?php

use Illuminate\Support\Facades\Route;
use Module\HRM\Controllers\Api\EmployeeController;
use Module\HRM\Controllers\Api\AttendanceController;
use Module\HRM\Controllers\Api\HolidayController;
use Module\HRM\Controllers\Api\AdvanceController;
use Module\HRM\Controllers\Api\SalaryController;
use Module\HRM\Controllers\Api\PayrollController;

Route::group(['prefix' => 'hrm', 'middleware' => ['auth:sanctum']], function () {


    //Employee route
    Route::get('employees',[EmployeeController::class,'index']);
    Route::post('employees',[EmployeeController::class,'store']);
    Route::post('employees/{id}',[EmployeeController::class,'update']);
    Route::delete('employees/{id}',[EmployeeController::class,'destroy']);


    //Attendance route
    Route::get('attendance',[AttendanceController::class,'index']);
    Route::post('attendance',[AttendanceController::class,'store']);

    //Holiday Route
    Route::get('holidays',[HolidayController::class,'index']);
    Route::post('holidays',[HolidayController::class,'store']);
    Route::post('holidays/{id}',[HolidayController::class,'update']);
    Route::delete('holidays/{id}',[HolidayController::class,'destroy']);

    //Advance Route
    Route::get('advances',[AdvanceController::class,'index']);
    Route::post('advances',[AdvanceController::class,'store']);
    Route::post('advances/{id}',[AdvanceController::class,'update']);
    Route::delete('advances/{id}',[AdvanceController::class,'destroy']);

    //Salary Route
    Route::get('salaries',[SalaryController::class,'index']);
    Route::post('salaries',[SalaryController::class,'store']);

    //Payroll Route
    Route::get('payrolls',[PayrollController::class,'index']);
    Route::post('payrolls',[PayrollController::class,'update']);




});
