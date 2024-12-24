<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\Department;
use App\Models\AuthDesignation;
use App\Models\AdvanceType;
use App\Models\AdvanceGroup;
use App\Models\Advance;
use App\Models\AdvanceProcess;
use App\Models\salaryHead;
use App\Models\salaryBlock;
use App\Models\AdvanceRequest;
use App\Models\LoanProcessLog;
use Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Redirect;
use Str;
use App\Models\LoanMaster;

class AdvanceController extends Controller
{
    public function index()
    {
        $emp = Employee::select('*')->get();
        $departments = Department::select('id', 'name')->get();
        $designations = AuthDesignation::get();

        $query = Advance::with('employee', 'advanceType');
        $advanceRequests = $query->orderBy('created_at', 'desc')->where("interest_amount", 0)->orWhereNull('interest_amount')
            ->get()
            ->map(function ($advance) {
                // Check if the advance has a reference number in LoanMaster
                $advance->has_loan_master = LoanMaster::where('reference_no', $advance->reference_no)->exists();
                return $advance;
            });
        //->paginate(10);
        //dd($advanceRequests);
        $advanceTypes = AdvanceType::all();
        //dd($advanceTypes);
        // ->whereNotNull('type_name')
        // ->where('deleted_at', null)
        // ->pluck("type_name", "id")
        // ->toArray();

        if (empty($advanceTypes)) {
            $advanceTypes = [];
        }

        return view("advance.index", compact('emp', 'departments', 'designations', 'advanceRequests', 'advanceTypes'));
    }

