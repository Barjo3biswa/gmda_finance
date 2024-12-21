<?php

namespace App\Http\Controllers;
use App\Models\Employee;
use App\Models\Department;
use App\Models\AuthDesignation;
use App\Models\AdvanceType;
use App\Models\AdvanceGroup;
use App\Models\Advance;
use App\Models\AdvanceProcess;
use App\Models\SalaryHead;
use App\Models\SalaryBlock;
use App\Models\AdvanceRequest;
use App\Models\LoanMaster;
use App\Models\LoanProcessLog;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $emp = Employee::select('*')->get();
        $departments = Department::select('id', 'name')->get();
        $designations = AuthDesignation::get();

        $query = Advance::with('employee', 'advanceType');
        $advanceRequests = $query->orderBy('created_at', 'desc')->where("interest_amount", ">", 0)
        ->paginate(10);
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

        return view("loan.index", compact('emp', 'departments', 'designations', 'advanceRequests', 'advanceTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::select('*')->get();
        $designations = AuthDesignation::get();
        $advanceGroups = AdvanceGroup::all();
        $advanceTypes = AdvanceType::all();
        $salaryheads = SalaryHead::all();
        return view("loan.create", compact('employees', 'advanceTypes', 'salaryheads', 'advanceGroups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Generate reference number
        $currentDate = now();
        $year = $currentDate->format('y');
        $month = str_pad($currentDate->format('m'), 2, '0', STR_PAD_LEFT);
        
        // Get the last reference number
        $lastRefNo = LoanMaster::whereNotNull('reference_no')
            ->where('reference_no', 'LIKE', "LN/{$year}/{$month}/%")
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
        
        $referenceNo = "LN/{$year}/{$month}/{$sequence}";

        // dd($referenceNo);
        
        $advance = new Advance();
        $advance->reference_no = $referenceNo;
        $advance->user_id = $request->employee_id;
        // Get employee details
        $employee = Employee::where('user_id', $request->employee_id)->first();
        $advance->emp_code = $employee ? $employee->code : '';
        $advance->advance_id = $request->loan_head_id;
        $advance->loan_head_id = $request->sal_block_id;
        $advance->principal_amount = $request->principal_amount;
        $advance->monthly_installment = $request->monthly_emi;
        //$advance->recovered_amount = $request->recovered_amount;
        $advance->interest_amount = $request->interest_amount;
        $advance->interest_recovered = $request->interest_recovered;
        // $advance->start_date = $request->start_date;
        // $advance->closing_date = $request->closing_date;
        $advance->installment_year = $request->installment_year;
        $advance->installment_month = $request->installment_month;
        $advance->adjustable_installment = $request->adjustable_installment;
        $advance->adjust_in = $request->adjust_in;
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
            'reference_no' => $referenceNo,
            'user_id' => $request->employee_id,
            'emp_code' => $emp_code ?? null,
            'fld_deptid' => $emp_dept,
            'fld_desigid' => $emp_desig,
            'loan_type_id' => $request->loan_head_id,
            'loan_amount' => $request->loan_amount,
            'loan_interest_rate' => $request->loan_interest_rate,
            'principal_amount' => $request->principal_amount,
            'outstanding_principal' => $request->principal_amount,
            'no_of_installment' => $request->no_of_installment,
            'principal_installment' => $request->monthly_emi,
            'monthly_emi' => $request->monthly_emi,
            'adj_emi' => $request->adj_emi,
            'adj_emi_in' => $request->adj_emi_in,
            'interest_amount' => $request->interest_amount,
            'no_of_installment_interest' => $request->no_of_installment_interest,
            'outstanding_interest_amount' => $request->interest_amount,
            'interest_installment' => 0,
            'interest_emi' => $request->interest_installment,
            'adj_interest_emi' => $request->adj_interest_emi,
            'adj_interest_emi_in' => $request->adj_interest_emi_in,
            'sal_block_id' => $request->sal_block_id,
            'from_yyyy' => $request->wef_year,
            'from_mm' => $request->wef_month,
            'applied_on' => now(),
            'applied_for' => 'Existing Loan'
        ];

        //dd($loanMasterData);
        
        $salaryBlock = SalaryBlock::find($request->sal_block_id);

        /*
        if ($salaryBlock) {
            $loanMasterData['sal_block_month'] = $salaryBlock->month;
            $loanMasterData['sal_block_yr'] = $salaryBlock->year;
        } else {
            // Handle if no SalaryBlock is found (you can set null or handle the error)
            $loanMasterData['sal_block_month'] = null;
            $loanMasterData['sal_block_yr'] = null;
        }
        */

        //dd($loanMasterData);
        LoanMaster::firstOrCreate(
            $loanMasterData
        );

        return redirect()->route('loan.index')->with('success', 'New loan added successfully');
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
        // Fetch the loan data
        $loan = LoanMaster::findOrFail($id);

        // Fetch related data (e.g., employees, loan types, salary heads)
        $employees = Employee::all();
        $advanceTypes = AdvanceType::all();
        $salaryheads = SalaryHead::all();

        // Return the edit view with the current loan data
        return view('loan.edit', compact('loan', 'employees', 'advanceTypes', 'salaryheads'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($referenceNo);
        
        $advance = new Advance();
        $advance->user_id = $request->employee_id;
        // Get employee details
        $employee = Employee::where('user_id', $request->employee_id)->first();
        $advance->emp_code = $employee ? $employee->code : '';
        $advance->advance_id = $request->loan_head_id;
        $advance->loan_head_id = $request->sal_block_id;
        $advance->principal_amount = $request->principal_amount;
        $advance->monthly_installment = $request->monthly_emi;
        //$advance->recovered_amount = $request->recovered_amount;
        $advance->interest_amount = $request->interest_amount;
        $advance->interest_recovered = $request->interest_recovered;
        // $advance->start_date = $request->start_date;
        // $advance->closing_date = $request->closing_date;
        $advance->installment_year = $request->installment_year;
        $advance->installment_month = $request->installment_month;
        $advance->adjustable_installment = $request->adjustable_installment;
        $advance->adjust_in = $request->adjust_in;
        /*$advance->payslip_1 = $data['payslip_1'];
        $advance->payslip_2 = $data['payslip_2'];
        $advance->payslip_3 = $data['payslip_3'];
        $advance->document_path = $data['document_path'];*/
        
        $advance->update()-where('reference_no', $request->reference_no);

        $employee = Employee::where('user_id', $request->employee_id)->first();
        $emp_code = $employee->code;
        $emp_dept = $employee->department_id;
        $emp_desig = $employee->designation_id;
        //dd($emp_code, $emp_dept, $emp_desig);

        $loanMasterData = [
            'reference_no' => $referenceNo,
            'user_id' => $request->employee_id,
            'emp_code' => $emp_code ?? null,
            'fld_deptid' => $emp_dept,
            'fld_desigid' => $emp_desig,
            'loan_type_id' => $request->loan_head_id,
            'loan_amount' => $request->loan_amount,
            'loan_interest_rate' => $request->loan_interest_rate,
            'principal_amount' => $request->principal_amount,
            'outstanding_principal' => $request->principal_amount,
            'no_of_installment' => $request->no_of_installment,
            'principal_installment' => $request->monthly_emi,
            'monthly_emi' => $request->monthly_emi,
            'adj_emi' => $request->adj_emi,
            'adj_emi_in' => $request->adj_emi_in,
            'interest_amount' => $request->interest_amount,
            'no_of_installment_interest' => $request->no_of_installment_interest,
            'outstanding_interest_amount' => $request->interest_amount,
            'interest_installment' => 0,
            'interest_emi' => $request->interest_installment,
            'adj_interest_emi' => $request->adj_interest_emi,
            'adj_interest_emi_in' => $request->adj_interest_emi_in,
            'sal_block_id' => $request->sal_block_id,
            'from_yyyy' => $request->wef_year,
            'from_mm' => $request->wef_month,
            'applied_on' => now(),
            'applied_for' => 'Existing Loan'
        ];

        //dd($loanMasterData);
        
        $salaryBlock = SalaryBlock::find($request->sal_block_id);

        /*
        if ($salaryBlock) {
            $loanMasterData['sal_block_month'] = $salaryBlock->month;
            $loanMasterData['sal_block_yr'] = $salaryBlock->year;
        } else {
            // Handle if no SalaryBlock is found (you can set null or handle the error)
            $loanMasterData['sal_block_month'] = null;
            $loanMasterData['sal_block_yr'] = null;
        }
        */

        //dd($loanMasterData);
        LoanMaster::updateOrCreate(
            $loanMasterData
        )->where('id', $id)->update();

        return redirect()->route('loan.index')->with('success', 'loan updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function createExisting()
    {
        $employees = Employee::select('*')->get();
        $designations = AuthDesignation::get();
        $advanceGroups = AdvanceGroup::all();
        $advanceTypes = AdvanceType::all();
        $salaryheads = SalaryHead::all();
        return view("loan.existing", compact('employees', 'advanceTypes', 'salaryheads', 'advanceGroups'));
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
        
        $referenceNo = "ADV/{$year}/{$month}/{$sequence}";
        
        $advance = new Advance();
        $advance->reference_no = $request->reference_no;
        $advance->user_id = $request->employee_id;
        // Get employee details
        $employee = Employee::where('user_id', $request->employee_id)->first();
        $advance->emp_code = $employee ? $employee->code : '';
        $advance->advance_id = $request->loan_head_id;
        $advance->loan_head_id = $request->sal_block_id;
        $advance->principal_amount = $request->principal_amount;
        //$advance->outstanding_principal = $request->outstanding_principal;
        $advance->monthly_installment = $request->monthly_installment;
        $advance->recovered_amount = $request->recovered_amount;
        $advance->interest_amount = $request->interest_amount;
        $advance->interest_recovered = $request->interest_recovered;
        // $advance->start_date = $request->start_date;
        // $advance->closing_date = $request->closing_date;
        $advance->installment_year = $request->installment_year;
        $advance->installment_month = $request->installment_month;
        $advance->adjustable_installment = $request->adjustable_installment;
        $advance->adjust_in = $request->adjust_in;
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
            'loan_type_id' => $request->loan_head_id,
            'loan_amount' => $request->loan_amount,
            'loan_interest_rate' => $request->loan_interest_rate,
            'principal_amount' => $request->principal_amount,
            'outstanding_principal' => $request->outstanding_principal,
            'no_of_installment' => $request->no_of_installment,
            'principal_installment' => $request->monthly_emi,
            'monthly_emi' => $request->monthly_emi,
            'adj_emi' => $request->adj_emi,
            'adj_emi_in' => $request->adj_emi_in,
            'interest_amount' => $request->interest_amount,
            'no_of_installment_interest' => $request->no_of_installment_interest,
            'outstanding_interest_amount' => $request->outstanding_interest_amount,
            'interest_installment' => 0,
            'interest_emi' => $request->interest_installment,
            'adj_interest_emi' => $request->adj_interest_emi,
            'adj_interest_emi_in' => $request->adj_interest_emi_in,
            'from_yyyy' => $request->wef_year,
            'from_mm' => $request->wef_month,
            'sal_block_id' => $request->sal_block_id,
            'applied_on' => now(),
            'applied_for' => 'Existing Loan'
        ];
        
        $salaryBlock = SalaryBlock::find($request->sal_block_id);

        /*
        if ($salaryBlock) {
            $loanMasterData['sal_block_month'] = $salaryBlock->month;
            $loanMasterData['sal_block_yr'] = $salaryBlock->year;
        } else {
            // Handle if no SalaryBlock is found (you can set null or handle the error)
            $loanMasterData['sal_block_month'] = null;
            $loanMasterData['sal_block_yr'] = null;
        }
        */

        //dd($loanMasterData);
        LoanMaster::firstOrCreate(
            $loanMasterData
        );

        return redirect()->route('loan.index')->with('success', 'Existing loan added successfully');
    }


    public function process_loan()
    {
        $emp = Employee::all();
        $departments  = Department::select('id', 'name')->get();
        $salarystatus = SalaryBlock::where('sal_process_status', 'Unblock')->where("is_finalized", 0)->first();

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
            ->with(["advanceprocessdata" => function ($query) use ($salarystatus) {
                return $query->where("year", optional($salarystatus)->year)
                    ->where("month", optional($salarystatus)->month);
            }],'employee', 'advanceType', 'salhead')->where("interest_amount", ">", 0)
            ->orderBy('user_id', 'desc')
            ->paginate(100);

        /*$query = Advance::with('employee', 'advanceType');
        $advances = $query->orderBy('created_at', 'desc')->paginate(10);*/

        $advanceTypes = AdvanceType::all();
        //dd($advances);

        return view('loan.process', compact('salarystatus', 'emp', 'departments', 'advances', "advanceTypes"));
    }

    public function process_loan_data(Request $request)
    {

        // dd($request->all());

        $request->validate([
            'datas'                 => 'required|array|min:1',
            'datas.policy_ids.*'    => 'required|numeric|min:1',
            'datas.loan_head_ids.*' => 'required|numeric|min:1',
            'salary_block_id'       => 'required|exists:salary_blocks,id',
        ], [], [
            'salary_block_id'       => 'Salary Year',
        ]);

        //dd($request->all());
        /**
         * Fetch the salary block and check if it is active
         */

        
         $salary_block = SalaryBlock::where("id", $request->salary_block_id)
            ->active()
            ->first();

        //dd($salary_block);

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

        //dd($request->datas);
        DB::beginTransaction();
        try {
            //log
            foreach($request->allrows as $ldata){
                $adv_ids[] = $ldata['advance_id'];
            }
    
            $loans = LoanMaster::query()        //$selected_advances = Advance::query()
                    ->whereIn("id", $adv_ids)
                    //->active()
                    ->get();
    
                    foreach($loans as $loan){
                        if ($loan->principal_instllmnt_status == 'completed' || $loan->outstanding_principal <= 0) {
                            $installmentType = 'interest';
                        } else {
                            $installmentType = 'principal';
                        }
    
                        $log_data = [
                            'loan_id'   => $loan->id,
                            'ref_no'    => $loan->reference_no,
                            'employee_id'    => $loan->user_id,
                            'emp_code'    => $loan->emp_code,
                            'monthly_emi'    => $loan->monthly_emi,
                            'interest_installment' => $loan->interest_installment,
                            'process_by'     => auth()->user()->id,
                            'process_date'   => now(),
                            'principal_or_interest' => $installmentType,
                            'month'          => $salary_block->month, //salary month
                            'year'           => $salary_block->year, //salary year
                            'type'           => "advance",
                            'ip_address'     => request()->ip(),
                        ];
                        LoanProcessLog::create($log_data);
                    }
            //  dd($loans);
            //log---

            $advance_ids = [];
            $amounts = [];

            //dd($request->datas);
            foreach($request->datas as $data){
                if (isset($data['advance_id']) && isset($data['monthly_premium'])) {
                $advance_ids[] = $data['advance_id'];
                $amounts[] = $data['monthly_premium'];
                Advance::where("id", $data['advance_id'])
                    ->update([
                        "monthly_installment" => $data['monthly_premium'],
                    ]);
                }
            }

            /**
             * Get all the selected advances and check if they are active
             * Create an empty array $processes_employee_ids to store employee ids that are processed
             *
             * If the advance is completely recoved, update the status to closed
             * Else, increment the recovered amount and create addvance processed data for the month.
             */
            $selected_advances = LoanMaster::query()
                ->whereIn("id", $advance_ids)
                //->active()
                ->get();

            //dd($selected_advances);
            $procssed_employee_ids = [];

            foreach ($selected_advances as $advance) {
                if($advance->recovered_amount >= $advance->principal_amount){
                    $advance->status = 0;
                    //$advance->save();
                } else {
                    $advance_data = ['recovered_amount' => $advance->monthly_emi,];

                    // Advance::where("id", $advance->id)
                    //     ->increment('recovered_amount', $advance->monthly_installment);

                        // ->update(array('recovered_amount', DB::raw('recovered_amount + $advance->monthly_installment')));

                    $query_data = [
                        'employee_id'    => $advance->user_id,
                        'emp_code'    => $advance->emp_code,
                        'reference_no'   => $advance->reference_no,
                        'loan_head_id'   => $advance->loan_type_id,
                        'month'          => $salary_block->month, //salary month
                        'year'           => $salary_block->year, //salary year
                        'advance_id'     => $advance->id,
                        'processed_at' => now(),
                        'status'         => AdvanceProcess::$ACTIVE,
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
                    $log_data = [
                        'is_processed'   => 1
                    ];
                    LoanProcessLog::where('ref_no', $advance->reference_no)->update($log_data);
                }
            }
            
        } catch (\Throwable $th) {
            report($th);
            dd($th->getMessage());
            return redirect()->back()
                ->with("error", "Something went wrong.");
        }
        DB::commit();
        return redirect()->back()
            ->with("success", "Data added for processing.");

    }

    public function processed_loan_list()
    {
        $salary_block = SalaryBlock::where("sal_process_status", "Unblock")->where("is_finalized", 0)
            ->active()
            ->first();
        request()->merge([
            "month" => request("month", $salary_block->month),
            "year"  => request("year", $salary_block->year),
        ]);

        $departments = Department::select("name", "id")->get();
        $employees   = Employee::select("id", "first_name", "middle_name", "last_name", "code")
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
            })
            /*->whereHas('advances', function ($query) {
                $query->where("interest_amount", ">", 0);
            })*/
            ;

        if (request("export") == "excel") {
            return $this->exportToExcel($processed_data_query, request("month"), request("year"));
        }
        $processed_data = $processed_data_query->orderBy('employee_id')->active()->get();
        //dd($processed_data);
        $advance_types = AdvanceType::query()
            //->advanceType()
            //->active()
            ->pluck("type_name", "id")
            ->toArray();
        return view('loan.processed_loan_list', compact('processed_data', 'departments', "employees", "salary_block", "advance_types"));
    }
}
