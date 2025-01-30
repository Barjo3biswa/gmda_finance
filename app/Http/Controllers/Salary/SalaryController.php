<?php

namespace App\Http\Controllers\Salary;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Imports\EmployeeWiseImport;
use App\Imports\HeadWiseImport;
use App\Models\AdvanceType;
use App\Models\AttendanceSummery;
use App\Imports\kssFileImport;
use App\Models\LoanMaster;
use App\Models\LoanMasterDetails;
use App\Models\LoanRecovery;
use App\Models\salaryBlock;
use App\Models\salaryHead;
use App\Models\salaryHeadAmountDistribution;
use App\Models\salaryMaster;
use App\Models\salaryProcessStep;
use App\Models\SalarySummmary;
use App\Models\salaryTemp;
use App\Models\salaryTrans;
use App\Models\User;
use App\Models\userHoldUnhold;
use App\Services\LoanService;
use Auth;
use Crypt;
use DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Schema;

class SalaryController extends Controller
{
    protected $loanService;
    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }
    public function salaryHead(Request $request)
    {
        $salary_head = salaryHead::orderBy('order_fld')->get();
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
        $currentMonth = date('n');
        $currentYear = date('Y');

        $salary_block = salaryBlock::where(function ($q) use ($currentMonth, $currentYear) {
            for ($i = -3; $i <= 3; $i++) {
                $month = ($currentMonth + $i);
                $year = $currentYear;

                if ($month < 1) {
                    $month += 12; // Wrap around to last year
                    $year -= 1;
                } elseif ($month > 12) {
                    $month -= 12; // Wrap around to next year
                    $year += 1;
                }

                $q->orWhere(function ($query) use ($month, $year) {
                    $query->where('month', $month)->where('year', $year);
                });
            }
        })->get();



        // $salary_block = salaryBlock::get();
        $salary_head = salaryHead::orderBy('order_fld')->get();
        $process_steps = salaryProcessStep::orderBy('order')->get();
        $couurent_open_block = salaryBlock::where('sal_process_status', 'unblock')->first();
        $default_block = salaryBlock::where('month', date('m'))->where('year', date('Y'))->first();
        $view_salary_block = $request->sal_block ?? ($couurent_open_block ? $couurent_open_block->id : $default_block->id);

        $is_editable_flag = SalaryBlock::where('id', $view_salary_block)
            ->where('sal_process_status', 'unblock')->where('is_finalized', 0)
            ->first();
        $employee = User::where('salary_flag', 'open')->get();
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
        $salary_head = salaryHead::orderBy('order_fld')->get();

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


    public function finalPaySlip(Request $request, $id, $sl_blk)
    {
        $emp_id = Crypt::decrypt($id);
        $salary_block = salaryBlock::where('id', $sl_blk)->first();
        $emp_details = User::with('employee')->where('id', $emp_id)->first();
        $salary = salaryMaster::with('salaryTrans')->where('emp_id', $emp_id)->where('sal_block_id', $sl_blk)->first();
        if (!$salary) {
            return redirect()->back()->with('error', 'Salary Not Generated');
        }
        $claims = $salary->salaryTrans->where('pay_head', 'Income')->where('amount', '!=', 0);
        $deductions = $salary->salaryTrans->where('pay_head', 'Deduction')->where('amount', '!=', 0);
        return view('salary.final-payslip', compact('salary', 'emp_details', 'claims', 'deductions', 'salary_block'));
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
        $head = salaryHead::orderBy('order_fld')->get();
        return view('salary.excel-upload', compact('head'));
    }

    public function sampleExcelHD(Request $request)
    {
        $excel = User::where('salary_flag', 'open')->get();
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
        $excel = User::where('salary_flag', 'open')->get();
        $head = salaryHead::orderBy('order_fld')->get();
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
        // $file = $request->file('excel_file');
        // dd([
        //     'extension' => $file->getClientOriginalExtension(),
        //     'mime_type' => $file->getMimeType(),
        //     'original_name' => $file->getClientOriginalName(),
        // ]);
        $request->validate([
            'excel_file' => 'required|mimetypes:text/csv,text/plain,application/vnd.ms-excel',
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
        $salary_head = salaryHead::orderBy('order_fld')->get();
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
            return redirect()->back()->with('error', 'Please maintaion process order');
        }
        $sal_block_id = $step_details->block_id;
        $salary_block = salaryBlock::where('id', $sal_block_id)->first();
        if ($salary_block->is_finalized == 1) {
            return redirect()->back()->with('error', 'Already Processed');
        }
        if ($salary_block->sal_process_status == 'block') {
            return redirect()->back()->with('error', 'Please Unblock Salary for this month');
        }
        $employee = User::where('salary_flag', 'open')->get();
        $salary_heads = salaryHead::orderBy('order_fld')->get();
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
            return redirect()->back()->with('error', 'Please maintaion process order');
        }
        $sal_block_id = $step_details->block_id;
        $income_hed = salaryHead::where('pay_head', 'Income')->where('is_substitute_head', 0)->pluck('id')->toArray();
        $deduct_hed = salaryHead::where('pay_head', 'Deduction')->where('is_substitute_head', 0)->pluck('id')->toArray();
        $salary_block = salaryBlock::where('id', $sal_block_id)->first();
        if ($salary_block->is_finalized == 1) {
            return redirect()->back()->with('error', 'Already Finalized');
        }
        $employee = User::where('salary_flag', 'open')->get();
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
                    'emp_object' => json_encode($emp->employee),
                    'department_id' => $emp->employee->department_id ?? null,
                    'department' => $emp->employee->department_id ?? null,
                    'designation_id' => $emp->employee->designation_id ?? null,
                    'payband' => $emp->employee->payband_id ?? null,
                    'ifsc_code' => $emp->employee->bank_ifsc_no ?? null,
                    'account_no' => $emp->employee->bank_ac_no ?? null,
                    'sal_block_id' => $sal_block_id,
                    'month' => $salary_block->month,
                    'year' => $salary_block->year,
                    'financial_year' => $salary_block->month >= 4 ? $salary_block->year . '-' . ($salary_block->year + 1) : ($salary_block->year - 1) . '-' . $salary_block->year,
                    // 'total_days'  =>,
                    // 'working_days'  =>,
                    'gross' => $gross,
                    'deduction' => $deduction,
                    'net' => $net,
                ];
                // dd($data);
                $created = salaryMaster::create($data);

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
                        $data['master_id'] = $created->id;
                        salaryTrans::create($data);
                    }
                }
            }
            $salary_block->is_finalized = 1;
            $salary_block->sal_process_status = 'block';
            $salary_block->save();
            $step_details->status = 'process';
            $step_details->save();
            $this->processSalarySummary($step_details->block_id);
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
        $loans = json_decode($loan_detail);
        if (!is_array($loans) && !$loans instanceof Traversable) {
            $loans = [$loans];
        }
        foreach ($loans as $loan) {

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

    }

    public function attendanceProcess($id)
    {
        $step_details = salaryProcessStep::where('id', $id)->first();
        if (!CommonHelper::checkIsInOrder($step_details->order)) {
            return redirect()->back()->with('error', 'Please maintaion process order');
        }

        DB::beginTransaction();
        try {
            $user = user::where('salary_flag', 'open')->get();
            $salary_block = salaryBlock::where('sal_process_status', 'Unblock')->first();
            $deductable_head = salaryHead::where('sal_deduct_if_absent', 1)->orderBy('order_fld')->get();
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
            // dd($e);
            return redirect()->back()->with('error', 'Error While Processing Attendance');
        }

        return redirect()->back()->with('success', 'Successfully Processed Attendance');
    }


    public function processSalarySummary($block_id): bool
    {
        // $step_details = salaryProcessStep::where('step_name', 'salary')->first();
        $salary_block = salaryBlock::where('id', $block_id)->first();
        // dd($salary_block);
        $user = user::get();
        DB::beginTransaction();
        $financial_year = $salary_block->month >= 4 ? $salary_block->year . '-' . ($salary_block->year + 1) : ($salary_block->year - 1) . '-' . $salary_block->year;

        $user = user::where('salary_flag', 'open')->get();


        try {
            foreach ($user as $usr) {
                $salaryTempData = salaryTemp::where('emp_id', $usr->id)->where('block_id', $salary_block->id)->get();


                if ($salaryTempData->isEmpty()) {
                    continue;
                }

                $salarySummary = SalarySummmary::updateOrCreate(
                    [
                        'emp_id' => $usr->id,
                        'sal_block_id' => $salary_block->id,
                        'financial_year' => $financial_year,
                        'month' => $salary_block->month,
                        'year' => $salary_block->year,
                    ],
                    [
                        'emp_code' => $usr->emp_code,
                    ]
                );

                $this->addDynamicColumns($salarySummary, $salaryTempData);

                foreach ($salaryTempData as $temp) {
                    if ($temp->pay_head == 'Deduction') {
                        $columnName = 'DED_' . str_replace('.', '_', $temp->salary_head_code);
                        $salarySummary->$columnName = $temp->amount;
                    }

                    if ($temp->pay_head == 'Income') {
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
            return redirect()->back()->with('error', 'Please maintaion process order');
        }
        if ($step_details->status == 'process') {
            return redirect()->back()->with('error', 'Process is completed');
        }
        $salary_block = salaryBlock::get();
        $all_sal_head = salaryHead::orderBy('order_fld')->get();
        $salary_head = salaryHead::where('pay_cut_hd', 1)->orderBy('order_fld')->get();
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
            return redirect()->back()->with('error', 'Please maintaion process order');
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
            return redirect()->back()->with('error', 'Please maintaion process order');
        }
        $sal_block_id = $step_details->block_id;
        $salary_block = salaryBlock::where('id', $sal_block_id)->first();
        DB::beginTransaction();
        try {
            $advance_types = AdvanceType::get()->pluck('salary_head_id')->toArray();
            $advance_types = array_unique($advance_types);
            salaryTemp::whereIn('sal_head_id', $advance_types)->update(['amount' => 0, 'detail_json' => null]);
            $loans = LoanMaster::where('status', '!=', '5')->get();
            foreach ($loans as $key => $loan) {
                if ($loan->user->salary_flag == 'open') {
                    if ($loan->advanceType->advance_type == 'flat') {
                        $data = $this->loanService->generateFlatLoanData($loan);
                        if ($loan->no_of_installment > $loan->principal_installment) {
                            $salary_head = $loan->advanceType->salary_head_id;
                        } elseif ($loan->no_of_installment_interest > $loan->interest_installment) {
                            if ($loan->advanceType->int_salary_head_id) {
                                $salary_head = $loan->advanceType->int_salary_head_id;
                            } else {
                                $salary_head = $loan->advanceType->salary_head_id;
                            }
                        }
                    } elseif ($loan->advanceType->advance_type == 'reducing') {
                        $data = $this->loanService->generateReducingLoanData($loan);
                        $salary_head = $loan->advanceType->salary_head_id;
                        if ($loan->advanceType->int_salary_head_id) {
                            $int_salary_head = $loan->advanceType->int_salary_head_id;
                        } else {
                            $int_salary_head = $loan->advanceType->salary_head_id;
                        }

                    }

                    $json_data = $this->loanService->generateJsonData($loan, $data, $salary_head);
                    $salary_head_details = salaryHead::where('id', $salary_head)->first();
                    $check_is_exist = salaryTemp::where('emp_id', $loan->user->id)->where('sal_head_id', $salary_head)->first();

                    if ($loan->advanceType->advance_type == 'flat') {
                        $salary_data = [
                            'emp_code' => $loan->user->id,
                            'sal_head_id' => $salary_head,
                            'salary_head_code' => $salary_head_details->code,
                            'salary_head_name' => $salary_head_details->name,
                            'month' => $salary_block->month,
                            'year' => $salary_block->year,
                            'block_id' => $salary_block->id,
                            'pay_head' => $salary_head_details->pay_head,
                            'working_days' => 30,
                            'status' => 'draft',
                            'amount' => $data['emi'] + $check_is_exist->amount,
                            'last_amount' => 0.00,
                            'detail_json' => $json_data,
                        ];
                        salaryTemp::updateOrCreate(
                            [
                                'emp_id' => $loan->user->id,
                                'sal_head_id' => $salary_head,
                            ],
                            $salary_data
                        );
                    } else { ///// reducing loan handling//////
                        $salary_data = [
                            'emp_code' => $loan->user->id,
                            'sal_head_id' => $salary_head,
                            'salary_head_code' => $salary_head_details->code,
                            'salary_head_name' => $salary_head_details->name,
                            'month' => $salary_block->month,
                            'year' => $salary_block->year,
                            'block_id' => $salary_block->id,
                            'pay_head' => $salary_head_details->pay_head,
                            'working_days' => 30,
                            'status' => 'draft',
                            'amount' => $data['principal_amount'] + $check_is_exist->amount,
                            'last_amount' => 0.00,
                            'detail_json' => $json_data,
                        ];
                        salaryTemp::updateOrCreate(
                            [
                                'emp_id' => $loan->user->id,
                                'sal_head_id' => $salary_head,
                            ],
                            $salary_data
                        );
                        $int_salary_head_details = salaryHead::where('id', $int_salary_head)->first();
                        $salary_data = [
                            'emp_code' => $loan->user->id,
                            'sal_head_id' => $int_salary_head,
                            'salary_head_code' => $int_salary_head_details->code,
                            'salary_head_name' => $int_salary_head_details->name,
                            'month' => $salary_block->month,
                            'year' => $salary_block->year,
                            'block_id' => $salary_block->id,
                            'pay_head' => $int_salary_head_details->pay_head,
                            'working_days' => 30,
                            'status' => 'draft',
                            'amount' => $data['intrest_amount'] + $check_is_exist->amount,
                            'last_amount' => 0.00,
                            'detail_json' => null,
                        ];
                        salaryTemp::updateOrCreate(
                            [
                                'emp_id' => $loan->user->id,
                                'sal_head_id' => $int_salary_head,
                            ],
                            $salary_data
                        );
                    }
                }
            }
            $step_details->status = 'process';
            $step_details->save();
            DB::commit();
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return redirect()->back()->with('error', 'Error');
        }
        return redirect()->back()->with('success', 'successfull');
    }

    public function uploadKSS($id)
    {
        $step_details = salaryProcessStep::where('id', $id)->first();
        if (!CommonHelper::checkIsInOrder($step_details->order)) {
            return redirect()->back()->with('error', 'Please maintaion process order');
        }
        $salary_block = salaryBlock::where('sal_process_status', 'Unblock')->first();
        return view('salary.kss-upload', compact('salary_block', 'id'));
    }

    public function saveKSS(Request $request, $id)
    {
        // dd($request->all());
        $step_details = salaryProcessStep::where('id', $id)->first();
        if (!CommonHelper::checkIsInOrder($step_details->order)) {
            return redirect()->back()->with('error', 'Please maintaion process order');
        }
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv',
        ]);
        try {
            Excel::import(new kssFileImport(), $request->file('excel_file'));
            $step_details->status = 'process';
            $step_details->save();
            return redirect()->route('salary-process', ['view' => 'process'])->with('success', 'Successfully Uploaded');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to import: ' . $e->getMessage());
        }
    }


    public function holdUnhold()
    {
        $employee = User::get();
        return view('salary.hold-unhold-salary', compact('employee'));
        // dd("ok");
    }

    public function holdSalary(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $user = user::where('id', $request->emp_id)->first();
            user::where('id', $request->emp_id)->update(
                ['salary_flag' => 'hold']
            );

            userHoldUnhold::create([
                'emp_id' => $request->emp_id,
                'emp_code' => $user->emp_code,
                'type' => 'salary',
                'from_date' => $request->from_date ?? null,
                'to_date' => $request->to_date ?? null,
                'holding_type' => $request->holding_type,
                'holding reason' => $request->reason,
                'status' => 'active',
                'created_by' => Auth::user()->emp_code,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Try Again');
        }
        return redirect()->back()->with('success', 'Successfull');
    }

    public function unholdSalary($id)
    {
        $decrypt = Crypt::decrypt($id);
        user::where('id', $decrypt)->update(['salary_flag' => 'open']);
        userHoldUnhold::where('emp_id', $decrypt)->update(['status' => 'closed']);
        return redirect()->back()->with('success', 'Successfull');

    }


    public function salarySummeryNet(Request $request)
    {
        // dd($request->all());
        $currentMonth = date('n');
        $currentYear = date('Y');

        $salary_block = salaryBlock::where(function ($q) use ($currentMonth, $currentYear) {
            for ($i = -3; $i <= 3; $i++) {
                $month = ($currentMonth + $i);
                $year = $currentYear;

                if ($month < 1) {
                    $month += 12; // Wrap around to last year
                    $year -= 1;
                } elseif ($month > 12) {
                    $month -= 12; // Wrap around to next year
                    $year += 1;
                }

                $q->orWhere(function ($query) use ($month, $year) {
                    $query->where('month', $month)->where('year', $year);
                });
            }
        })->get();
        // dd($salary_block);
        $couurent_open_block = salaryBlock::where('sal_process_status', 'unblock')->first();
        $default_block = salaryBlock::where('month', date('m'))->where('year', date('Y'))->first();
        $view_salary_block = $request->sal_block ?? ($couurent_open_block ? $couurent_open_block->id : $default_block->id);
        $salary_master = salaryMaster::where('sal_block_id', $request->sal_block)->get();
        // dd($salary_master);
        return view('salary.salary-summery-net', compact('salary_block', 'view_salary_block', 'salary_master'));
        // dd($salary_block);
    }

}
