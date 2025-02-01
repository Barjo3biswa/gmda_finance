<?php

namespace App\Http\Controllers;

use App\Models\AuthMaster;
use App\Models\salaryHead;
use App\Models\salaryTrans;
use App\Models\Department;
use App\Models\DepartmentSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function salaryHeadReport()
    {
        $SalaryHead = salaryHead::get();
        $departments = Department::all();
        $sections = DepartmentSection::all();
        $appointmenttypes = AuthMaster::where('master_type', 'Appointment Type')->get();


        $data = [];

        if(request()->salary_head ||
            request()->department ||
            request()->section ||
            request()->appointment_type ||
            request()->pf_category ||
            request()->from_month ||
            request()->to_month){
            $query = salaryTrans::query()
                ->with('employee')
                ->whereHas('employee', function($q) {
                    if(request()->department) {
                        $q->where('department_id', request()->department);
                    }
                    if(request()->section) {
                        $q->where('section_id', request()->section);
                    }
                    if(request()->appointment_type) {
                        $q->where('appointment_type', request()->appointment_type);
                    }
                    if(request()->pf_category) {
                        $q->where('pf_category', request()->pf_category);
                    }
                })
                ->where('sal_head_id', request()->salary_head)
                ->where('year', request()->year);

            if(request()->from_month && request()->to_month) {
                $query->whereBetween('month', [request()->from_month, request()->to_month]);
            } elseif(request()->from_month) {
                $query->where('month', '>=', request()->from_month);
            } elseif(request()->to_month) {
                $query->where('month', '<=', request()->to_month);
            } else {

                if(request()->month){
                    $query->where('month', request()->month);
                }

            }

            $data = $query->get();
        }
        return view('reports.salary-head-report', compact(
            'SalaryHead',
            'departments',
            'sections',
            'appointmenttypes',
            'data'));
    }

    public function summaryHeadReport(){
        $departments = Department::all();
        $sections = DepartmentSection::all();
        $appointmenttypes = AuthMaster::where('master_type', 'Appointment Type')->get();

        $salaryHeadTypes = salaryHead::select('pay_head')->distinct()->get();

        $data = [];
        $groupedData = [];

        if(request()->year ||
           request()->department ||
           request()->section ||
           request()->appointment_type ||
           request()->pf_category ||
           request()->from_month ||
           request()->to_month){

            $query = salaryTrans::query()
                ->with('employee')
                ->whereHas('employee', function($q) {
                    if(request()->department) {
                        $q->where('department_id', request()->department);
                    }
                    if(request()->section) {
                        $q->where('section_id', request()->section);
                    }
                    if(request()->appointment_type) {
                        $q->where('appointment_type', request()->appointment_type);
                    }
                    if(request()->pf_category) {
                        $q->where('pf_category', request()->pf_category);
                    }
                })
                ->whereHas('salaryHead', function($q) {
                    if(request()->salary_head_type) {
                        $q->where('pay_head', request()->salary_head_type);
                    }
                })
                ->where('year', request()->year);

            if(request()->from_month && request()->to_month) {
                $query->whereBetween('month', [request()->from_month, request()->to_month]);
            } elseif(request()->from_month) {
                $query->where('month', '>=', request()->from_month);
            } elseif(request()->to_month) {
                $query->where('month', '<=', request()->to_month);
            }

            if(!request()->salary_head_type) {
                $groupedData = $query
                    ->with(['salaryHead' => function($q) {
                        $q->select('id', 'name', 'pay_head');
                    }])
                    ->select('sal_head_id', DB::raw('SUM(amount) as total_amount'))
                    ->groupBy('sal_head_id')
                    ->get()
                    ->groupBy(function($item) {
                        return $item->salaryHead->pay_head;
                    });
            } else {
                $data = $query
                    ->with('salaryHead')
                    ->select('sal_head_id', DB::raw('SUM(amount) as total_amount'))
                    ->groupBy('sal_head_id')
                    ->get();
            }
        }

        return view('reports.summary-head-report', compact(
            'data',
            'groupedData',
            'departments',
            'sections',
            'appointmenttypes',
            'salaryHeadTypes'
        ));
    }

    public function glsiReport()
    {
        $departments = Department::all();
        $sections = DepartmentSection::all();
        $appointmenttypes = AuthMaster::where('master_type', 'Appointment Type')->get();

        $salHead_id = salaryHead::where('code', 'GLSI')->first();

        if(!$salHead_id){
            return redirect()->back()->with('error', 'GLSI salary head not found');
        }

        $data = [];
        if(request()->report_type ||
            request()->department ||
            request()->section ||
            request()->appointment_type ||
            request()->pf_category ||
            request()->from_month ||
            request()->to_month){
            $query = salaryTrans::query()
                ->with('employee')
                ->where('sal_head_id', $salHead_id->id)
                ->whereHas('employee', function($q) {
                    if(request()->department) {
                        $q->where('department_id', request()->department);
                    }
                    if(request()->section) {
                        $q->where('section_id', request()->section);
                    }
                    if(request()->appointment_type) {
                        $q->where('appointment_type', request()->appointment_type);
                    }
                    if(request()->pf_category) {
                        $q->where('pf_category', request()->pf_category);
                    }
                })
                ->where('year', request()->year);

            if(request()->from_month && request()->to_month) {
                $query->whereBetween('month', [request()->from_month, request()->to_month]);
            } elseif(request()->from_month) {
                $query->where('month', '>=', request()->from_month);
            } elseif(request()->to_month) {
                $query->where('month', '<=', request()->to_month);
            } else {
                if(request()->month){
                    $query->where('month', request()->month);
                }

            }

            if(request()->report_type == 'monthly'){
                $data = $query->get();
            } else {
                $data = $query->sum('amount');
            }
        }
        return view('reports.glsi-report', compact(
            'data',
            'departments',
            'sections',
            'appointmenttypes'
        ));
    }

    public function npsReport()
    {
        $departments = Department::all();
        $sections = DepartmentSection::all();
        $appointmenttypes = AuthMaster::where('master_type', 'Appointment Type')->get();

        $data = [];

        if(request()->report_type ||
            request()->department ||
            request()->section ||
            request()->appointment_type ||
            request()->pf_category ||
            request()->from_month ||
            request()->to_month){
            $query = salaryTrans::query()
                ->with('employee')
                ->where('sal_head_id', 13)
                ->whereHas('employee', function($q) {
                    if(request()->department) {
                        $q->where('department_id', request()->department);
                    }
                    if(request()->section) {
                        $q->where('section_id', request()->section);
                    }
                    if(request()->appointment_type) {
                        $q->where('appointment_type', request()->appointment_type);
                    }
                    if(request()->pf_category) {
                        $q->where('pf_category', request()->pf_category);
                    }
                })
                ->where('year', request()->year);

            if(request()->from_month && request()->to_month) {
                $query->whereBetween('month', [request()->from_month, request()->to_month]);
            } elseif(request()->from_month) {
                $query->where('month', '>=', request()->from_month);
            } elseif(request()->to_month) {
                $query->where('month', '<=', request()->to_month);
            } else {

                if(request()->month){
                    $query->where('month', request()->month);
                }

            }


            if(request()->report_type == 'monthly'){
                $data = $query->get();
            } else {
                $data = $query->sum('amount');
            }
        }
        return view('reports.nps-report', compact(
            'data',
            'departments',
            'sections',
            'appointmenttypes'
        ));
    }

    public function sssReport(){
        $departments = Department::all();
        $sections = DepartmentSection::all();
        $appointmenttypes = AuthMaster::where('master_type', 'Appointment Type')->get();

        $data = [];

        $salHead_id = salaryHead::where('code', 'SSS')->first();

        if(!$salHead_id){
            return redirect()->back()->with('error', 'SSS salary head not found');
        }

        if(request()->report_type ||
            request()->department ||
            request()->section ||
            request()->appointment_type ||
            request()->pf_category ||
            request()->from_month ||
            request()->to_month){
            $query = salaryTrans::query()
                ->with('employee')
                ->where('sal_head_id', $salHead_id->id)
                ->whereHas('employee', function($q) {
                    if(request()->department) {
                        $q->where('department_id', request()->department);
                    }
                    if(request()->section) {
                        $q->where('section_id', request()->section);
                    }
                    if(request()->appointment_type) {
                        $q->where('appointment_type', request()->appointment_type);
                    }
                    if(request()->pf_category) {
                        $q->where('pf_category', request()->pf_category);
                    }
                })
                ->where('year', request()->year);

            if(request()->from_month && request()->to_month) {
                $query->whereBetween('month', [request()->from_month, request()->to_month]);
            } elseif(request()->from_month) {
                $query->where('month', '>=', request()->from_month);
            } elseif(request()->to_month) {
                $query->where('month', '<=', request()->to_month);
            } else {

                if(request()->month){
                    $query->where('month', request()->month);
                }

            }


            if(request()->report_type == 'monthly'){
                $data = $query->get();
            } else {
                $data = $query->sum('amount');
            }
        }
        return view('reports.sss-report', compact(
            'data',
            'departments',
            'sections',
            'appointmenttypes'
        ));
    }

    public function licReport(){
        $departments = Department::all();
        $sections = DepartmentSection::all();
        $appointmenttypes = AuthMaster::where('master_type', 'Appointment Type')->get();

        $data = [];

        $salHead_id = salaryHead::where('code', 'LIC')->first();

        if(!$salHead_id){
            return redirect()->back()->with('error', 'LIC salary head not found');
        }

        if(request()->report_type ||
            request()->department ||
            request()->section ||
            request()->appointment_type ||
            request()->pf_category ||
            request()->from_month ||
            request()->to_month){
            $query = salaryTrans::query()
                ->with('employee')
                ->where('sal_head_id', $salHead_id->id)
                ->whereHas('employee', function($q) {
                    if(request()->department) {
                        $q->where('department_id', request()->department);
                    }
                    if(request()->section) {
                        $q->where('section_id', request()->section);
                    }
                    if(request()->appointment_type) {
                        $q->where('appointment_type', request()->appointment_type);
                    }
                    if(request()->pf_category) {
                        $q->where('pf_category', request()->pf_category);
                    }
                })
                ->where('year', request()->year);

            if(request()->from_month && request()->to_month) {
                $query->whereBetween('month', [request()->from_month, request()->to_month]);
            } elseif(request()->from_month) {
                $query->where('month', '>=', request()->from_month);
            } elseif(request()->to_month) {
                $query->where('month', '<=', request()->to_month);
            } else {
                if(request()->month){
                    $query->where('month', request()->month);
                }
            }


            if(request()->report_type == 'monthly'){
                $data = $query->get();
            } else {
                $data = $query->sum('amount');
            }
        }
        return view('reports.lic-report', compact(
            'data',
            'departments',
            'sections',
            'appointmenttypes'
        ));
    }
}
