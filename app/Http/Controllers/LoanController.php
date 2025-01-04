<?php

namespace App\Http\Controllers;
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
use App\Models\LoanMaster;
use App\Models\LoanMasterDetail;
use App\Models\LoanProcessLog;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Fetch employees, departments, and designations
        $emp = Employee::select('*')->orderBy('first_name', 'asc')->get();
        $departments = Department::select('id', 'name')->get();
        $designations = AuthDesignation::get();

        // Initialize the base query for advances
        $query = Advance::with('employee', 'advanceType')
            ->whereHas('advanceType', function($q) {
                $q->where('type', 'loan'); // Filter by loan type
            })
            ->orderBy('created_at', 'desc');

        // Apply filtering for advance type if selected
        if ($request->has('advance_type_id') && $request->advance_type_id != '') {
            $query->where('advance_id', $request->advance_type_id);
        }

        // Apply filtering for department if selected
        if ($request->has('department_id') && $request->department_id != '') {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        // Apply filtering for employee if selected
        if ($request->has('employee_id') && $request->employee_id != '') {
            $query->where('user_id', $request->employee_id);
        }

        // Fetch filtered advance requests
        $advanceRequests = $query->get()->map(function ($advance) {
            // Check if the advance has a reference number in LoanMaster
            $advance->has_loan_master = LoanMaster::where('reference_no', $advance->reference_no)->exists();
            return $advance;
        });

        // Fetch available advance types
        $advanceTypes = DB::table('advance_types')
            ->where('type', 'loan')
            ->get();
        if ($advanceTypes->isEmpty()) {
            $advanceTypes = [];
        }

        return view("loan.index", compact('emp', 'departments', 'designations', 'advanceRequests', 'advanceTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::select('*')->orderBy('first_name')->get();
        $designations = AuthDesignation::get();
        $advanceGroups = AdvanceGroup::all();
        $advanceTypes = DB::select("SELECT * FROM advance_types WHERE type = ?", ['loan']);
        //dd($advanceTypes);
        $salaryheads = SalaryHead::all();
        $salaryblocks = salaryBlock::all();
        return view("loan.create", compact('employees', 'advanceTypes', 'salaryheads', 'salaryblocks', 'advanceGroups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $tableData = json_decode($request->input('table_data'), true);
        // dd($tableData);

        // Generate reference number
        $currentDate = now();
        $year = $currentDate->format('y');
        $month = str_pad($currentDate->format('m'), 2, '0', STR_PAD_LEFT);

        // Get the last reference number from Advance model
        $lastAdvanceRefNo = Advance::whereNotNull('reference_no')
            ->where('reference_no', 'LIKE', "LOAN/{$year}/{$month}/%")
            ->orderBy('id', 'desc')
            ->first();

        // Get the last reference number from Loan model
        $lastLoanRefNo = LoanMaster::whereNotNull('reference_no')
            ->where('reference_no', 'LIKE', "LOAN/{$year}/{$month}/%")
            ->orderBy('id', 'desc')
            ->first();

        // Function to extract the numeric part of the reference number
        function extractNumericPart($referenceNo)
        {
            if (preg_match('/\d+$/', $referenceNo, $matches)) {
                return (int)$matches[0];  // Return the numeric part as an integer
            }
            return 0;  // Return 0 if no numeric part is found
        }

        // Initialize the sequence variable
        $sequence = '0001';

        // Compare the numeric parts of both Advance and Loan reference numbers
        if ($lastAdvanceRefNo && $lastLoanRefNo) {
            // Extract the numeric parts
            $advanceNum = extractNumericPart($lastAdvanceRefNo->reference_no);
            $loanNum = extractNumericPart($lastLoanRefNo->reference_no);

            // Get the largest numeric part
            $largestSequence = max($advanceNum, $loanNum);
            $sequence = str_pad($largestSequence + 1, 4, '0', STR_PAD_LEFT);
        } elseif ($lastAdvanceRefNo) {
            // If only Advance record exists
            $advanceNum = extractNumericPart($lastAdvanceRefNo->reference_no);
            $sequence = str_pad($advanceNum + 1, 4, '0', STR_PAD_LEFT);
        } elseif ($lastLoanRefNo) {
            // If only Loan record exists
            $loanNum = extractNumericPart($lastLoanRefNo->reference_no);
            $sequence = str_pad($loanNum + 1, 4, '0', STR_PAD_LEFT);
        }

        // Now generate the reference number
        $referenceNo = "LOAN/{$year}/{$month}/{$sequence}";

        // dd($referenceNo);

        $advance = new Advance();
        $advance->reference_no = $referenceNo;
        $advance->user_id = $request->employee_id;
        // Get employee details
        $employee = Employee::where('user_id', $request->employee_id)->first();
        $advance->emp_code = $employee ? $employee->code : '';
        $advance->advance_id = $request->loan_head_id;
        $advance->loan_head_id = $request->loan_head_id;
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

        $advanceId = $advance->id;

        $employee = Employee::where('user_id', $request->employee_id)->first();
        $emp_code = $employee->code;
        $emp_dept = $employee->department_id;
        $emp_desig = $employee->designation_id;
        //dd($emp_code, $emp_dept, $emp_desig);

        $loanMasterData = [
            'advances_id' => $advanceId,
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
            'applied_for' => 'New Loan'
        ];

        // dd($loanMasterData);
        
        $salaryBlock = SalaryBlock::find($request->sal_block_id);
        //dd($loanMasterData);

        $salaryBlock = salaryBlock::find($request->sal_block_id);

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

        $loanid=LoanMaster::where('reference_no', $referenceNo)->value('id');

        $tableData = json_decode($request->input('table_data'), true);

        foreach ($tableData as $row) {
            LoanMasterDetail::create([
                'emp_id' => $request->employee_id,
                'emp_code' => $emp_code,
                'loan_type_id'=> $request->loan_head_id,
                'loan_id' => $loanid,
                'payment_no'=>$row['sl'],
                'payment_date'=>'01-'.$request->wef_month.'-'.$request->wef_year,
                'begining_balance'=>$row['balance'],
                'payment' => $row['emi'],
                'interest' => $row['int'],
                'principal' => $row['principal'],
                'ending_balance' => $row['balance']
            ]);
        }

        return redirect()->route('loan.index')->with('success', 'New loan added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $refno=advance::where('id', $id)->value('reference_no');
        $loanid=LoanMaster::where('advances_id', $id)->value('id');
        // dd($loanid);
        $loan = LoanMaster::with('employee', 'advanceType')->where('id', $loanid)->first();
        $loanmasterdetail = LoanMasterDetail::where('loan_id', $loanid)->get();
        // dd($loanmasterdetail);
        // dd($loan);
        $employees = Employee::all();
        $advanceTypes = DB::select('SELECT * FROM advance_types WHERE type = ?', ['loan']);;
        $salaryheads = SalaryHead::all();
        // dd($loan)
        return view('loan.show', compact('loan', 'loanmasterdetail', 'employees', 'advanceTypes', 'salaryheads'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Fetch the loan data
        //$loan = LoanMaster::where('advances_id', $id)->first(); // findOrFail($id);
        $loan = Advance::where('id', $id)->first();
        // dd($loan);
        // Fetch related data (e.g., employees, loan types, salary heads)
        $employees = Employee::all();
        $advanceTypes = DB::table('advance_types')
                  ->where('type', 'loan')
                  ->get();
        $salaryheads = salaryHead::all();

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
        // $advance->emp_code = $employee ? $employee->code : '';
        // $advance->advance_id = $request->loan_head_id;
        // $advance->loan_head_id = $request->sal_block_id;
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
        
        $advance = Advance::where('id', $request->advances_id)->first();
        $advance->update();

        //$advance->update() - where('reference_no', $request->reference_no);

        $employee = Employee::where('user_id', $request->employee_id)->first();
        $emp_code = $employee->code;
        $emp_dept = $employee->department_id;
        $emp_desig = $employee->designation_id;
        //dd($emp_code, $emp_dept, $emp_desig);

        $loanMasterData = [
            'advances_id' => $request->advances_id,
            'reference_no' => $request->reference_no,
            'user_id' => $request->employee_id,
            'emp_code' => $emp_code ?? null,
            'fld_deptid' => $emp_dept,
            'fld_desigid' => $emp_desig,
            'loan_type_id' => $request->loan_type_id,
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
            // 'interest_installment' => 0,
            'interest_emi' => $request->interest_installment,
            'adj_interest_emi' => $request->adj_interest_emi,
            'adj_interest_emi_in' => $request->adj_interest_emi_in,
            'sal_block_id' => $request->sal_block_id,
            'from_yyyy' => $request->wef_year,
            'from_mm' => $request->wef_month
        ];

        //dd($loanMasterData);

        $salaryBlock = salaryBlock::find($request->sal_block_id);

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
            ['advances_id' => $request->advances_id],
            $loanMasterData
        );

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
        $employees = Employee::select('*')->orderBy('first_name')->get();
        $designations = AuthDesignation::get();
        $advanceGroups = AdvanceGroup::all();
        $advanceTypes = AdvanceType::all();
        $salaryheads = salaryHead::all();
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

        // Get the last reference number from Advance model
        $lastAdvanceRefNo = Advance::whereNotNull('reference_no')
            ->where('reference_no', 'LIKE', "LOAN/{$year}/{$month}/%")
            ->orderBy('id', 'desc')
            ->first();

        // Get the last reference number from Loan model
        $lastLoanRefNo = LoanMaster::whereNotNull('reference_no')
            ->where('reference_no', 'LIKE', "LOAN/{$year}/{$month}/%")
            ->orderBy('id', 'desc')
            ->first();

        // Function to extract the numeric part of the reference number
        function extractNumericPart($referenceNo)
        {
            if (preg_match('/\d+$/', $referenceNo, $matches)) {
                return (int)$matches[0];  // Return the numeric part as an integer
            }
            return 0;  // Return 0 if no numeric part is found
        }

        // Initialize the sequence variable
        $sequence = '0001';

        // Compare the numeric parts of both Advance and Loan reference numbers
        if ($lastAdvanceRefNo && $lastLoanRefNo) {
            // Extract the numeric parts
            $advanceNum = extractNumericPart($lastAdvanceRefNo->reference_no);
            $loanNum = extractNumericPart($lastLoanRefNo->reference_no);

            // Get the largest numeric part
            $largestSequence = max($advanceNum, $loanNum);
            $sequence = str_pad($largestSequence + 1, 4, '0', STR_PAD_LEFT);
        } elseif ($lastAdvanceRefNo) {
            // If only Advance record exists
            $advanceNum = extractNumericPart($lastAdvanceRefNo->reference_no);
            $sequence = str_pad($advanceNum + 1, 4, '0', STR_PAD_LEFT);
        } elseif ($lastLoanRefNo) {
            // If only Loan record exists
            $loanNum = extractNumericPart($lastLoanRefNo->reference_no);
            $sequence = str_pad($loanNum + 1, 4, '0', STR_PAD_LEFT);
        }

        // Now generate the reference number
        $referenceNo = "LOAN/{$year}/{$month}/{$sequence}";

        dd($referenceNo);

        $advance = new Advance();
        $advance->reference_no = $referenceNo;
        $advance->user_id = $request->employee_id;
        // Get employee details
        $employee = Employee::where('user_id', $request->employee_id)->first();
        $advance->emp_code = $employee ? $employee->code : '';
        $advance->advance_id = $request->loan_head_id;
        $advance->loan_head_id = $request->sal_block_id;
        $advance->principal_amount = $request->principal_amount;
        //$advance->outstanding_principal = $request->outstanding_principal;
        $advance->monthly_installment = $request->monthly_emi;
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

        $advanceId = $advance->id;


        $employee = Employee::where('user_id', $request->employee_id)->first();
        $emp_code = $employee->code;
        $emp_dept = $employee->department_id;
        $emp_desig = $employee->designation_id;
        //dd($emp_code, $emp_dept, $emp_desig);

        $loanMasterData = [
            'advances_id' => $advanceId,
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

        $salaryBlock = salaryBlock::find($request->sal_block_id);

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
        $departments = Department::select('id', 'name')->get();
        $salarystatus = salaryBlock::where('sal_process_status', 'Unblock')->where("is_finalized", 0)->first();

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
            ], 'employee', 'advanceType', 'salhead')->whereHas('advanceType', function($q) {
                $q->where('type', 'loan');
            })
            ->orderBy('user_id', 'desc')
            ->paginate(100);

        /*$query = Advance::with('employee', 'advanceType');
        $advances = $query->orderBy('created_at', 'desc')->paginate(10);*/

        $advanceTypes = AdvanceType::all();
        dd($advances);

        return view('loan.process', compact('salarystatus', 'emp', 'departments', 'advances', "advanceTypes"));
    }

    public function process_loan_data(Request $request)
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


        $salary_block = salaryBlock::where("id", $request->salary_block_id)
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
            foreach ($request->allrows as $ldata) {
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
                            'type'           => "loan",
                            'ip_address'     => request()->ip(),
                        ];
                        LoanProcessLog::create($log_data);
                    }
            //  dd($loans);
            //log---

            $advance_ids = [];
            $amounts = [];

            //dd($request->datas);
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
                if ($advance->recovered_amount >= $advance->principal_amount) {
                    $advance->status = 0;
                    //$advance->save();
                } else {
                    $advance_data = ['recovered_amount' => $advance->monthly_emi,];

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
                        'processed_at' => now(),
                        'processed_by_id' => auth()->user()->id,
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
                    $log_data = [
                        'is_processed' => 1
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
        $salary_block = salaryBlock::where("sal_process_status", "Unblock")->where("is_finalized", 0)
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
            })
            ->whereHas('advanceType', function($q) {
                $q->where('type', 'loan');
            })
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


    public function close(string $id)
    {
        $refno=advance::where('id', $id)->value('reference_no');
        $loan = LoanMaster::where('reference_no', $refno)->first();
        // Fetch related data (e.g., employees, loan types, salary heads)
        $employees = Employee::all();
        $advanceTypes = AdvanceType::all();
        $salaryheads = SalaryHead::all();

        // Return the edit view with the current loan data
        return view('loan.close', compact('loan', 'employees', 'advanceTypes', 'salaryheads'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function closeLoan(Request $request, string $id)
    {
        // dd($referenceNo);
        
        $advance = new Advance();
        $advance->close_advance = $request->close_advance;
        $advance->closed_from_month = $request->closed_from_month;
        $advance->closed_from_year = $request->closed_from_year;
        $advance->closed_to_month = $request->closed_to_month;
        $advance->closed_to_year = $request->closed_to_year;
        
        $advance = Advance::where('reference_no', $request->reference_no)->first();
        $advance->update();

        $loanMasterData = [
            'close_advance' => $request->close_advance,
            'closed_from_month' => $request->closed_from_month,
            'closed_from_year' => $request->closed_from_year,
            'closed_to_month' => $request->closed_to_month,
            'closed_to_year' => $request->closed_to_year,
            'updated_by' => Auth::user()->id
        ];

        // dd($loanMasterData);

        //dd($loanMasterData);
        LoanMaster::updateOrCreate(
            ['reference_no' => $request->reference_no],
            $loanMasterData
        );

        return redirect()->route('advance.index')->with('success', 'Advance updated successfully');
    }
}