    public function create()
    {
        $employees = Employee::select('*')->get();
        $designations = AuthDesignation::get();
        $advanceTypes = AdvanceType::all();

        //dd($advanceTypes);
        // $loan_head_principal = loan_head_principal_select_array();

        //dd($loan_head_principal);

        return view("advance.create", compact('employees', 'designations', 'advanceTypes'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        DB::beginTransaction();

        try {

            $find_data = [
                'reference_no' => $request->ref_no,
            ];

            $data = [
                'employee_id' => $request->employee_id,
                'employee_name' => $request->emp_name,
                'reference_no' => $request->ref_no,
                'loan_type_id' => $request->loan_type,
                'loan_head_id' => $request->loan_head,
                'duration' => $request->duration,
                'amount' => $request->amount,
                'monthly_installment' => $request->instalment,
                'recovered_amount' => 0,
                'wef_month' => $request->wef_month,
                'wef_year' => $request->wef_year,
                'start_date' => $request->start_date,
                'closing_date' => $request->closing_date,
                'status' => Advances::$ACTIVE,
            ];

            Advances::updateOrCreate($find_data, $data);
            //dd($data);

        } catch (Exception $e) {
            DB::rollback();
            Log::critical($e);
            dd($e->getMessage());
            $request->session()->flash('error', 'Something went wrong');

            //return back();
        }
        DB::commit();
        $request->session()->flash('success', 'Successfully added');
        return back();
    }

    public function edit($id)
    {
        //dd($id);
        $employees = Employee::select('*')->get();
        $designations = AuthDesignation::get();
        $advance = Advance::where('id', $id)->first();
        //dd($advance);
        $advanceTypes = AdvanceType::all();
        return view("advance.edit", compact('employees', 'designations', 'advance', 'advanceTypes'));
    }

    public function edit_advance_list()
    {
        $employees = Employee::select('*')->get();
        $designations = AuthDesignation::get();

        $advances = Advance::query()
            ->with("employees", "advanceType")
            ->orderBy('id', 'desc')
            ->get();

        $advance_types = AdvanceType::query()
            ->advanceType()
            ->active()
            ->pluck("name", "id")
            ->toArray();
        //dd($loan_head_principal);
        //dd($advances);

        return view("advances.update_advance", compact('employees', 'designations', 'advances', 'advance_types'));
    }

    public function update_advance(Request $request, string $id)
    {
        // dd($request->all());
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'loan_type_id' => 'required|numeric|min:1',
            'loan_amount' => 'required|numeric|min:0',
            'loan_interest_rate' => 'nullable|numeric|min:0',
            'principal_amount' => 'required|numeric|min:0',
            'no_of_installment' => 'required|integer|min:1',
            'monthly_emi' => 'required|numeric|min:0',
            'adj_emi' => 'nullable|numeric|min:0',
            'adj_emi_in' => 'nullable|string',
            'interest_amount' => 'nullable|numeric|min:0',
            'interest_installment' => 'nullable|numeric|min:0',
            'adj_interest_emi' => 'nullable|numeric|min:0',
            'adj_interest_emi_in' => 'nullable|string',
            'wef_month' => 'required|integer|min:1|max:12',
            'wef_year' => 'required|integer|min:2000',
            //'sal_block_id' => 'required|integer',
            'close_advance' => 'nullable|string',
            'closed_from_month' => 'nullable|integer|min:1|max:12',
            'closed_from_year' => 'nullable|integer|min:2000',
            'closed_to_month' => 'nullable|integer|min:1|max:12',
            'closed_to_year' => 'nullable|integer|min:2000',
        ]);

        try {
            DB::beginTransaction();

            // Update Advance record
            $advance = Advance::findOrFail($id);
            $data = [
                'principal_amount' => $request->principal_amount,
                'duration' => $request->no_of_installment,
                'monthly_installment' => $request->monthly_emi,
                'recovered_amount' => $request->recovered_amount ?? 0,
                'interest_amount' => $request->interest_amount ?? 0,
                'interest_recovered' => $request->interest_recovered ?? 0,
                'installment_month' => $request->wef_month,
                'installment_year' => $request->wef_year,
                //'start_date' => $request->start_date,
                'closing_advance' => $request->close_advance ?? false,
                'closed_from_month' => $request->closed_from_month,
                'closed_from_year' => $request->closed_from_year,
                'closed_to_month' => $request->closed_to_month,
                'closed_to_year' => $request->closed_to_year,
                'updated_by' => auth()->id()
            ];
            $advance->update($data);

            // Update or Create LoanMaster record
            $employee = Employee::where('user_id', $request->employee_id)->first();
            $emp_dept = Employee::where('code', $request->emp_code)->first()->department_id;
            $emp_desig = Employee::where('code', $request->emp_code)->first()->designation_id;

            $loanAmnt = $request->principal_amount + $request->interest_amount;
            $loanMasterData = [
                'reference_no' => $request->ref_no,
                'user_id' => $request->employee_id,
                'emp_code' => $request->emp_code ?? null,
                'fld_deptid' => $emp_dept,
                'fld_desigid' => $emp_desig,
                'loan_type_id' => $request->loan_type_id,
                'loan_amount' => $loanAmnt,
                'loan_interest_rate' => $request->loan_interest_rate,
                'principal_amount' => $request->principal_amount,
                'outstanding_principal' => $request->principal_amount, // Initially same as principal
                'no_of_installment' => $request->no_of_installment,
                'principal_installment' => $request->monthly_emi,
                'monthly_emi' => $request->monthly_emi,
                'adj_emi' => $request->adj_emi,
                'adj_emi_in' => $request->adj_emi_in,
                'interest_amount' => $request->interest_amount,
                'no_of_installment_interest' => $request->no_of_installment_interest,
                'outstanding_interest_amount' => $request->interest_amount, // Initially same as interest amount
                'interest_installment' => $request->interest_installment,
                'adj_interest_emi' => $request->adj_interest_emi,
                'adj_interest_emi_in' => $request->adj_interest_emi_in,
                //'sal_block_id' => $request->sal_block_id
                //'updated_by' => auth()->id()
            ];

            $salaryBlock = salaryBlock::find($request->sal_block_id);

            /*if ($salaryBlock) {
                $loanMasterData['sal_block_month'] = $salaryBlock->month;
                $loanMasterData['sal_block_yr'] = $salaryBlock->year;
            } else {
                // Handle if no SalaryBlock is found (you can set null or handle the error)
                $loanMasterData['sal_block_month'] = null;
                $loanMasterData['sal_block_yr'] = null;
            }*/
            //dd($loanMasterData);
            LoanMaster::updateOrCreate(
                ['reference_no' => $request->ref_no],
                $loanMasterData
            );

            DB::commit();
            return redirect()->route('advance.index')->with('success', 'Advance updated successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error updating advance: ' . $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        //dd($request->all());


        $employees = Employee::select('*')->get();
        $designations = AuthDesignation::get();

        $advances = Advances::query()
            ->when(request("type"), function ($query) {
                return $query->where('loan_type_id', request("type"));
            })
            ->when($request->emp_id, function ($query) {
                return $query->where('employee_id', request("emp_id"));
            })
            ->when(!is_null(request("status")), function ($query) {
                return $query->where('status', request("status"));
            })
            ->with('employees')->get();

        $advance_types = AdvanceType::query()
            ->advanceType()
            ->active()
            ->pluck("name", "id")
            ->toArray();

        return view("advances.update_advance", compact('employees', 'designations', 'advances', 'advance_types'));
    }


    public function process_advance()
    {
        $emp = Employee::all();
        $departments = Department::select('id', 'name')->get();
        $salarystatus = salaryBlock::where('sal_process_status', 'Unblock')->first();

        //dd($salarystatus->isAdvanceProcessed());

        /*$advances = Advance::filter()
            // ->active()
            ->with(["advanceprocessdata" => function ($query) use ($salarystatus) {
                return $query->where("year", optional($salarystatus)->year)
                    ->where("month", optional($salarystatus)->month);
            }],'employee', 'advanceType', 'salhead')
            ->orderBy('user_id', 'desc')
            ->paginate(100);*/

        $advances = LoanMaster::filter()
            // ->active()
            ->with([
                "advanceprocessdata" => function ($query) use ($salarystatus) {
                    return $query->where("year", optional($salarystatus)->year)
                        ->where("month", optional($salarystatus)->month);
                }
            ], 'employee', 'advanceType', 'salhead')->where("interest_amount", 0)->orWhereNull('interest_amount')
            ->orderBy('user_id', 'desc')
            ->paginate(100);

        /*$query = Advance::with('employee', 'advanceType');
        $advances = $query->orderBy('created_at', 'desc')->paginate(10);*/

        $advanceTypes = AdvanceType::all();
        //dd($advances);

        return view('advance.process_advance', compact('salarystatus', 'emp', 'departments', 'advances', "advanceTypes"));
    }

    public function process_advance_data(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'datas' => 'required|array|min:1',
            'datas.policy_ids.*' => 'required|numeric|min:1',
            'datas.loan_head_ids.*' => 'required|numeric|min:1',
            'salary_block_id' => 'required|exists:salary_blocks,id',
        ], [], [
            'salary_block_id' => 'Salary Year',
        ]);

        //dd($request->all());
        /**
         * Fetch the salary block and check if it is active
         */

        $salary_block = salaryBlock::where("id", $request->salary_block_id)->active()->first();

        if (!$salary_block) {
            return redirect()->back()
                ->with("error", "Selected salary year block is not active.");
        }
        if ($salary_block->isAdvanceProcessed()) {
            return redirect()->back()
                ->with("error", "Selected salary year block is already processed.");
        }


        /**
         * On active block, fetch advance ids and amounts
         *  update monthly installment if it is manually entered.
         */
        //dd($request->allrows);

        DB::beginTransaction();
        try {
            //log
            foreach ($request->allrows as $ldata) {
                $adv_ids[] = $ldata['advance_id'];
            }

            $loans = LoanMaster::query()        //$selected_advances = Advance::query()
                ->whereIn("id", $adv_ids)
                //->active()
                ->get();

            foreach ($loans as $loan) {
                if ($loan->principal_instllmnt_status == 'completed' || $loan->outstanding_principal <= 0) {
                    $installmentType = 'interest';
                } else {
                    $installmentType = 'principal';
                }

                $log_data = [
                    'loan_id' => $loan->id,
                    'ref_no' => $loan->reference_no,
                    'employee_id' => $loan->user_id,
                    'emp_code' => $loan->emp_code,
                    'monthly_emi' => $loan->monthly_emi,
                    'interest_installment' => $loan->interest_installment,
                    'process_by' => auth()->user()->id,
                    'process_date' => now(),
                    'principal_or_interest' => $installmentType,
                    'month' => $salary_block->month, //salary month
                    'year' => $salary_block->year, //salary year
                    'type' => "advance",
                    'ip_address' => request()->ip(),
                ];
                LoanProcessLog::create($log_data);
            }
            //  dd($loans);
            //log---

            $advance_ids = [];
            $amounts = [];

            foreach ($request->datas as $data) {
                if (isset($data['advance_id']) && isset($data['monthly_premium'])) {
                    $advance_ids[] = $data['advance_id'];
                    $amounts[] = $data['monthly_premium'];
                    Advance::where("id", $data['advance_id'])
                        ->update([
                            "monthly_installment" => $data['monthly_premium'],
                        ]);
                }
            }

            // dd($request->datas);

            /**
             * Get all the selected advances and check if they are active
             * Create an empty array $processes_employee_ids to store employee ids that are processed
             *
             * If the advance is completely recoved, update the status to closed
             * Else, increment the recovered amount and create addvance processed data for the month.
             */
            $selected_advances = LoanMaster::query()        //$selected_advances = Advance::query()
                ->whereIn("id", $advance_ids)
                //->active()
                ->get();

            //dd($selected_advances);
            $procssed_employee_ids = [];

            foreach ($selected_advances as $advance) {
                if ($advance->recovered_amount >= $advance->principal_amount) {
                    $advance->status = 0;
                    //$advance->save();
                } else {
                    $advance_data = [
                        'recovered_amount' => $advance->monthly_installment,
                    ];

                    // Advance::where("id", $advance->id)
                    //     ->increment('recovered_amount', $advance->monthly_installment);

                    // ->update(array('recovered_amount', DB::raw('recovered_amount + $advance->monthly_installment')));

                    $query_data = [
                        'employee_id' => $advance->user_id,
                        'emp_code' => $advance->emp_code,
                        'reference_no' => $advance->reference_no,
                        'loan_head_id' => $advance->loan_type_id,
                        'month' => $salary_block->month, //salary month
                        'year' => $salary_block->year, //salary year
                        'advance_id' => $advance->id,
                        'status' => AdvanceProcess::$ACTIVE,
                    ];

                    $procssed_employee_ids[$advance->user_id] = $advance->user_id;
                    $update_data = [
                        'amount' => $advance->monthly_emi,
                    ];
                    AdvanceProcess::updateOrCreate(
                        $query_data,
                        $update_data
                    );
                    LoanMaster::where("reference_no", $advance->reference_no)
                        ->update([
                            "sal_block_id" => $salary_block->id,
                            "sal_block_month" => $salary_block->month,
                            "sal_block_yr" => $salary_block->year
                        ]);
                    //log
                    //dd($advance);
                    $log_data = [
                        'is_processed' => 1
                    ];
                    LoanProcessLog::where('ref_no', $advance->reference_no)->update($log_data);
                }
            }

        } catch (\Throwable $th) {
            report($th);
            dd($th->getMessage());
            return redirect()->back()->with("error", "Something went wrong.");
        }
        DB::commit();
        return redirect()->back()->with("success", "Data added for processing.");
    }

