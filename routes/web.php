<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/invalidate-token', [App\Http\Controllers\Auth\TokenController::class, 'invalidateToken'])->name('invalidate-token');

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::get('/salary-head', [App\Http\Controllers\Salary\SalaryController::class, 'salaryHead'])->name('salary-head');
    Route::post('/salary-head-store', [App\Http\Controllers\Salary\SalaryController::class, 'salaryHeadStore'])->name('salary-head-store');
    Route::get('/salary-head-edit', [App\Http\Controllers\Salary\SalaryController::class, 'salaryHead'])->name('salary-head-edit');
    Route::get('/salary-block', [App\Http\Controllers\Salary\SalaryController::class, 'salaryBlock'])->name('salary-block');
    Route::get('/block-unblock/{id}', [App\Http\Controllers\Salary\SalaryController::class, 'BlockUnblock'])->name('block-unblock');

    Route::get('/salary-process', [App\Http\Controllers\Salary\SalaryController::class, 'salaryProcess'])->name('salary-process');
    Route::get('/payslip/{id}/{sl_blk}', [App\Http\Controllers\Salary\SalaryController::class, 'payslip'])->name('payslip');
    Route::post('/update-amount', [App\Http\Controllers\Salary\SalaryController::class, 'updateAmount'])->name('update-amount');

    Route::get('/excel-upload', [App\Http\Controllers\Salary\SalaryController::class, 'salaryExcelUpload'])->name('excel-upload');
    Route::get('/sample-excel-hd', [App\Http\Controllers\Salary\SalaryController::class, 'sampleExcelHD'])->name('sample-excel-hd');
    Route::get('/sample-excel-emp', [App\Http\Controllers\Salary\SalaryController::class, 'sampleExcelEmp'])->name('sample-excel-emp');
    Route::post('/hd-wise-import', [App\Http\Controllers\Salary\SalaryController::class, 'hdWiseImport'])->name('hd-wise-import');
    Route::post('/employee-wise-import', [App\Http\Controllers\Salary\SalaryController::class, 'employeeWiseImport'])->name('employee-wise-import');
    Route::post('/reduce-working-days', [App\Http\Controllers\Salary\SalaryController::class, 'reduceWorkingDays'])->name('reduce-working-days');

    Route::get('/emp-amount', [App\Http\Controllers\Salary\SalaryController::class, 'empAmount'])->name('emp-amount');
    Route::post('/save-record', [App\Http\Controllers\Salary\SalaryController::class, 'saveRecord'])->name('save-record');

    Route::get('/empty-temp/{id}', [App\Http\Controllers\Salary\SalaryController::class, 'emptyTemp'])->name('empty-temp');
    Route::get('/pull-temp/{id}', [App\Http\Controllers\Salary\SalaryController::class, 'processSalary'])->name('pull-temp');
    Route::get('/attendance-process/{id}', [App\Http\Controllers\Salary\SalaryController::class, 'attendanceProcess'])->name('attendance-process');
    Route::get('/manage-pay-cut/{id}', [App\Http\Controllers\Salary\SalaryController::class, 'payCutManage'])->name('manage-pay-cut');
    Route::get('/save-pay-cut/{id}', [App\Http\Controllers\Salary\SalaryController::class, 'payCutSave'])->name('save-pay-cut');
    Route::get('/finalize-salary/{id}', [App\Http\Controllers\Salary\SalaryController::class, 'FinalizeSalary'])->name('finalize-salary');
    Route::get('/process-loan-amount/{id}', [App\Http\Controllers\Salary\SalaryController::class, 'processLoanAmount'])->name('process-loan-amount');


    Route::get('/include-exclude', [App\Http\Controllers\Salary\SalaryController::class, 'includeExclude'])->name('include-exclude');
});


