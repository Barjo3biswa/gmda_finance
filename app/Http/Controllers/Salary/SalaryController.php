<?php

namespace App\Http\Controllers\Salary;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Imports\EmployeeWiseImport;
use App\Imports\HeadWiseImport;
use App\Models\AttendanceSummery;
use App\Models\LoanMaster;
use App\Models\LoanMasterDetails;
use App\Models\LoanRecovery;
use App\Models\salaryBlock;
use App\Models\salaryHead;
use App\Models\salaryHeadAmountDistribution;
use App\Models\salaryMaster;
use App\Models\salaryProcessStep;
use App\Models\salaryTemp;
use App\Models\salaryTrans;
use App\Models\User;
use Auth;
use Crypt;
use DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SalaryController extends Controller
{
    public function salaryHead(Request $request)
    {
        $salary_head = salaryHead::orderBy('order')->get();
        if ($request->editable_id) {
            $editable_id = Crypt::decrypt($request->editable_id);
            $editable = salaryHead::where("id", $editable_id)->first();
            return view("salary.head-index", compact("salary_head", "editable"));
        }
        return view("salary.head-index", compact("salary_head"));
    }

    public function salaryHeadStore(Request $request)
    {
        // dd($request->all());
        $data = [
            'name' => $request->name,
            'code' => $request->code,
            'pay_head' => $request->pay_head,
            'income_type' => $request->income_type,
            'percentage' => $request->percentage,
            'calculation_on' => $request->calculation_on ? json_encode($request->calculation_on) : null,
            'sal_deduct_if_absent' => $request->sal_deduct_if_absent ?? 0,
        ];
        if ($request->editable_id) {
            salaryHead::where('id', $request->editable_id)->update($data);
        } else {
            salaryHead::create($data);
        }

        // dd($data);
        return redirect()->back()->with('success', 'success');
    }

    public function salaryBlock(Request $request)
    {
        $currentDate = now();
        $startMonth = $currentDate->copy()->subMonths(8);
        $endMonth = $currentDate->copy()->addMonths(4);
        $blocks = salaryBlock::whereRaw("STR_TO_DATE(CONCAT(`year`, '-', `month`, '-01'), '%Y-%m-%d') BETWEEN ? AND ?", [
            $startMonth->startOfMonth()->format('Y-m-d'),
            $endMonth->endOfMonth()->format('Y-m-d')
        ])
            ->orderBy('id')
            ->get();
        return view("salary.salary-block", compact("blocks"));

    }

    public function BlockUnblock(Request $request, $id)
    {

        $decrypted = Crypt::decrypt($id);
        $block = SalaryBlock::where("id", $decrypted)->first();
        $check = salaryProcessStep::where('status', 'underprocess')->first();
        // dd($check);
        if (isset($check)) {
            return redirect()->back()->with('error', 'Opened Block is not fully Completed.');
        }

        if ($block->is_finalized == 1) {
            return redirect()->back()->with('error', 'Salary is generated for this block.');
        }
        // dd("ok");
        DB::beginTransaction();
        try {
            if ($block->sal_process_status == 'block') {
                $status = 'Unblock';
            } else {
                $status = 'block';
            }

            if ($block->sal_process_status == 'block') {
                salaryProcessStep::query()->update(['block_id' => $decrypted, 'status' => 'underprocess']);
            }

            $block->sal_process_status = $status;
            $block->save();
            SalaryBlock::whereNotIn("id", [$decrypted])->update(['sal_process_status' => 'block']);

            $maxId = SalaryBlock::max('id');
            if ($block->id == $maxId) {
                $data = [
                    'month' => ($block->month + 1) < 13 ? ($block->month + 1) : 1,
                    'year' => ($block->month + 1) < 13 ? $block->year : ($block->year + 1),
                    'sal_process_status' => 'block'
                ];
                SalaryBlock::create($data);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error in Opening salary Block');
        }

        return redirect()->back()->with('success', 'successfull');
    }


    public function salaryProcess(Request $request)
    {
        // dd($request->all());
        $salary_block = salaryBlock::get();
        $salary_head = salaryHead::orderBy('order')->get();

        $process_steps = salaryProcessStep::orderBy('order')->get();
        $couurent_open_block = salaryBlock::where('sal_process_status', 'unblock')->first();
        $default_block = salaryBlock::where('month', date('m'))->where('year', date('Y'))->first();
        $view_salary_block = $request->sal_block ?? ($couurent_open_block ? $couurent_open_block->id : $default_block->id);

        $is_editable_flag = SalaryBlock::where('id', $view_salary_block)
            ->where('sal_process_status', 'unblock')->where('is_finalized', 0)
            ->first();
        $employee = User::get();
        if ($request->view == "process") {
            return view("salary.process-salary", compact('salary_block', 'salary_head', 'employee', 'view_salary_block', 'process_steps', 'is_editable_flag'));
        } else {
            if ($request->status == 'pay_cut') {
                $employee = User::all()->filter(function ($user) {
                    return $user->payCut() > 0;
                });
            }
            return view("salary.salary-summery", compact('salary_block', 'salary_head', 'employee', 'view_salary_block', 'process_steps', 'is_editable_flag'));
        }

    }

    public function payslip(Request $request, $id, $sl_blk)
    {
        // dd($request->all());
        $emp_id = Crypt::decrypt($id);
        $emp_details = User::where('id', $emp_id)->first();
        $salary_block = salaryBlock::get();
        $salary_head = salaryHead::orderBy('order')->get();

        if ($request->sal_block) {
            $view_salary_block = $request->sal_block;
        } else {
            $view_salary_block = $sl_blk ?? salaryBlock::where('sal_process_status', 'unblock')->first()->id;
        }

        $attendance = AttendanceSummery::where('user_id', $emp_id)->where('block_id', $view_salary_block)->first();

        $is_editable_flag = SalaryBlock::where('id', $view_salary_block)
            ->where('sal_process_status', 'unblock')->where('is_finalized', 0)
            ->first();
        // dd($is_editable_flag);
        return view("salary.payslip", compact('salary_block', 'salary_head', 'view_salary_block', 'emp_id', 'is_editable_flag', 'emp_details', 'attendance'));
    }

    public function updateAmount(Request $request)
    {
        salaryTemp::where([
            'emp_id' => $request->emp_id,
            'sal_head_id' => $request->hd_id,
            'block_id' => $request->blk_id,
        ])->update([
                    'amount' => $request->amount,
                ]);
        $temp_salary = salaryTemp::where('emp_id', $request->emp_id)->where('block_id', $request->blk_id)->get();
        foreach ($temp_salary as $key => $temp) {
            $head_details = $temp->salaryHead->calculation_on;
            if ($temp->salaryHead->calculation_on) {
                $array = json_decode($temp->salaryHead->calculation_on);
                $new_amount = 0;
                foreach ($array as $key2 => $value2) {
                    $temp_salary = salaryTemp::where('emp_id', $request->emp_id)
                        ->where('block_id', $request->blk_id)
                        ->where('sal_head_id', $value2)->first();
                    $new_amount += $temp_salary->amount;
                }
                $new_amount = ($new_amount / 100) * $temp->salaryHead->percentage;
                $temp->amount = $new_amount;
                $temp->save();
            }
        }
        return redirect()->back()->with('success', 'Successfully Updated Amount');
    }

    public function salaryExcelUpload(Request $request)
    {
        $head = salaryHead::orderBy('order')->get();
        return view('salary.excel-upload', compact('head'));
    }

    public function sampleExcelHD(Request $request)
    {
        $excel = User::get();
        $fileName = 'Sample-Head-Wise-Upload.csv';
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0",
        );
        $columns = array(
            'SL',
            'emp_code',
            'emp_name',
            'head_name',
            'amount',
        );
        $callback = function () use ($excel, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $count = 0;
            foreach ($excel as $key => $task) {
                $row['SL'] = ++$key;
                $row['emp_code'] = $task->emp_code;
                $row['emp_name'] = $task->name;
                $row['head_name'] = 'Test';
                $row['amount'] = 0;
                fputcsv($file, array(
                    $row['SL'],
                    $row['emp_code'],
                    $row['emp_name'],
                    $row['head_name'],
                    $row['amount'],
                ));
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    // public function sampleExcelEmp(Request $request)
    // {
    //     $excel = User::get();
    //     $head = salaryHead::get();
    //     $fileName = 'Sample-Employee-Wise-Upload.csv';
    //     $headers = array(
    //         "Content-type" => "text/csv",
    //         "Content-Disposition" => "attachment; filename=$fileName",
    //         "Pragma" => "no-cache",
    //         "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
    //         "Expires" => "0",
    //     );
    //     $columns = array(
    //         'SL',
    //         'emp_code',
    //         'emp_name',
    //         'head_code',
    //         'head_name',
    //         'head_type',
    //         'amount',
    //     );
    //     $callback = function () use ($excel, $columns, $head) {
    //         $file = fopen('php://output', 'w');
    //         fputcsv($file, $columns);
    //         $count = 0;
    //         foreach ($excel as $key => $task) {
    //             foreach ($head as $hd) {
    //                 $row['SL'] = ++$key;
    //                 $row['emp_code'] = $task->emp_code;
    //                 $row['emp_name'] = $task->name;
    //                 $row['head_code'] = $hd->code;
    //                 $row['head_name'] = $hd->name;
    //                 $row['head_type'] = $hd->pay_head;
    //                 $row['amount'] = '';
    //                 fputcsv($file, array(
    //                     $row['SL'],
    //                     $row['emp_code'],
    //                     $row['emp_name'],
    //                     $row['head_code'],
    //                     $row['head_name'],
    //                     $row['head_type'],
    //                     $row['amount'],
    //                 ));
    //             }
    //         }
    //         fclose($file);
    //     };
    //     return response()->stream($callback, 200, $headers);
    // }



    public function sampleExcelEmp(Request $request)
    {
        $excel = User::get();
        $head = salaryHead::orderBy('order')->get();
        $fileName = 'Sample-Employee-Wise-Upload.csv';
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0",
        );

        $sal_hd_array = [];
        $columns = array(
            'SL',
            'emp_code',
            'emp_name',
        );

        foreach ($head as $hd) {
            array_push($sal_hd_array, $hd->code);
            array_push($columns, $hd->code);
        }
        // dd($columns);
        $callback = function () use ($excel, $columns, $sal_hd_array) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $count = 0;
            foreach ($excel as $key => $task) {
                $row['SL'] = ++$key;
                $row['emp_code'] = $task->emp_code;
                $row['emp_name'] = $task->name;
                foreach ($sal_hd_array as $arr) {
                    $head = salaryHead::where('code', $arr)->first();
                    $amount = salaryHeadAmountDistribution::where('emp_id', $task->id)->where('sal_head_id', $head->id)->first();
                    $row[$arr] = $amount->amount ?? 0;
                }

                $outputRow = [];
                foreach ($columns as $column) {
                    $outputRow[] = $row[$column] ?? '';
                }
                fputcsv($file, $outputRow);

            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function hdWiseImport(Request $request)
    {
        $salary_block = salaryBlock::where('sal_process_status', 'Unblock')->where('is_finalized', 0)->first();
        if (!$salary_block) {
            return redirect()->back()->with('error', 'Please Unblock New Salary Month & Year');
        }
        $request->validate([
            'head_name' => 'required',
            'excel_file' => 'required|mimes:xlsx,xls,csv',
        ]);
        try {
            Excel::import(new HeadWiseImport($request->head_name), $request->file('excel_file'));
            return redirect()->back()->with('success', 'Imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to import: ' . $e->getMessage());
        }
    }

    public function employeeWiseImport(Request $request)
    {
        // dd("ok");
        $salary_block = salaryBlock::where('sal_process_status', 'Unblock')->where('is_finalized', 0)->first();
        if (!$salary_block) {
            return redirect()->back()->with('error', 'Please Unblock New Salary Month & Year');
        }
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv',
        ]);
        try {
            Excel::import(new EmployeeWiseImport, $request->file('excel_file'));
            return redirect()->back()->with('success', 'Imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to import: ' . $e->getMessage());
        }
    }

    public function reduceWorkingDays(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $user = user::where('id', $request->emp_id)->first();
            $salary_block = salaryBlock::where('id', $request->sal_block_id)->first();
            $deductable_head = salaryHead::where('sal_deduct_if_absent', 1)->orderBy('order')->get();
            $attendance_summery = AttendanceSummery::where('block_id', $salary_block->id)->where('user_id', $request->emp_id)->first();
            $pay_cut_head = salaryHead::where('pay_cut_hd', 1)->first()->id;
            if (!$attendance_summery) {
                return redirect()->back()->with('error', 'Attendence is not processed.');
            }

            if ($request->pay_cut_day > 0) {
                $pay_cut = 0;
                foreach ($deductable_head as $hd) {
                    $temp_salary = salaryTemp::where('emp_id', $user->id)->where('sal_head_id', $hd->id)->first();
                    $salary_assigned_amount = salaryHeadAmountDistribution::where('emp_id', $user->id)->where('sal_head_id', $hd->id)->first();
                    $amount_per_day = ($salary_assigned_amount->amount / $attendance_summery->days_in_month);
                    $cut_amount = $amount_per_day * $request->pay_cut_day;

                    $pay_cut = round($pay_cut + $cut_amount);
                    $temp_salary->working_days = ($attendance_summery->days_in_month - $request->pay_cut_day);
                    $temp_salary->save();
                }
                salaryTemp::where('emp_id', $user->id)->where('sal_head_id', $pay_cut_head)->update(['amount' => $pay_cut]);
            }
            DB::commit();
        } catch (\Exception $e) {
            // dd($e);
            DB::rollBack();
            return redirect()->back()->with('error', 'Error While Processing Attendance');
        }
        return redirect()->back()->with('success', 'Successfull');
    }

    public function empAmount(Request $request)
    {
        // dd($request->all());
        $employee = user::get();
        $salary_head = salaryHead::orderBy('order')->get();
        $editable_id = $request->employee_id;
        return view('salary.head-wise-fix-amount', compact('employee', 'salary_head', 'editable_id'));
    }

    public function saveRecord(Request $request)
    {
        foreach ($request->amount as $key => $value) {
            $salary_head = salaryHead::where('id', $key)->first();
            $data = [
                'emp_id' => $request->employee_id,
                'emp_code' => User::where('id', $request->employee_id)->first()->emp_code,
                'sal_head_id' => $key,
                'salary_head_code' => $salary_head->code,
                'salary_head_name' => $salary_head->name,
                'pay_head' => $salary_head->pay_head,
                'amount' => $value[0] ?? 0,
                'status' => 'Active',
            ];
            salaryHeadAmountDistribution::updateOrCreate(
                [
                    'emp_id' => $request->employee_id,
                    'sal_head_id' => $salary_head->id,
                ],
                $data
            );
        }
        return redirect()->back()->with('success', 'Saved');
    }

    public function emptyTemp($id)
    {
        $step_details = salaryProcessStep::where('id', $id)->first();
        salaryTemp::truncate();
        $step_details->status = 'process';
        $step_details->save();
        salaryProcessStep::whereNotIn('id', [$id])->update(['status' => 'underprocess']);
        return redirect()->back()->with('success', 'Successfully Created Workspace');
    }
    public function processSalary($id)
    {
        // dd("ok");
        $step_details = salaryProcessStep::where('id', $id)->first();
        if (!CommonHelper::checkIsInOrder($step_details->order)) {
            return redirect()->back()->with('error', 'Please maintaion process oeder');
        }
        $sal_block_id = $step_details->block_id;
        $salary_block = salaryBlock::where('id', $sal_block_id)->first();
        if ($salary_block->is_finalized == 1) {
            return redirect()->back()->with('error', 'Already Processed');
        }
        if ($salary_block->sal_process_status == 'block') {
            return redirect()->back()->with('error', 'Please Unblock Salary for this month');
        }
        $employee = User::get();
        $salary_heads = salaryHead::orderBy('order')->get();
        DB::beginTransaction();
        try {
            foreach ($employee as $emp) {
                foreach ($salary_heads as $salary_head) {
                    $data = [
                        'emp_code' => $emp->emp_code,
                        'sal_head_id' => $salary_head->id,
                        'salary_head_code' => $salary_head->code,
                        'salary_head_name' => $salary_head->name,
                        'month' => $salary_block->month,
                        'year' => $salary_block->year,
                        'block_id' => $salary_block->id,
                        'pay_head' => $salary_head->pay_head,
                        'working_days' => 30,
                        'status' => 'draft',
                    ];
                    $existingRecord = salaryTemp::where('emp_id', $emp->id)
                        ->where('sal_head_id', $salary_head->id)
                        ->first();
                    if ($existingRecord) {
                        $new_amount = $existingRecord->amount;
                        if ($existingRecord->salaryHead->calculation_on) {
                            $array = json_decode($existingRecord->salaryHead->calculation_on);
                            $new_amount = 0;
                            foreach ($array as $key2 => $value2) {
                                $temp_salary = salaryTemp::where('emp_id', $emp->id)
                                    ->where('block_id', $salary_block->id)
                                    ->where('sal_head_id', $value2)->first();
                                $new_amount += $temp_salary->amount;
                            }
                            $new_amount = round(($new_amount / 100) * $existingRecord->salaryHead->percentage);
                        }

                        $data['amount'] = $new_amount;
                        $data['last_amount'] = $existingRecord->last_amount;
                    } else {
                        $master_amount = salaryHeadAmountDistribution::where('emp_id', $emp->id)
                            ->where('sal_head_id', $salary_head->id)
                            ->first();
                        if (!$master_amount && $salary_head->pay_head == 'Income') {
                            return redirect()->back()->with('error', $salary_head->name . ' Not found for ' . $emp->name);
                        }
                        $data['amount'] = $master_amount->amount ?? 0.00;
                        $data['last_amount'] = 0.00;
                    }
                    salaryTemp::updateOrCreate(
                        [
                            'emp_id' => $emp->id,
                            'sal_head_id' => $salary_head->id,
                        ],
                        $data
                    );
                }
            }

            $step_details->status = 'process';
            $step_details->save();
            DB::commit();
        } catch (\Exception $e) {
            // dd($e);
            DB::rollBack();
            return redirect()->back()->with('error', 'Error while Processing Salary');
        }
        return redirect()->back()->with('success', 'Successfully Processed Salary');
    }

    public function FinalizeSalary($id)
    {
        $step_details = salaryProcessStep::where('id', $id)->first();
        if (!CommonHelper::checkIsInOrder($step_details->order)) {
            return redirect()->back()->with('error', 'Please maintaion process oeder');
        }
        $sal_block_id = $step_details->block_id;
        $income_hed = salaryHead::where('pay_head', 'Income')->pluck('id')->toArray();
        $deduct_hed = salaryHead::where('pay_head', 'Deduction')->pluck('id')->toArray();
        $salary_block = salaryBlock::where('id', $sal_block_id)->first();
        if ($salary_block->is_finalized == 1) {
            return redirect()->back()->with('error', 'Already Finalized');
        }
        $employee = User::get();
        DB::beginTransaction();
        try {
            foreach ($employee as $key => $emp) {
                $gross = salaryTemp::where('emp_id', $emp->id)
                    ->where('block_id', $sal_block_id)
                    ->whereIn('sal_head_id', $income_hed)
                    ->where('status', 'draft')
                    ->sum('amount');
                $deduction = salaryTemp::where('emp_id', $emp->id)
                    ->where('block_id', $sal_block_id)
                    ->whereIn('sal_head_id', $deduct_hed)
                    ->where('status', 'draft')
                    ->sum('amount');
                $net = $gross - $deduction;
                $data = [
                    'emp_id' => $emp->id,
                    'emp_code' => $emp->emp_code,
                    'emp_name' => $emp->name,
                    // 'emp_object'  =>,
                    // 'department_id'  =>,
                    // 'department'  =>,
                    // 'designation_id'  =>,
                    // 'payband'  =>,
                    'sal_block_id' => $sal_block_id,
                    'month' => $salary_block->month,
                    'year' => $salary_block->year,
                    // 'total_days'  =>,
                    // 'working_days'  =>,
                    'gross' => $gross,
                    'deduction' => $deduction,
                    'net' => $net,
                ];
                // dd($data);
                salaryMaster::create($data);

                $temp_salary = salaryTemp::where('emp_id', $emp->id)
                    ->where('status', 'draft')
                    ->where('block_id', $sal_block_id)
                    ->get();

                if ($temp_salary->isNotEmpty()) {
                    foreach ($temp_salary as $row) {
                        if ($row->detail_json) {
                            $this->loanRecovery($row->detail_json, $salary_block);
                        }
                        $data = $row->makeHidden(['id', 'created_at', 'updated_at', 'deleted_at'])->toArray();
                        salaryTrans::create($data);
                    }
                }
            }
            $salary_block->is_finalized = 1;
            $salary_block->sal_process_status = 'block';
            $salary_block->save();
            $step_details->status = 'process';
            $step_details->save();
            DB::commit();
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return redirect()->back()->with('error', 'Error while Finalizing Salary');
        }
        return redirect()->back()->with('success', 'Successfully Finalized Salary');
    }

    public function loanRecovery($loan_detail, $salary_block)
    {
        // dd($loan_detail);
        $loan = json_decode($loan_detail);

        $loan_master = LoanMaster::where('id', $loan->loan_id)->first();
        // dd($loan_master);
        $principal_amount = $loan->principal_amount;
        $intrest_amount = $loan->intrest_amount;
        $principal_installment = 0;
        $interest_installment = 0;

        // dd($principal_amount);
        if ($loan_master->advanceType->advance_type == 'flat') {
            ///// flat loans/////
            if ($principal_amount) {
                $data = [
                    'outstanding_principal' => $loan_master->outstanding_principal - $principal_amount,
                    'principal_installment' => $loan_master->principal_installment + 1,
                ];
                $principal_installment = $loan_master->principal_installment + 1;
            } elseif ($intrest_amount) {
                $data = [
                    'outstanding_interest_amount' => $loan_master->outstanding_interest_amount - $intrest_amount,
                    'interest_installment' => $loan_master->interest_installment + 1,
                ];
                $interest_installment = $loan_master->interest_installment + 1;
                if ($interest_installment == $loan_master->no_of_installment_interest) {
                    $data['status'] = 5;
                }
            }

        } elseif ($loan_master->advanceType->advance_type == 'reducing') {
            ///// reducing loans/////
            $data = [
                'outstanding_principal' => $loan_master->outstanding_principal - $principal_amount,
                'outstanding_interest_amount' => $loan_master->outstanding_interest_amount - $intrest_amount,
                'principal_installment' => $loan_master->principal_installment + 1,
            ];
            if (($loan_master->principal_installment + 1) == $loan_master->no_of_installment) {
                $data['status'] = 5;
            }

        }
        // dd($data);
        LoanMaster::where('id', $loan->loan_id)->update($data);

        LoanRecovery::create([
            'emp_id' => $loan_master->user_id,
            'emp_code' => $loan_master->emp_code,
            'loan_id' => $loan_master->id,
            'inst_no' => $loan->installment_no,
            'principal_installment' => $principal_installment,
            'interest_installment' => $interest_installment,
            'principal_amount' => $principal_amount,
            'interest_amount' => $intrest_amount,
            'total_amount' => ($principal_amount + $intrest_amount),
            // 'loan_type_id' =>
            // 'recovery_type' =>
            'month' => $salary_block->month,
            'year' => $salary_block->year,
            'sal_block_id' => $salary_block->id,
        ]);

    }

    public function attendanceProcess($id)
    {
        $step_details = salaryProcessStep::where('id', $id)->first();
        if (!CommonHelper::checkIsInOrder($step_details->order)) {
            return redirect()->back()->with('error', 'Please maintaion process oeder');
        }

        DB::beginTransaction();
        try {
            $user = user::get();
            $salary_block = salaryBlock::where('sal_process_status', 'Unblock')->first();
            $deductable_head = salaryHead::where('sal_deduct_if_absent', 1)->orderBy('order')->get();
            $pay_cut_head = salaryHead::where('pay_cut_hd', 1)->first()->id;
            foreach ($user as $usr) {
                $attendance_summery = AttendanceSummery::where('block_id', $salary_block->id)->where('user_id', $usr->id)->first();
                if (!$attendance_summery) {
                    return redirect()->back()->with('error', 'Attendence is not processed.');
                }
                if ($attendance_summery->absent_count > 0) {
                    $pay_cut = 0;
                    foreach ($deductable_head as $hd) {
                        $temp_salary = salaryTemp::where('emp_id', $usr->id)->where('sal_head_id', $hd->id)->first();
                        $salary_assigned_amount = salaryHeadAmountDistribution::where('emp_id', $usr->id)->where('sal_head_id', $hd->id)->first();
                        $amount_per_day = ($salary_assigned_amount->amount / $attendance_summery->days_in_month);
                        $cut_amount = $amount_per_day * $attendance_summery->absent_count;

                        $pay_cut = round($pay_cut + $cut_amount);
                        // $new_amount = ($salary_assigned_amount->amount - ($amount_per_day * $attendance_summery->absent_count));
                        // $temp_salary->amount = round($new_amount);
                        $temp_salary->working_days = ($attendance_summery->days_in_month - $attendance_summery->absent_count);
                        $temp_salary->save();
                    }
                    salaryTemp::where('emp_id', $usr->id)->where('sal_head_id', $pay_cut_head)->update(['amount' => $pay_cut, 'status' => 'temp']);
                }

            }
            $step_details->status = 'process';
            $step_details->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error While Processing Attendance');
        }

        return redirect()->back()->with('success', 'Successfully Processed Attendance');
    }

    public function includeExclude(Request $request)
    {
        $temp_salary = salaryTemp::where('sal_head_id', $request->hd_id)->where('emp_id', $request->emp_id)->first();
        // dd($temp_salary);
        // dump($temp_salary->status);
        if ($temp_salary->status == 'temp') {
            $status = 'draft';
        } else if ($temp_salary->status == 'draft') {
            $status = 'temp';
        }
        // dump($status);
        $temp_salary->status = $status;
        $temp_salary->save();
        return redirect()->back()->with('success', 'Successfull');
    }

    public function payCutManage($id)
    {
        // dd($id);
        $step_details = salaryProcessStep::where('id', $id)->first();
        if (!CommonHelper::checkIsInOrder($step_details->order)) {
            return redirect()->back()->with('error', 'Please maintaion process oeder');
        }
        if ($step_details->status == 'process') {
            return redirect()->back()->with('error', 'Process is completed');
        }
        $salary_block = salaryBlock::get();
        $all_sal_head = salaryHead::orderBy('order')->get();
        $salary_head = salaryHead::where('pay_cut_hd', 1)->orderBy('order')->get();
        $view_salary_block = salaryBlock::where('sal_process_status', 'unblock')->first()->id;
        $employee = User::all()->filter(function ($user) {
            return $user->payCut() > 0;
        });
        $pay_cut_head = salaryHead::where('pay_cut_hd', 1)->first();
        $attendance = AttendanceSummery::where('block_id', $view_salary_block)->first();
        return view('salary.pay-cut-manage', compact('employee', 'salary_block', 'all_sal_head', 'salary_head', 'view_salary_block', 'pay_cut_head', 'attendance', 'id'));
    }

    public function payCutSave($id)
    {
        $step_details = salaryProcessStep::where('id', $id)->first();
        if (!CommonHelper::checkIsInOrder($step_details->order)) {
            return redirect()->back()->with('error', 'Please maintaion process oeder');
        }
        if ($step_details->status == 'process') {
            return redirect()->back()->with('error', 'Process is completed');
        }
        DB::beginTransaction();
        try {
            $pay_cut_head = salaryHead::where('pay_cut_hd', 1)->first();


            salaryTemp::where('sal_head_id', $pay_cut_head->id)
                ->where('status', 'temp')
                ->update(['amount' => 0, 'status' => 'draft']);
            $step_details->status = 'process';
            $step_details->save();
            DB::commit();
        } catch (\Exception $e) {
            // dd($e);
            DB::rollBack();
            return redirect()->back()->with('error', 'Error while Processing');
        }
        return redirect()->route('salary-process', ['view' => 'process'])->with('success', 'Successfully Saved');
    }

    public function processLoanAmount($id)
    {
        $step_details = salaryProcessStep::where('id', $id)->first();
        if (!CommonHelper::checkIsInOrder($step_details->order)) {
            return redirect()->back()->with('error', 'Please maintaion process oeder');
        }
        $sal_block_id = $step_details->block_id;
        $salary_block = salaryBlock::where('id', $sal_block_id)->first();
        DB::beginTransaction();
        try {
            $loans = LoanMaster::where('status', '!=', '5')->get();
            foreach ($loans as $loan) {
                if ($loan->advanceType->advance_type == 'flat') {
                    if ($loan->no_of_installment > $loan->principal_installment) {
                        $emi_amount = $loan->principal_installment;
                        if (($loan->adj_interest_emi_in == 'F' && $loan->principal_installment == 0) || ($loan->adj_interest_emi_in == 'L' && $loan->no_of_installment == ($loan->principal_installment + 1))) {
                            $emi_amount = $loan->adj_emi;
                        }
                        //////////additional condition to prevent negative value//////
                        if ($loan->outstanding_principal < $emi_amount) {
                            $emi_amount = $loan->outstanding_principal;
                        }
                        /////////////////////// Ends Here ////////////////////////////
                        $data = [
                            'loan_id' => $loan->id,
                            'emi' => $emi_amount,
                            'principal_amount' => $emi_amount,
                            'intrest_amount' => null,
                            'installment_no' => $loan->principal_installment + 1,
                        ];
                    } elseif ($loan->no_of_installment_interest > $loan->interest_installment) {
                        // dd("here");
                        $emi_amount = $loan->interest_emi;
                        if (($loan->adj_interest_emi_in == 'F' && $loan->interest_installment == 0) || ($loan->adj_interest_emi_in == 'L' && $loan->no_of_installment_interest == ($loan->interest_installment + 1))) {
                            $emi_amount = $loan->adj_interest_emi;
                        }
                        //////////additional condition to prevent negative value//////
                        if ($loan->outstanding_interest_amount < $emi_amount) {
                            $emi_amount = $loan->outstanding_interest_amount;
                        }
                        /////////////////////// Ends Here ////////////////////////////
                        $data = [
                            'loan_id' => $loan->id,
                            'emi' => $emi_amount,
                            'principal_amount' => null,
                            'intrest_amount' => $emi_amount,
                            'installment_no' => $loan->interest_installment + 1,
                        ];
                    }
                } elseif ($loan->advanceType->advance_type == 'reducing') {
                    $installment_no = $loan->principal_installment + 1;
                    $emi_details = LoanMasterDetails::where('loan_id', $loan->id)->where('payment_no', $installment_no)->first();
                    $emi_amount = $emi_details->payment;

                    $data = [
                        'loan_id' => $loan->id,
                        'emi' => $emi_amount,
                        'principal_amount' => $emi_details->principal,
                        'intrest_amount' => $emi_details->interest,
                        'installment_no' => $installment_no
                    ];
                }

                $json_data = json_encode($data);
                $salary_data = [
                    'emp_code' => $loan->user->id,
                    'sal_head_id' => $loan->advanceType->salary_head_id,
                    'salary_head_code' => $loan->advanceType->salaryHead->code,
                    'salary_head_name' => $loan->advanceType->salaryHead->name,
                    'month' => $salary_block->month,
                    'year' => $salary_block->year,
                    'block_id' => $salary_block->id,
                    'pay_head' => $loan->advanceType->salaryHead->pay_head,
                    'working_days' => 30,
                    'status' => 'draft',
                    'amount' => $emi_amount,
                    'detail_json' => $json_data,
                ];

                salaryTemp::updateOrCreate(
                    [
                        'emp_id' => $loan->user->id,
                        'sal_head_id' => $loan->advanceType->salary_head_id,
                    ],
                    $salary_data
                );
            }
            $step_details->status = 'process';
            $step_details->save();

            DB::commit();
        } catch (\Exception $e) {
            // dd($e);
            DB::rollBack();
            return redirect()->back()->with('error', 'Error');
        }
        return redirect()->back()->with('success', 'successfull');
    }


}
