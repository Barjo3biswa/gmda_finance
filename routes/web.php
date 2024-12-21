<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdvanceController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\PolicyController;

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
    Route::get('/upload-kss/{id}', [App\Http\Controllers\Salary\SalaryController::class, 'uploadKSS'])->name('upload-kss');
    Route::post('/save-kss', [App\Http\Controllers\Salary\SalaryController::class, 'saveKSS'])->name('save-kss');


    Route::get('/include-exclude', [App\Http\Controllers\Salary\SalaryController::class, 'includeExclude'])->name('include-exclude');



    Route::get('advance', [AdvanceController::class, 'index'])->name('advance.index');
    Route::get('advance/show/{id}', [AdvanceController::class, 'show'])->name('advance.show');
    Route::get('advance/create', [AdvanceController::class, 'create'])->name('advance.create');
    Route::post('advance/store', [AdvanceController::class, 'store'])->name('advance.store');
    Route::get('advance/edit/{id}', [AdvanceController::class, 'edit'])->name('advance.edit');
    //Route::post('advance/update', [AdvanceController::class, 'update_advance'])->name('advance.update');

    Route::put('advance/{id}/update', [AdvanceController::class, 'update_advance'])->name('advance.update');

    Route::get('advance/search', [AdvanceController::class, 'search'])->name('advance.search');
    Route::get('advance/update-advance', [AdvanceController::class, 'edit_advance_list'])->name('advance.update_advance');
    Route::post('advance/update-advance_data', [AdvanceController::class, 'update_advance_data'])->name('advance.update_advance_data');
    Route::get('advance/process-advance', [AdvanceController::class, 'process_advance'])->name('advance.processadvance');
    Route::post('advance/process-advance-data', [AdvanceController::class, 'process_advance_data'])->name('advance.process_advance_data');
    Route::get('advance/processed-data-list', [AdvanceController::class, 'processed_data_list'])->name('advance.processed_data_list');
    Route::post('advance/process-data-list', [AdvanceController::class, 'process_advance_data_post'])->name('advance.process_advance_data_post');
    Route::get('advance/processed-data-list/{id}', [AdvanceController::class, 'processed_data_list_delete'])->name('advance.processed_data_list_delete');

    Route::get('advance/existing', [AdvanceController::class, 'createExisting'])->name('advance.existing');
    Route::post('advance/store-existing', [AdvanceController::class, 'storeExisting'])->name('advance.store-existing');

    Route::get('advance/view-advances-list', [AdvanceController::class, 'ViewAdvances'])->name('advance.viewadvances');
    Route::get('advance/view-advance-details/{id}', [AdvanceController::class, 'ViewAdvanceDetails'])->name('advance.viewadvancedetails');

    //loan
    Route::get('loan', [LoanController::class, 'index'])->name('loan.index');
    Route::get('loan/create', [LoanController::class, 'create'])->name('loan.create');
    Route::post('loan/create', [LoanController::class, 'store'])->name('loan.store');
    Route::get('loan/existing', [LoanController::class, 'createExisting'])->name('loan.existing');
    Route::get('loan/process-loan', [LoanController::class, 'process_loan'])->name('loan.processloan');
    Route::post('loan/process-loan-data', [LoanController::class, 'process_loan_data'])->name('loan.process_loan_data');
    Route::get('loan/processed-loan-list', [LoanController::class, 'processed_loan_list'])->name('loan.processed_loan_list');
    Route::post('loan/store-existing', [LoanController::class, 'storeExisting'])->name('loan.store-existing');

    Route::get('loan/edit/{id}', [LoanController::class, 'edit'])->name('loan.edit');
    Route::put('loan/{id}/update', [LoanController::class, 'update'])->name('loan.update');

    Route::get('loan/close/{id}', [LoanController::class, 'close'])->name('loan.close');
    Route::put('loan/{id}/close', [LoanController::class, 'closeLoan'])->name('loan.closeLoan');

    Route::get('loan/show/{id}', [LoanController::class, 'show'])->name('loan.show');


    //policy
    Route::get('policy', [PolicyController::class, 'index'])->name('policy.index');
    Route::get('policy/create', [PolicyController::class, 'create'])->name('policy.create');
    Route::post('policy/create', [PolicyController::class, 'store'])->name('policy.store');
    Route::get('policy/edit/{id}', [PolicyController::class, 'edit'])->name('policy.edit');
    Route::get('policy/update-policy', [PolicyController::class, 'update_policy'])->name('policy.updatepolicy');
    Route::post('policy/update-policy-data', [PolicyController::class, 'update_policy_data'])->name('policy.updatepolicydata');
    Route::put('policy/{id}/update', [PolicyController::class, 'update'])->name('policy.update');
    Route::get('policy/show/{id}', [PolicyController::class, 'show'])->name('policy.show');
    Route::get('policy/process-policy', [PolicyController::class, 'process_policy'])->name('policy.processpolicy');
    Route::post('policy/process-policy-data', [PolicyController::class, 'process_policy_data'])->name('policy.process_policy_data');
    Route::get('policy/processed-policy-list', [PolicyController::class, 'processed_policy_list'])->name('policy.processed_policy_list');
    Route::get('policy/processed-policy-list/{id}', [PolicyController::class, 'processed_policy_list_delete'])->name('policy.processed_policy_list_delete');

    Route::post('policy/process-policy-search', [PolicyController::class, 'process_policy_search'])->name('policy.search');
});