    public function processed_data_list()
    {
        $salary_block = salaryBlock::where("sal_process_status", "Unblock")
            ->active()
            ->first();
        request()->merge([
            "month" => request("month", $salary_block->month),
            "year" => request("year", $salary_block->year),
        ]);
        $departments = Department::select("name", "id")->get();
        $employees = Employee::select("id", "first_name", "middle_name", "last_name", "code")
            //->active()
            ->get();
        $processed_data_query = AdvanceProcess::query()
            ->with("employee:user_id,first_name,middle_name,last_name,code", "advanceType:id,type_name")
            ->monthYearFilter(request("month"), request("year"))
            ->when(request("employee_id"), function ($query) {
                return $query->where("employee_id", request("employee_id"));
            })
            ->when(request("type"), function ($query) {
                return $query->whereHas("advances", function ($query) {
                    return $query->where("loan_type_id", request("type"));
                });
            })
            ->when(request("department_id"), function ($query) {
                return $query->whereHas("employee", function ($query) {
                    return $query->where("department_id", request("department_id"));
                });
            });
        /*->whereHas('advances', function ($query) {
            return $query->where("interest_amount", "=", 0);
        })*/

        if (request("export") == "excel") {
            return $this->exportToExcel($processed_data_query, request("month"), request("year"));
        }
        $processed_data = $processed_data_query->orderBy('employee_id')->active()->get();
        // dd($processed_data);
        $advance_types = AdvanceType::query()
            //->advanceType()
            //->active()
            ->pluck("type_name", "id")
            ->toArray();
        return view('advance.processed_data_list', compact('processed_data', 'departments', "employees", "salary_block", "advance_types"));
    }

