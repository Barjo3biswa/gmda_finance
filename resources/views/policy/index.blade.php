@extends('layouts.app')
@section('content')
<div class="container" style="margin-bottom: 100px; margin-top: 100px;">
<div class="card">
    <div class="card-header">
        <i class="fa fa-filter"></i> Filter
        <a class="btn btn-sm btn-outline-primary float-right mr-1" href="{{route('policy.processpolicy')}}">
            <i class="fa fa-list"></i> Process LIC Data
        </a>
    </div>
    <div class="card-body">

        <form method="get" action="">

            <div class="row">
                <div class="col-md-4">
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
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name">Employee</label>
                        <select name="employee_id" id="categories" class="form-control select2">
                            <option value="">--All--</option>
                            @foreach ($emp as $employee)
                                <option value="{{$employee->id}}">{{$employee->first_name}} {{$employee->last_name}}</option>
                            @endforeach
                        </select>


                    </div>
                </div>
            </div>

        <button type="submit" class='btn btn-primary text-white btn-sm'>Search </button>

        </form>
    </div>
</div>
<div class="card">
            <div class="card-header">
                <i class="fa fa-list-alt"></i> Policy Records
                <a class="btn btn-success btn btn-xs float-right mr-1" href="{{route('policy.create')}}"><i class="fa fa-plus"></i> New Policy</a>
            </div>
            <div class="card-body">
                @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
                @endif
                <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <tbody>
                        <tr>
                            <th>Serial No.</th>
                            <th>Employee Name</th>
                            <th>Employee Code</th>
                            <th class="text-right">Monthly Premium</th>
                            <th width="100px">Start Date</th>
                            <th>Last Date of deduction</th>
                            <th>Last Month/year</th>
                            <th width="230px">Action</th>
                        </tr> <!-- Modal -->
                    </tbody>
                    <tbody>
                        @foreach ($policies as $key=>$data)
                        <tr>
                            <td>{{ $key+1}}</td>
                            <td>{{ $data->employees->first_name}} {{ $data->employees->last_name}}</td>
                            <td>{{$data->employees->code}}</td>
                            <td class="text-right">{{ $data->monthly_premium }}</td>
                            <td>{{$data->start_date}}</td>
                            <td>{{$data->closing_date }}</td>
                            <td>{{$data->closing_month}}/{{$data->closing_year}}</td>
                            <td>
                                {{-- <a href="" class="btn btn-primary">View</a> --}}

                                <a href="" class="btn btn-danger btn-sm">Delete</a>
                                <a href="{{route('policy.edit',['id' => $data->id])}}" class="btn btn-success btn-sm">View/Edit</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
                {{$policies->appends(request()->all())->links()}}
            </div>

</div>
</div>
@endsection
@section("js")
<script>
    $(document).ready(function((){
        $(".select2").select2();
    });
</script>
@endsection
