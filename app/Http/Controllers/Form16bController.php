<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Form16b;
use App\Models\salaryMaster;
use App\Models\SalarySummmary;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class Form16bController extends Controller
{
    public function index()
    {
        $employees = User::select('id', 'name')->where('salary_flag', 'open')->get();

        return view('form16b.index', compact('employees'));
    }

    public function create(Request $request)
    {
        // dd($request->all());
        $employee = User::where('id', $request->employee_id)->first();
        $financialYear = $request->financial_year;

        $salarySummary = SalarySummmary::where('emp_id', $employee->id)->where('financial_year', $financialYear)->count();

        // dd($salarySummary);

        if ($salarySummary != 12) {
            return redirect()->route('form16.index')->with('error', 'Complete Salary Summaries does not exists for this employee and financial year.');
        }

        $form16b = Form16b::where('emp_code', $employee->emp_code)->where('financial_year', $financialYear)->first();

        if ($form16b) {
            return redirect()->route('form16.index')->with('error', 'Form 16b already generated for this employee and financial year.');
        }

        // dd($employee->emp_code, $financialYear);

        $totalGrossSalary = salaryMaster::where('emp_code', $employee->emp_code)
            ->where('financial_year', $financialYear)
            ->sum('gross') ?? 0;


        // dd($totalGrossSalary);

        $totalINC_HRA = 0;
        if (Schema::hasColumn('salary_summmaries', 'INC_HRA')) {
            $totalINC_HRA = SalarySummmary::select('emp_id', 'emp_code', 'financial_year', 'INC_HRA')
                ->where('emp_code', $employee->emp_code)
                ->where('financial_year', $financialYear)
                ->sum('INC_HRA');
        }

        $totalINC_CONVA = 0;
        if (Schema::hasColumn('salary_summmaries', 'INC_CONVA')) {
            $totalINC_CONVA = SalarySummmary::select('emp_id', 'emp_code', 'financial_year', 'INC_CONVA')
                ->where('emp_code', $employee->emp_code)
                ->where('financial_year', $financialYear)
                ->sum('INC_CONVA');
        }

        $totalINC_CRGA = 0;
        if (Schema::hasColumn('salary_summmaries', 'INC_CRGA')) {
            $totalINC_CRGA = SalarySummmary::select('emp_id', 'emp_code', 'financial_year', 'INC_CRGA')
                ->where('emp_code', $employee->emp_code)
                ->where('financial_year', $financialYear)
                ->sum('INC_CRGA');
        }

        $totalINC_OTHERALLW = 0;
        if (Schema::hasColumn('salary_summmaries', 'INC_OTHERALLW')) {
            $totalINC_OTHERALLW = SalarySummmary::select('emp_id', 'emp_code', 'financial_year', 'INC_OTHERALLW')
                ->where('emp_code', $employee->emp_code)
                ->where('financial_year', $financialYear)
                ->sum('INC_OTHERALLW');
        }

        $totalDED_PTAX = 0;
        if (Schema::hasColumn('salary_summmaries', 'DED_PTAX')) {
            $totalDED_PTAX = SalarySummmary::select('emp_id', 'emp_code', 'financial_year', 'DED_PTAX')
                ->where('emp_code', $employee->emp_code)
                ->where('financial_year', $financialYear)
                ->sum('DED_PTAX');
        }

        $totalDED_GPF = 0;
        if (Schema::hasColumn('salary_summmaries', 'DED_GPF')) {
            $totalDED_GPF = SalarySummmary::select('emp_id', 'emp_code', 'financial_year', 'DED_GPF')
                ->where('emp_code', $employee->emp_code)
                ->where('financial_year', $financialYear)
                ->sum('DED_GPF');
        }

        $totalDED_EPF = 0;
        if (Schema::hasColumn('salary_summmaries', 'DED_EPF')) {
            $totalDED_EPF = SalarySummmary::select('emp_id', 'emp_code', 'financial_year', 'DED_EPF')
                ->where('emp_code', $employee->emp_code)
                ->where('financial_year', $financialYear)
                ->sum('DED_EPF');
        }

        $totalDED_NPS = 0;
        if (Schema::hasColumn('salary_summmaries', 'DED_NPS')) {
            $totalDED_NPS = SalarySummmary::select('emp_id', 'emp_code', 'financial_year', 'DED_NPS')
                ->where('emp_code', $employee->emp_code)
                ->where('financial_year', $financialYear)
                ->sum('DED_NPS');
        }

        $totalDED_CPF = 0;
        if (Schema::hasColumn('salary_summmaries', 'DED_CPF')) {
            $totalDED_CPF = SalarySummmary::select('emp_id', 'emp_code', 'financial_year', 'DED_CPF')
                ->where('emp_code', $employee->emp_code)
                ->where('financial_year', $financialYear)
                ->sum('DED_CPF');
        }

        $totalGPF_EPF_CPF = $totalDED_GPF + $totalDED_NPS;

        $totalDED_GSLI = 0;
        if (Schema::hasColumn('salary_summmaries', 'DED_GSLI')) {
            $totalDED_GSLI = SalarySummmary::select('emp_id', 'emp_code', 'financial_year', 'DED_GSLI')
                ->where('emp_code', $employee->emp_code)
                ->where('financial_year', $financialYear)
                ->sum('DED_GSLI');
        }

        // Note* there is a seperate field G.I.S but in old code in gis filed gsli is used

        return view('form16b.create', compact(
            'employee',
            'financialYear',
            'totalGrossSalary',
            'totalINC_HRA',
            'totalINC_CONVA',
            'totalINC_CRGA',
            'totalINC_OTHERALLW',
            'totalDED_PTAX',
            'totalGPF_EPF_CPF',
            'totalDED_GSLI'
        ));
    }

    public function store(Request $request)
    {
        dd($request->all());
    }
}