    public function deleteProcessedData(AdvancesProcess $id)
    {
        if (!$id->isProcessingAllowed()) {
            return redirect()
                ->back()
                ->with("error", "This record is already processed. Unable to delete.");
        }

        //dd($id);
        $id->delete();
        return redirect()
            ->back()
            ->with("success", "Record deleted successfully.");
    }


    public function createExisting()
    {
        $employees = Employee::select('*')->get();
        $designations = AuthDesignation::get();
        $advanceGroups = AdvanceGroup::all();
        $advanceTypes = AdvanceType::all();
        $salaryheads = salaryHead::all();
        return view("advance.existing", compact('employees', 'advanceTypes', 'salaryheads', 'advanceGroups'));
    }

    public function storeExisting(Request $request)
    {
        /*$request->validate([
            'user_id' => 'required|int',
            'code' => 'required|string',
            'advance_type_id' => 'required|int',
            'amount_requested' => 'required|numeric',
            'reason' => 'nullable|string|max:1000',
            'monthly_installment' => 'nullable|numeric',
            'installment_year' => 'nullable|numeric',
            'installment_month' => 'nullable|string',
            'payslip_1' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'payslip_2' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'payslip_3' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'document_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);*/

        // Store payslip 1
        /*$payslipPath1 = null;
        if(isset($request->payslip_1)){
            $empCode = Auth::user()->emp_code;
            $date = now()->format('dmyHis');
            $uploadPath = public_path("uploads/{$empCode}/advance/");
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $file = $request->payslip_1;
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = "payslip1_{$date}.{$fileExtension}";
            $payslipPath1 = "uploads/{$empCode}/advance/{$fileName}";
            $file->move($uploadPath, $fileName);
        }

        // Store payslip 2
        $payslipPath2 = null;
        if(isset($request->payslip_2)){
            $empCode = Auth::user()->emp_code;
            $date = now()->format('dmyHis');
            $uploadPath = public_path("uploads/{$empCode}/advance/");
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $file = $request->payslip_2;
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = "payslip2_{$date}.{$fileExtension}";
            $payslipPath2 = "uploads/{$empCode}/advance/{$fileName}";
            $file->move($uploadPath, $fileName);
        }

        // Store payslip 3
        $payslipPath3 = null;
        if(isset($request->payslip_3)){
            $empCode = Auth::user()->emp_code;
            $date = now()->format('dmyHis');
            $uploadPath = public_path("uploads/{$empCode}/advance/");
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $file = $request->payslip_3;
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = "payslip3_{$date}.{$fileExtension}";
            $payslipPath3 = "uploads/{$empCode}/advance/{$fileName}";
            $file->move($uploadPath, $fileName);
        }
        $data['payslip_1'] = $payslipPath1;
        $data['payslip_2'] = $payslipPath2;
        $data['payslip_3'] = $payslipPath3;

        // Store additional document if provided
        if ($request->hasFile('document_path')) {
            $empCode = Auth::user()->emp_code;
            $date = now()->format('dmyHis');
            $uploadPath = public_path("uploads/{$empCode}/documents/");
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $document = $request->file('document_path');
            $fileExtension = $document->getClientOriginalExtension();
            $fileName = "{$date}.{$fileExtension}";
            $documentPath = "uploads/{$empCode}/documents/{$fileName}";
            $document->move($uploadPath, $fileName);
            $data['document_path'] = $documentPath;
        }*/

        // Generate reference number
        $currentDate = now();
        $year = $currentDate->format('y');
        $month = str_pad($currentDate->format('m'), 2, '0', STR_PAD_LEFT);

        // Get the last reference number
        $lastRefNo = AdvanceRequest::whereNotNull('reference_no')
            ->where('reference_no', 'LIKE', "ADV/{$year}/{$month}/%")
            ->orderBy('id', 'desc')
            ->first();

        $sequence = '0001';

        if ($lastRefNo) {
            $parts = explode('/', $lastRefNo->reference_no);
            if (count($parts) == 4) {
                $lastSequence = intval($parts[3]);
                $sequence = str_pad($lastSequence + 1, 4, '0', STR_PAD_LEFT);
            }
        }

        //dd($request->all());

        $referenceNo = "ADV/{$year}/{$month}/{$sequence}";

        $advance = new Advance();
        $advance->reference_no = $request->reference_no;
        $advance->user_id = $request->employee_id;
        // Get employee details
        $employee = Employee::where('user_id', $request->employee_id)->first();
        $advance->emp_code = $employee ? $employee->code : '';
        $advance->advance_id = $request->advance_type_id;
        $advance->loan_head_id = $request->sal_block_id;
        $advance->principal_amount = $request->principal_amount;
        $advance->monthly_installment = $request->monthly_emi;
        $advance->recovered_amount = $request->outstanding_principal;
        $advance->interest_amount = $request->interest_amount;
        $advance->interest_recovered = $request->interest_recovered;
        // $advance->start_date = $request->start_date;
        // $advance->closing_date = $request->closing_date;
        $advance->installment_year = $request->installment_year;
        $advance->installment_month = $request->installment_month;
        $advance->adjustable_installment = $request->adjustable_installment;
        $advance->adjust_in = $request->adjust_in;
        $advance->duration = $request->no_of_installment;
        $advance->installment_month = $request->wef_month;
        $advance->installment_year = $request->wef_year;
        $advance->adjustable_installment = $request->adj_emi;
        $advance->adjust_in = $request->adj_emi_in;
        /*$advance->payslip_1 = $data['payslip_1'];
        $advance->payslip_2 = $data['payslip_2'];
        $advance->payslip_3 = $data['payslip_3'];
        $advance->document_path = $data['document_path'];*/
        $advance->save();


        $employee = Employee::where('user_id', $request->employee_id)->first();
        $emp_code = $employee->code;
        $emp_dept = $employee->department_id;
        $emp_desig = $employee->designation_id;
        //dd($emp_code, $emp_dept, $emp_desig);

        $loanMasterData = [
            'reference_no' => $request->reference_no,
            'user_id' => $request->employee_id,
            'emp_code' => $emp_code ?? null,
            'fld_deptid' => $emp_dept,
            'fld_desigid' => $emp_desig,
            'loan_type_id' => $request->advance_type_id,
            'loan_amount' => $request->loan_amount,
            'loan_interest_rate' => $request->loan_interest_rate,
            'principal_amount' => $request->principal_amount,
            'outstanding_principal' => $request->outstanding_principal, // Initially same as principal
            'no_of_installment' => $request->no_of_installment,
            'principal_installment' => $request->monthly_emi,
            'monthly_emi' => $request->monthly_emi,
            'adj_emi' => $request->adj_emi,
            'adj_emi_in' => $request->adj_emi_in,
            'interest_amount' => $request->interest_amount,
            'no_of_installment_interest' => $request->no_of_installment_interest,
            'outstanding_interest_amount' => $request->interest_amount, // Initially same as interest amount
            'interest_installment' => $request->interest_installment,
            'adj_interest_emi' => $request->adj_interest_emi,
            'adj_interest_emi_in' => $request->adj_interest_emi_in,
            'sal_block_id' => $request->sal_block_id,
            'from_yyyy' => $request->wef_year,
            'from_mm' => $request->wef_month,
            //'updated_by' => auth()->id()
        ];

        $salaryBlock = salaryBlock::find($request->sal_block_id);

        /*if ($salaryBlock) {
            $loanMasterData['sal_block_month'] = $salaryBlock->month;
            $loanMasterData['sal_block_yr'] = $salaryBlock->year;
        } else {
            // Handle if no SalaryBlock is found (you can set null or handle the error)
            $loanMasterData['sal_block_month'] = null;
            $loanMasterData['sal_block_yr'] = null;
        }*/

        //dd($loanMasterData);
        LoanMaster::firstOrCreate(
            $loanMasterData
        );

        return redirect()->route('advance.index')->with('success', 'Existing advance added successfully');
    }

    public function ViewAdvances()
    {
        $emp = Employee::all();
        $departments = Department::select('id', 'name')->get();
        $advanceTypes = AdvanceType::all();

        $advances = LoanMaster::filter()
            ->with(['employee', 'advanceType', 'salhead'])->where("interest_amount", "=", 0)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('advance.view_advances', compact('advances', 'emp', 'departments', 'advanceTypes'));
    }

    public function ViewAdvanceDetails($id)
    {
        // Fetch the loan master details from the view (or table)
        $loanMaster = LoanMaster::find($id);

        if (!$loanMaster) {
            return redirect()->route('advance.index')->with('error', 'Loan Master not found!');
        }

        return view('advance.viewadvancedetails', compact('loanMaster'));
    }
}
