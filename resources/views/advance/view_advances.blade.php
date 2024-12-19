@extends('layouts.app')
@section('content')
<div class="container" style="margin-bottom: 100px; margin-top: 50px; box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);">
    <div class="card mb-3">
        <div class="card-header">
            <i class="fa fa-filter"></i> Filter
        </div>
        <div class="card-body">
            <form method="get" action="">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="name">Type</label>
                            <select name="advance_type_id" id="advance_type_id" class="form-control">
                                <option value="">--All--</option>
                                @foreach($advanceTypes as $advanceType)
                                    <option value="{{ $advanceType->id }}" {{ request('advance_type_id') == $advanceType->id ? 'selected' : '' }}>{{ $advanceType->type_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="name">Department</label>
                            <select name="department_id" id="department" class="form-control select2">
                                <option value="">--All--</option>
                                @foreach ($departments as $department)
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
                                    <option value="{{$employee->id}}" {{request("employee_id") == $employee->id ? "selected" : ""}}>{{$employee->first_name}} {{$employee->last_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2" style="padding-top: 24px;">
                        <button type="submit" class='btn btn-primary btn-sm text-white mt-4'>Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-2">
        <div class="card-body">
            <h5>Advance List</h5>
            <a class="btn btn-success btn btn-xs float-right mr-1" href="{{route("advance.existing")}}"><i class="fa fa-plus"></i> Existing Advance</a>
            @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered table-sm font-12">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Reference No</th>
                            <th>Emp. Name</th>
                            <th>Type</th>
                            <th>Principal Amount</th>
                            <th class="text-right">Monthly EMI</th>
                            <th class="text-right">Interest Amount</th>
                            <th>Interest EMI</th>
                            <th>Block Month</th>
                            <th>Block Year</th>
                            <th width="100px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($advances as $key => $advance)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $advance->reference_no }}</td>
                            <td>{{ optional($advance->employee)->first_name }} ({{ optional($advance->employee)->code }})</td>
                            <td>{{ optional($advance->advanceType)->type_name }}</td>
                            <td class="text-right">{{ number_format($advance->principal_amount, 2) }}</td>
                            <td class="text-right">{{ number_format($advance->principal_installment, 2) }}</td>
                            <td class="text-right">{{ number_format($advance->interest_amount, 2) }}</td>
                            <td class="text-right">{{ number_format($advance->interest_installment, 2) }}</td>
                            <td>{{ date('F', mktime(0, 0, 0, $advance->sal_block_month, 1)) }}</td>
                            <td>{{ $advance->sal_block_yr }}</td>
                            <td>
                                <a href="{{ route('advance.viewadvancedetails', $advance->id) }}" class="btn btn-info btn-xs">
                                    <i class="fa fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center">No records found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="float-right">
                    {{ $advances->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
