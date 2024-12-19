@extends('layouts.app')
@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            Employee Details
            <a href="{{route('policy.index')}}" class="btn btn-success float-right mr-1"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
        <div class="card-body">
            <form action="{{route('policy.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <div class=" col-md-6">
                        <label for="inputname">Employee Name</label>
                        <select class="form-control" id="employee_id" name="employee_id">
                            @foreach($employees as $key=>$employee)
                            <option value="{{$employee->user_id}}" id="employee_id" {{$policies->employee_id == $employee->user_id ? "selected" : ""}}>{{$employee->first_name}} {{$employee->last_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- <div class=" col-md-6">
                         <label for="">Employee Code</label>
                        <select class="form-control" id="employee_code" name="employee_code">
                            @foreach($employees as $key=>$employee)
                            <option value=" {{$employee->code}}" id="employee_id">{{$employee->code}}</option>
                            @endforeach
                        </select>
                    </div> --}}
                </div>
                <div class="card-title">
                    <hr />
                    <strong>Policy details</strong>
                    <hr />
                </div>

                <div class="form-group row">
                    <div class=" col-md-6">
                        <label for="policy_no">LIC Policy Number</label>
                        <input value="{{$policies->policy_no}}" type="number" class="form-control" id="policy_no" placeholder="policy number" name="policy_no" id="policy_no" required>
                    </div>
                    <div class="col-md-6">
                        <label for="dependent_name">Self/Dependent Name</label>
                        <input value="{{$policies->dependent_name}}" type="text" class="form-control" id="dependent_name" name="dependent_name" placeholder="dependent name" required="">
                    </div>
                </div>
                <div class="form-group row">
                    <div class=" col-md-6">
                        <label for="policy_name">LIC Policy Name</label>
                        <input type="text" class="form-control" id="policy_name" placeholder="policy name" name="policy_name" id="policy_name" required value="{{$policies->policy_name}}">
                    </div>
                    <!-- <div class="col-md-6">
                        <label for="amount">Policy Amount</label>
                        <input value="{{$policies->amount}}" type="number" class="form-control  text-right" id="amount" name="amount" placeholder="policy amount" required="" step="0.01">
                    </div> -->
                </div>

                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="monthly_premium">Monthly Premium</label>
                        <input value="{{$policies->monthly_premium}}" type="number" class="form-control text-right" id="monthly_premium" name="monthly_premium" placeholder="monthly premium" required="" step="0.01">
                    </div>
                    <div class="col-md-3">
                        <label for="wef_month">W.E.F. Month</label>
                        <select name="wef_month" id="wef_month" class="form-control">
							<option value="">Select Month</option>
							<option value="1" {{ old('wef_month', $policies->wef_month) == '1' ? 'selected' : '' }}>January</option>
							<option value="2" {{ old('wef_month', $policies->wef_month) == '2' ? 'selected' : '' }}>February</option>
							<option value="3" {{ old('wef_month', $policies->wef_month) == '3' ? 'selected' : '' }}>March</option>
							<option value="4" {{ old('wef_month', $policies->wef_month) == '4' ? 'selected' : '' }}>April</option>
							<option value="5" {{ old('wef_month', $policies->wef_month) == '5' ? 'selected' : '' }}>May</option>
							<option value="6" {{ old('wef_month', $policies->wef_month) == '6' ? 'selected' : '' }}>June</option>
							<option value="7" {{ old('wef_month', $policies->wef_month) == '7' ? 'selected' : '' }}>July</option>
							<option value="8" {{ old('wef_month', $policies->wef_month) == '8' ? 'selected' : '' }}>August</option>
							<option value="9" {{ old('wef_month',   $policies->wef_month) == '9' ? 'selected' : '' }}>September</option>
							<option value="10" {{ old('wef_month', $policies->wef_month) == '10' ? 'selected' : '' }}>October</option>
							<option value="11" {{ old('wef_month', $policies->wef_month) == '11' ? 'selected' : '' }}>November</option>
							<option value="12" {{ old('wef_month', $policies->wef_month) == '12' ? 'selected' : '' }}>December</option>
						</select>
                    </div>
                    <div class="col-md-3">
                        <label for="wef_year">W.E.F. Year</label>
                        <select name="wef_year" id="wef_year" class="form-control">
							<option value="">Select Year</option>
							@for ($year = 2025; $year <= 2035; $year++)
								<option value="{{ $year }}" {{ old('wef_year', $policies->wef_year) == $year ? 'selected' : '' }}>{{ $year }}</option>
							@endfor
						</select>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="start_date">LIC Start Date</label>
                        <input type="date" value="{{$policies->start_date}}" class="form-control" id="start_date" placeholder="start date" name="start_date" required="">
                    </div>

                    <div class="col-md-6">
                        <label for="maturity_date">LIC Mature Date</label>
                        <input type="date" value="{{$policies->maturity_date}}" name="maturity_date" id="maturity_date" class="form-control" placeholder="Maturity Date">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="closing_date">Lic Closing date</label>
                        <input type="date" value="{{$policies->closing_date}}" class="form-control" name="closing_date" id="closing_date" placeholder="closing date">
                    </div>


                </div>
                <button type="submit" class="btn btn-primary">Update</button>

            </form>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    $(document).ready(function() {
        $("#employee_id").change(function(event) {
            $("#dependent_name").val($("#employee_id option:selected").text());
        });
    });

    $('#wef_month').Zebra_DatePicker({
        format: 'm',
    });
    $('#wef_year').Zebra_DatePicker({
        format: 'Y',
    });
    $('#start_date').Zebra_DatePicker({
        format: 'Y/m/d',
    });
    $('#maturity_date').Zebra_DatePicker({
        format: 'Y/m/d',
    });
    $('#closing_date').Zebra_DatePicker({
        format: 'Y/m/d',
    });
</script>
@endsection
