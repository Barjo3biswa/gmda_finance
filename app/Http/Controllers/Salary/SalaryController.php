<?php

namespace App\Http\Controllers\Salary;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Imports\EmployeeWiseImport;
use App\Imports\HeadWiseImport;
use App\Models\AttendanceSummery;
use App\Models\salaryBlock;
use App\Models\salaryHead;
use App\Models\salaryHeadAmountDistribution;
use App\Models\salaryMaster;
use App\Models\salaryProcessStep;
use App\Models\SalarySummmary;
use App\Models\salaryTemp;
use App\Models\salaryTrans;
use App\Models\User;
use Auth;
use Crypt;
use DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Schema;

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
        $employee = User::get();
        $process_steps = salaryProcessStep::orderBy('order')->get();
        $couurent_open_block = salaryBlock::where('sal_process_status', 'unblock')->first();
        $default_block = salaryBlock::where('month', date('m'))->where('year', date('Y'))->first();
        $view_salary_block = $request->sal_block ?? ($couurent_open_block ? $couurent_open_block->id : $default_block->id);

        $is_editable_flag = SalaryBlock::where('id', $view_salary_block)
            ->where('sal_process_status', 'unblock')->where('is_finalized', 0)
            ->first();

        if ($request->view == "process") {
            return view("salary.process-salary", compact('salary_block', 'salary_head', 'employee', 'view_salary_block', 'process_steps', 'is_editable_flag'));
        } else {
            // dd("ok");
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
                salaryTemp::where('emp_id', $user->id)->where('sal_head_id', 19)->update(['amount' => $pay_cut]);
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
                            $new_amount = ($new_amount / 100) * $existingRecord->salaryHead->percentage;
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
                    ->sum('amount');
                $deduction = salaryTemp::where('emp_id', $emp->id)
                    ->where('block_id', $sal_block_id)
                    ->whereIn('sal_head_id', $deduct_hed)
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
                    ->where('block_id', $sal_block_id)
                    ->get();

                if ($temp_salary->isNotEmpty()) {
                    foreach ($temp_salary as $row) {
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
    public function attendanceProcess($id)
    {
        $step_details = salaryProcessStep::where('id', $id)->first();
        if (!CommonHelper::checkIsInOrder($step_details->order)) {
            return redirect()->back()->with('error', 'Please maintaion process oeder');
        }
        $sal_block_id = $step_details->block_id;
        $salary_block = salaryBlock::where('id', $sal_block_id)->first();
        if ($salary_block->is_finalized == 1) {
            return redirect()->back()->with('error', 'Already Processed');
        }
        $employee = User::get();
        DB::beginTransaction();
        try {
            foreach ($employee as $usr) {
                $attendance_summery = AttendanceSummery::where('block_id', $salary_block->id)->where('user_id', $usr->id)->first();
                if (!$attendance_summery) {
                    return redirect()->back()->with('error', 'Attendence is not processed.');
                }
                if ($attendance_summery->absent_count > 0) {
                    $pay_cut = 0;
                    $deductable_head = salaryHead::where('sal_deduct_if_absent', 1)->orderBy('order')->get();
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
                    salaryTemp::where('emp_id', $usr->id)->where('sal_head_id', 19)->update(['amount' => $pay_cut]);
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


    public function processSalarySummary() : bool
    {
        // $step_details = salaryProcessStep::where('step_name', 'salary')->first();
        $salary_block = salaryBlock::where('sal_process_status', 'Unblock')->first();

        $user = user::get();

        try {
        DB::beginTransaction();
        foreach ($user as $usr) {
            $salaryTempData = salaryTemp::where('emp_id', $usr->id)->where('block_id', $salary_block->id)->get();

            $salarySummary = SalarySummmary::updateOrCreate(
                [
                    'emp_id' => $usr->id,
                    'sal_block_id' => $salary_block->id,
                    'month' => $salary_block->month,
                    'year' => $salary_block->year,
                ],
                [
                    'emp_code' => $usr->emp_code,
                ]
            );

            $this->addDynamicColumns($salarySummary, $salaryTempData);

            foreach ($salaryTempData as $temp) {
                if($temp->pay_head == 'Deduction'){
                    $columnName = 'DED_' . str_replace('.', '_', $temp->salary_head_code);
                    $salarySummary->$columnName = $temp->amount;
                }

                if($temp->pay_head == 'Income'){
                    $columnName = 'INC_' . str_replace('.', '_', $temp->salary_head_code);
                    $salarySummary->$columnName = $temp->amount;
                }
            }

            $salarySummary->save();
        }

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();

        return true;
    }

    private function addDynamicColumns($model, $salaryTempData)
    {
        $table = $model->getTable();

        $deductionData = $salaryTempData->where('pay_head', 'Deduction');
        $incomeData = $salaryTempData->where('pay_head', 'Income');

        $existingColumns = \DB::getSchemaBuilder()->getColumnListing($table);
        $nonTimestampColumns = collect($existingColumns)
            ->reject(fn($col) => in_array($col, ['created_at', 'updated_at', 'deleted_at']));

        $lastIncomeColumn = $nonTimestampColumns
            ->filter(fn($col) => str_starts_with($col, 'INC_'))
            ->last();

        $lastDeductionColumn = $nonTimestampColumns
            ->filter(fn($col) => str_starts_with($col, 'DED_'))
            ->last();

        $addedIncomeColumns = [];
        $addedDeductionColumns = [];

        foreach ($incomeData as $temp) {
            $columnName = 'INC_' . str_replace('.', '_', $temp->salary_head_code);
            if (!in_array($columnName, $existingColumns)) {
                $addedIncomeColumns[] = $columnName;
            }
        }

        foreach ($deductionData as $temp) {
            $columnName = 'DED_' . str_replace('.', '_', $temp->salary_head_code);
            if (!in_array($columnName, $existingColumns)) {
                $addedDeductionColumns[] = $columnName;
            }
        }

        $sqlQueries = [];

        foreach ($addedDeductionColumns as $columnName) {
            $sqlQueries[] = "ALTER TABLE `{$table}` ADD COLUMN `{$columnName}` DECIMAL(10,2) NULL " .
                            ($lastDeductionColumn ? "AFTER `{$lastDeductionColumn}`" : "AFTER `deleted_at`");
            $lastDeductionColumn = $columnName;
        }

        foreach ($addedIncomeColumns as $columnName) {
            $sqlQueries[] = "ALTER TABLE `{$table}` ADD COLUMN `{$columnName}` DECIMAL(10,2) NULL " .
                            ($lastIncomeColumn ? "AFTER `{$lastIncomeColumn}`" : "AFTER `deleted_at`");
            $lastIncomeColumn = $columnName;
        }

        foreach ($sqlQueries as $query) {
            try {
                \DB::statement($query);
            } catch (\Exception $e) {

                throw $e;
            }
        }
    }


}
