<?php

namespace App\Http\Controllers;

use App\Models\AuthDesignation;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Policy;
use App\Models\salaryBlock;
use App\Models\salaryHead;
use App\Models\LicProcessData;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class PolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $policies = Policy::query()
            ->with("employees")
            ->filter()
            ->latest()
            ->paginate(10);
        $emp = Employee::get();  //active()->get();
        $departments = Department::select('id', 'name')->get();
        // $employees = Employee::select('*')->active()->get();
        $designations = AuthDesignation::get();
        //dd($designations);
        return view('policy.index', compact('designations', 'policies', 'emp', "departments"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $policies = Policy::orderBy('id', 'desc')
            ->paginate(10);
        $employees = Employee::select('*')
            ->get();
        $designations = AuthDesignation::get();
        return view('policy.create', compact('policies', 'employees', 'designations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $employee = Employee::where('user_id', $request->employee_id)->first();
        // dd($employee);

        $closing_date = $request->closing_date;

        if (!empty($closing_date)) {
            $closing_date = $request->closing_date;
        } else {
            $closing_date = ($request->wef_year . '-' . $request->wef_month . '-01');
            $closing_date = date('Y-m-d', strtotime($closing_date . ' +10 years'));
        }

        /*$currentDate = Carbon\Carbon::now();
        $currentDate->toDateTimeString();
        $cd = $currentDate->format('Y-m-d');*/

        $start_date = $request->start_date;
        if (!empty($start_date)) {
            $start_date = $request->start_date;
        } else {
            $start_date = $cd;
        }

        $salary_block = salaryBlock::where('sal_process_status', "Unblock")->where('is_finalized', 0)->first();

        // $closing_date  = $request->closing_date;
        $closing_year = substr(date('Y', strtotime($closing_date)), 2, 4);
        $closing_month = date("n", strtotime($closing_date));
        $cls = sprintf("%02d", $closing_month);
        $clsyymm = $closing_year . $cls;
        //Wef date
        $wef_year = substr($request->wef_year, 2, 4);
        $wef_month = $request->wef_month;
        $wefyymm = $wef_year . $wef_month;


        //End Wef

        $rules = array('policy_no' => 'unique:policy,policy_no');
        $validator = "";//Validator::make($request->all(), $rules);

        // dd($request->all());
        DB::beginTransaction();
        try {
            if ($validator) {

                $queryData = [
                    'employee_id' => $request->employee_id,
                    'employee_code' => $employee->code,
                    'policy_no' => $request->policy_no,
                ];
                $data = [
                    'policy_name' => $request->policy_name,
                    'dependent_name' => $request->dependent_name,
                    'amount' => $request->amount ?? 0,
                    'monthly_premium' => $request->monthly_premium,
                    'wef_month' => $request->wef_month,
                    'wef_year' => $request->wef_year,
                    'wef_yy_mm' => $wefyymm,
                    // 'start_date'      => $request->start_date,
                    'start_date' => $start_date,
                    'maturity_date' => $request->maturity_date,
                    // 'closing_date'    => $request->closing_date,
                    'closing_date' => $closing_date,
                    'closing_year' => $closing_year,
                    'cls_yy_mm' => $clsyymm,
                    'closing_month' => $closing_month,
                    'status' => 1,

                ];
                Policy::updateOrCreate($queryData, $data);
                // return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $data = [
                    'employee_id' => $request->employee_id,
                    'employee_code' => $employee->code,
                    'employee_designation' => $employee->designation_id,
                    'policy_no' => $request->policy_no,
                    'policy_name' => $request->policy_name,
                    'dependent_name' => $request->dependent_name,
                    'amount' => $request->monthly_premium,
                    'monthly_premium' => $request->monthly_premium,
                    'wef_month' => $request->wef_month,
                    'wef_year' => $request->wef_year,
                    'wef_yy_mm' => $wefyymm,
                    // 'start_date'      => $request->start_date,
                    'start_date' => $start_date,
                    'maturity_date' => $request->maturity_date,
                    // 'closing_date'    => $request->closing_date,
                    'closing_date' => $closing_date,
                    'closing_year' => $closing_year,
                    'cls_yy_mm' => $clsyymm,
                    'closing_month' => $closing_month,
                    'status' => 1,

                ];
                Policy::create($data);
            }
        } catch (Exception $e) {
            DB::rollback();
            Log::critical($e);

            $request->session()->flash('error', 'Something went wrong');

            return back();
        }
        DB::commit();
        $request->session()->flash('success', 'Successfully added');
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $policies = Policy::where('id', $id)->first();
        //dd($policies);
        $employees = Employee::with('policy')->get();
        $designations = AuthDesignation::get();
        $emp = Employee::get(); //active()->get();
        return view('policy.edit', compact('employees', 'designations', 'policies', 'emp'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

    }

    public function update_policy()
    {
        $policies = Policy::orderBy('id', 'desc')->paginate(10);
        $emp = Employee::get(); //active()->get()
        $employees = Employee::with('policy')->get();
        $designations = AuthDesignation::get();
        return view('policy.update_policy', compact('employees', 'designations', 'policies', 'emp'));
    }
    public function update_policy_data(Request $request)
    {
        $employees = Employee::select('*')->get();
        $rules = [
            'comment' => 'required',
        ];

        /*$validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)
                ->withInput();
        }*/

        $count = count($request->emp_id);
        //  print_r($request->stop_status);die;

        if ($request->stop_status == 0) {
            $status = [
                'status' => $request->stop_status,
                'closing_reason' => $request->comment,
            ];
        }
        if ($request->close_status == 9) {
            $status = [
                'status' => $request->close_status,
                'closing_reason' => $request->comment,
            ];
        }
        if ($request->enable_status == 1) {
            $status = [
                'status' => $request->enable_status,
                'closing_reason' => $request->comment,
            ];
        }

        for ($i = 0; $i < $count; $i++) {
            Policy::where('id', $request->emp_id[$i])->update($status);
        }
        return back();
        //dd($designations);
        // return view('policy.update_policy', compact('employees', 'designations', 'policies'));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LicProcessData $model)
    {
        if (!$model->isProcessingAllowed()) {
            return redirect()
                ->back()
                ->with("error", "This record is already processed. Unable to delete.");
        }
        $model->update([
            "status" => 0,
        ]);
        return redirect()
            ->back()
            ->with("success", "Record deleted successfully.");
    }


    public function process_policy()
    {
        $emp = Employee::get(); //active()->
        $departments = Department::select('id', 'name')->get();
        $salarystatus = salaryBlock::where('sal_process_status', "Unblock")->first();
        $policies = Policy::filter()
            ->where('status', 1)
            ->with([
                "licprocessdatas" => function ($query) use ($salarystatus) {
                    return $query->where("year", optional($salarystatus)->salary_year)
                        ->where("month", optional($salarystatus)->salary_month);
                }
            ])
            ->with("employees")
            ->orderBy('employee_code', 'ASC')
            // ->whereDoesntHave('licprocessdata')
            ->paginate(100);
        // dd($salarystatus);

        //dd($policies);
        return view('policy.process_policy', compact('policies', 'salarystatus', 'emp', 'departments'));
    }


    public function process_policy_data(Request $request)
    {
        //dd($request->all());

        $request->validate([
            'datas' => 'required|array|min:1',
            'datas.policy_ids.*' => 'required|numeric|min:1',
            'salary_block_id' => 'required|exists:salary_blocks,id',
        ], [], [
            'datas' => 'Policy',
            'datas.policy_ids.*' => 'Policy',
            'salary_block_id' => 'Salary Year',
        ]);

        $salary_block = salaryBlock::where("id", $request->salary_block_id)
            ->active()
            ->first();

        if (!$salary_block) {
            return redirect()->back()
                ->with("error", "Selected salary year block is not active.");
        }

        /*if ($salary_block->isLicProcessed()) {
            return redirect()->back()
                ->with("error", "Selected salary year block is already processed.");
        }*/

        // update only selected data
        // update only selected data in temp salary table bcs. other data may be modified
        // or update by accounts during salary process
        // deduct sum amount employee wise in temp table
        // dump($request->all());
        // dd("reached");
        DB::beginTransaction();
        try {
            // $all_processed_data = licprocessdata::query()
            //     ->where('month', $salary_block->salary_month)
            //     ->where('year', $salary_block->salary_year)
            //     ->active()
            //     ->get();


            /**
             * loop to update from input
             */
            // EmployeeSalaryTemp::query()
            // // ->whereHas("employee", function($query){
            // //     return $query->active();
            // // })
            // ->update([
            //     "claims"    => 0,
            //     "deducts"   => 0
            // ]);
            foreach ($request->datas as $data) {
                $policy_ids[] = $data['policy_id'];
                $amounts[] = $data['monthly_premium'];

                Policy::where("id", $data['policy_id'])
                    ->update([
                        "monthly_premium" => $data['monthly_premium'],
                        //"amount" => $data['monthly_premium'],
                    ]);
            }

            $selected_lic_policies = Policy::query()
                ->whereIn("id", $policy_ids)
                ->active()
                ->get();

            //dd($selected_lic_policies);

            // $procssed_employee_ids = $all_processed_data->pluck('employee_id')->toArray();
            $procssed_employee_ids = [];

            foreach ($selected_lic_policies as $policy) {
                /*$query_data = [
                    'user_id' => $policy->employee_id,
                    'month'       => $salary_block->month,
                    'year'        => $salary_block->year,
                    'policy_id'   => $policy->id,
                    'policy_no'   => $policy->policy_no,
                    'status'      => 1,
                    'processed_by_id' => Auth::id(),
                    'sal_block_id'    => $salary_block->id,
                    'sal_block_month' => $salary_block->sal_block_month,
                    'sal_block_yr'    => $salary_block->sal_block_yr,
                ];*/
                $query_data = [
                    'sal_block_id' => $salary_block->id,
                    'sal_block_month' => $salary_block->month,
                    'sal_block_yr' => $salary_block->year,
                ];

                $procssed_employee_ids[$policy->employee_id] = $policy->employee_id;
                $update_data = [
                    'monthly_premium' => $policy->monthly_premium,
                ];
                /*LicProcessData::updateOrCreate(
                    $query_data,
                    $update_data
                );*/
                $policy = Policy::find($policy->id);  // Retrieve the policy by its ID
                $policy->update($query_data);
                $policy->update($update_data);
            }

            // $lic_policies_for_temp = Policy::query()
            //     ->selectRaw('sum(monthly_premium) as sum, employee_id')
            //     ->whereIn("employee_id", $procssed_employee_ids)
            //     ->groupBy('employee_id')
            //     ->active()
            //     ->get();

            /*
            $lic_policies_for_temp = licprocessdata::query()
                ->where('month', $salary_block->salary_month)
                ->where('year', $salary_block->salary_year)
                ->selectRaw('sum(amount) as sum, employee_id')
                ->whereIn("employee_id", $procssed_employee_ids)
                ->groupBy('employee_id')
                ->with("employee")
                ->active()
                ->get();
                */

            //$sal_head = SalHead::find(SalHead::$LIC_HEAD_ID);
            // finally update in temp table only for previously selected employees.
            /*foreach ($lic_policies_for_temp as $policy) {
                $temp_sal_row = EmployeeSalaryTemp::query()
                    ->where('employee_id', $policy->employee_id)
                    ->where("sal_head_id", SalHead::$LIC_HEAD_ID)
                    // ->active()
                    ->first();
                if ($temp_sal_row) {
                    $temp_sal_row->update([
                        'deducts' => $policy->sum,
                        'status'   => 1,
                    ]);
                } else {
                    EmployeeSalaryTemp::create([
                        'employee_id'      => $policy->employee_id,
                        'employee_code'    => $policy->employee->code,
                        'sal_head_id'      => SalHead::$LIC_HEAD_ID,
                        'salary_head_code' => $sal_head->code,
                        'salary_head'      => $sal_head->name,
                        'salMonth'         => $salary_block->salary_month,
                        'salYear'          => $salary_block->salary_year,
                        'status'           => 1,
                        'deducts'          => $policy->sum,
                        'claims'           => 0.00,
                        "head_type"        => SalHead::$deduction_status,
                    ]);
                }
            }*/
        } catch (\Throwable $th) {
            report($th);
            dd($th);
            return redirect()->back()
                ->with("error", "Something went wrong.");
        }
        DB::commit();
        return redirect()->back()
            ->with("success", "Data added for processing.");
    }
}
