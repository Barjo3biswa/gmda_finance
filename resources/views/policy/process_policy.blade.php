@extends('layouts.app')
@section('content')
<div class="container" style="margin-bottom: 100px; margin-top: 50px; box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);">
<div class="card">
    <div class="card-header">
        <i class="fa fa-filter"></i> Filter
        <a class="btn btn-sm btn-outline-primary float-right mr-1" href="{{route('policy.processed_policy_list')}}">
            <i class="fa fa-list"></i> View processed LIC data
        </a>
    </div>
    <div class="card-body">

        <form method="get" action="">

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="name">Department</label>
                        <select name="department_id" id="department" class="form-control select2">
                            <option value="">--All--</option>
                            @foreach ($departments as $key => $department)
                                <option value="{{$department->id}}" {{request("department_id") == $department->id ? "selected" : ""}}>{{$department->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="name">Employee</label>
                        <select name="employee_id" id="categories" class="form-control select2">
                            <option value="">--All--</option>
                            @foreach ($emp as $employee)
                                <option value="{{$employee->user_id}}">{{$employee->first_name}} {{$employee->last_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="name">Month</label>
                        <select name="month" class="form-control" required>
                            <option value="" disabled selected>--SELECT--</option>
                            @foreach(\App\Helpers\CommonHelper::allMonthArray($salarystatus->salary_month) as $month => $monthName)
                                <option value="{{ $month }}" {{ $month == $salarystatus->salary_month ? 'selected' : '' }}>
                                    {{ $monthName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="name">Year</label>
                        <input type="text" name="year" value="{{ $salarystatus->salary_year }}" class="form-control" placeholder="year" required readonly>
                    </div>
                </div>
            </div>

        <button type="submit" class='btn btn-primary text-white btn-sm'>Search </button>

        </form>
    </div>
</div>
        <div class="card">
            <div class="card-header">
                <i class="fa fa-list-alt"></i> POLICY RECORDS
                <a class="btn btn-sm btn-outline-success float-right mr-1"  href="{{route('policy.create')}}">
                    Add New Policy
                </a>
            </div>
            <div class="card-body">


    @if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
    @endif
    <form action="{{ route('policy.process_policy_data') }}" method="POST">
    @csrf
    <input type="hidden" name="salary_block_id" value="{{ $salarystatus->id }}">
    <div class="table-responsive">
        <table class="table table-bordered table-sm" id="employee_table">
            <tbody>
                <tr>
                    <th>
                        <input type="checkbox" id="checkAll" />
                    </th>
                    <th>SL.</th>
                    <th>Policy Holder Name(Employee Name)</th>
                    <th>Policy No</th>
                    <th>Employee Code</th>
                    <th class="text-right">Monthly Premium</th>
                    <th width="100px" class="d-none d-lg-table-cell d-md-table-cell">Start Date</th>
                    <th class="d-none d-lg-table-cell d-md-table-cell">Last Date of deduction</th>
                    <th class="d-none d-lg-table-cell d-md-table-cell">Last Month/year</th>

                </tr> <!-- Modal -->
            </tbody>
            <tbody>
                @forelse ($policies as $key => $policy)
                <tr>
                    <td>
                        @if($policy->licprocessdata)
                            <!-- @if($salarystatus->isLicProcessed())
                                <strong class="text-danger">Processed</strong>
                            @else
                                <strong class="text-info">Added for processing</strong>
                            @endif -->
                        @else
                            <input name="datas[{{$key}}][policy_id]" type="checkbox" value="{{$policy->id}}" onclick="chkBox(this)" id="checkbox-{{$policy->id}}"/>
                        @endif
                    </td>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $policy->employees->first_name}} {{ $policy->employees->last_name ?? "NA"}}</td>
                    <td>{{ $policy->policy_no}}</td>
                    <td>{{$policy->employee_code}}</td>
                    <td class="text-right"><input class="form-control text-right enableme" type="number" name="datas[{{$key}}][monthly_premium]" value={{$policy->monthly_premium }} disabled="disabled"></td>
                    <td class="d-none d-lg-table-cell d-md-table-cell">{{ $policy->start_date}}</td>
                    <td class="d-none d-lg-table-cell d-md-table-cell">{{$policy->closing_date }}</td>
                    <td class="d-none d-lg-table-cell d-md-table-cell">{{$policy->closing_month}}/{{$policy->closing_year}}</td>

                </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-danger">No Data Found</td>
                    </tr>
                @endempty
                <tfoot>
                    <tr>
                        <th class="text-right" colspan="5">Total</th>
                        <th class="text-right">{{number_format(($policies->sum("monthly_premium") ?? 0.00), 2)}}</th>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </tbody>
        </table>
        {{$policies->links()}}
    </div>
    <input type="submit" value="Add for Processing" class="btn btn-primary btn-sm" id="process">
    <a class="btn btn-outline-primary btn-sm" href="{{route('policy.processed_policy_list')}}">
        <i class="fa fa-list"></i> View processed LIC data
    </a>
    </form>
    {{-- {{$policies->appends(request()->all())->links()}} --}}
            </div>
        </div>
</div>
    </div>
@endsection
@section('js')
<script>
    disableProcessButton = function(){
        if($('#employee_table input:checkbox:checked').not("#checkAll").length > 0){
            console.log("false");
            $('#process').prop('disabled', false);
        }else{
            console.log("true");
            $('#process').prop('disabled', true);
        }
    }
    disableProcessButton();
    $(document).ready(function(){
        $("#checkAll").click(function(){
            $('#employee_table input:checkbox').not(this).prop('checked', this.checked);
            disableProcessButton();
        });
        $("#process").click(function(){
            if($("#employee_table input:checkbox:checked").length == 0){
                alert('Please select at least one policy');
                return false;
            }
        });
        // toggle selectAll checkbox
        $('#employee_table input:checkbox').click(function(){
            if($('#employee_table input:checkbox:checked').length == $('#employee_table input:checkbox').length){
                $('#checkAll').prop('checked', true);
               //enable all input number of the table
               
                $('.enableme').prop('disabled', false);

            }else{
                $('#checkAll').prop('checked', false);
                // $('.enableme').prop('disabled', true);

            }
            disableProcessButton();
        });
    });

//     $("#checkall").click(function (){
//     if ($("#checkall").is(':checked')){
//        $(".checkboxes").each(function (){
//           $(this).prop("checked", true);
//           });
//        }else{
//           $(".checkboxes").each(function (){
//                $(this).prop("checked", false);
//           });
//        }
// });

    function chkBox(obj) {
        console.log(obj);
        $(obj).parents("tr").find("input[type='number']").prop('disabled', !$(obj).is(":checked"));
        // $(obj).parents("tr").find("input[type='hidden']").prop('disabled', !$(obj).is(":checked"));
    }

</script>

@endsection

