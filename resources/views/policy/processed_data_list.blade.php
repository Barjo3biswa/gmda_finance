@extends('layouts.app')
@section('content')

    <div class="card" style="margin-bottom: 100px; margin-top: 50px; box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);">
        <div class="card-header">
            <a class="btn btn-sm btn-pinterest float-right mr-1" href="{{ route('policy.upload-excel') }}">
                {{-- excel icon --}}
                <i class="fa fa-file-excel-o"></i> Upload LIC Policy Excel
            </a>
            <i class="fa fa-filter"></i> Filter
            <a class="btn btn-sm btn-outline-primary float-right mr-1" href="{{ route('policy.process') }}">
                <i class="fa fa-list"></i> Process LIC Data
            </a>
        </div>
        <div class="card-body">

            <form method="get" action="" autocomplete="off">
                <div class="row">
                    {{-- <div class="col-md-3">
                        <div class="form-group">
                            <label for="name">Department</label>
                            <select name="department_id" id="department" class="form-control select2">
                                <option value="">--All--</option>
                                @foreach ($departments as $key => $department)
                                    <option value="{{ $department->id }}"
                                        {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="name">Employee</label>
                            <select name="employee_id" id="categories" class="form-control select2">
                                <option value="">--All--</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->full_name_with_code }}</option>
                                @endforeach
                            </select>


                        </div>
                    </div> --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="name">Month</label>
                            {!! Form::select('month', \App\Helpers\CommonHelper::allMonthArray(), request('month'), ['class' => 'form-control select2', 'placeholder' => '--All--', "required" => true]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="name">Year</label>
                            {!! Form::select('year', \App\Helpers\CommonHelper::getYearList(), request('year'), ['class' => 'form-control select2', 'placeholder' => '--All--', "required" => true]) !!}
                        </div>
                    </div>
                </div>

                <button type="submit" class='btn btn-primary text-white'>Search </button>

            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <i class="fa fa-list-alt"></i> POLICY RECORDS
            <a class="btn btn-sm btn-outline-success float-right mr-1" href="{{ route("policy.processed_data_list", request()->merge(["export" => "excel"])->all())}}">
                <i class="fa fa-file-excel-o"></i> Export
            </a>
        </div>
        <div class="card-body">
            {!! Form::open(['route' => 'policy.processed_data_list_post', 'method' => 'POST']) !!}
            @csrf
            {!! Form::hidden("month", request("month"), []) !!}
            {!! Form::hidden("year", request("year"), []) !!}
            <table class="table table-bordered table-sm" id="employee_table">
                <tbody>
                    <tr>
                        <th>SL.</th>
                        <th>Policy Holder Name(Employee Name)</th>
                        <th class="text-center">Emp. Code</th>
                        <th class="text-center">Policy No</th>
                        <th class="text-right">Premium Amt.</th>
                        <th>Month</th>
                        <th>Year</th>
                        <th>Action</th>
                    </tr>
                </tbody>
                <tbody>
                    @forelse ($processed_data as $key => $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row->employee->full_name ?? "NA" }}</td>
                            <td class="text-center">{{ $row->employee->code ?? "NA" }}</td>
                            <td class="text-center">{{ $row->policy_no }}</td>
                            <td class="text-right">{{ $row->amount }}</td>
                            <td>{{ \App\Helpers\CommonHelper::getMonthName($row->month) }}</td>
                            <td>{{ $row->year }}</td>
                            <td>
                                @if ($row->isProcessingAllowed())
                                    {{-- remove button with call a js function --}}
                                    <a href="{{route("policy.processed_data_list_delete", $row->id)}}" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure to delete this record?')">
                                        <i class="fa fa-trash"></i>
                                    </a>

                                @else
                                    <span class="badge badge-primary">Processed at: {{$row->processed_at->format("Y-m-d H:i a")}}</span>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-danger">No Data Found</td>
                        </tr>
                    @endempty
                    <tfoot>
                        <tr>
                            <th class="text-right" colspan="4">Total</th>
                            <th class="text-right">{{number_format(($processed_data->sum("amount") ?? 0.00), 2)}}</th>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                </tbody>
            </table>
            {!! Form::submit("Finalize & process for the month ".\App\Helpers\CommonHelper::getMonthName(request("month"))."-".request("year"), ['class' => 'btn btn-primary', 'id' => 'process', "onClick" => "return confirm('Are your sure?')", "disabled" => $processed_data->where("process_allowed", 1)->isEmpty()]) !!}
            {!! Form::close() !!}
            {{-- {{$policies->appends(request()->all())->links()}} --}}
        </div>
    </div>
    </div>
@endsection
@section('js')
    <script>
    </script>


@endsection
