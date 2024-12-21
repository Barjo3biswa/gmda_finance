<?php

namespace App\Traits;

use App\Models\Employee;
use App\Models\LeaveType;
use App\Exports\LeaveReportExport;
use App\Exports\OpeningLeaveExport;
use App\Models\EmployeeLeaveOpeningBalance;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Str;

trait LeaveReportsTrait
{

    public function employeeAvailableLeaves(Request $request)
    {
        $limit = request("limit", 100);
        request()->merge([
            "limit" => $limit,
        ]);
        $employees = Employee::query()->active();
        $employees = employee_global_filter($employees);
        $employees = $employees->with(["employeeAvailableLeaves"])->paginate($limit);
        $employees->appends(request()->all());

        $departments  = departments_select_array();
        $leaves_types = LeaveType::select(LeaveType::$minimal_select_fields)->get();
        if ($request->get("export-excel") == '1') {
            //     $count=$employees->count();
            return $this->leaveReportExport($employees,$departments,$leaves_types);

        }

        return view( $this->roleInSnake(). ".reports.leave-reports", compact("employees", "departments", "leaves_types"));
    }

    public function leaveReportExport($employees,$departments,$leaves_types)
    {
        $employees = Employee::query()->active();
        $employees = $employees->with(["employeeAvailableLeaves"])->get();
        $date=date("d-m-Y");
        return Excel::download(new LeaveReportExport($employees,$departments,$leaves_types), 'Leave_Accumulation_Report_'.$date.'.xlsx');
    }
    public function employeeOpeningLeaves()
    {
        $limit = request("limit", 100);
        request()->merge([
            "limit" => $limit,
        ]);
        $employees = Employee::query()->active();
        $employees = employee_global_filter($employees);

        $leaves_types = LeaveType::select(LeaveType::$minimal_select_fields)->get();
        if (request("export-excel") == 'yes') {
            $date=date("d-m-Y");
            return Excel::download(new OpeningLeaveExport($employees->get(),$leaves_types), 'Leave_Opening_balances '.$date.'.xlsx');
        }
        $departments  = departments_select_array();
        $employees = $employees->with(["leave_opening_balances"])->paginate($limit);
        $employees->appends(request()->all());

        return view($this->roleInSnake(). ".reports.opening-leave-reports", compact("employees", "departments", "leaves_types"));
    }
    private function roleInSnake()
    {
        return Str::snake(currentRole($lowercase = true));
    }

}
