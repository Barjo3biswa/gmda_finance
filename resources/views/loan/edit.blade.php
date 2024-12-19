@extends('layouts.app')
<style>
    .select2-container {
        width: 100% !important;
    }
</style>
@section('content')
<div class="container" style="margin-bottom: 100px; margin-top: 100px;">
	<div class="card">
		<div class="card-header">
			EDIT LOAN DETAILS
			<a href="{{ route('loan.index') }}" class="btn btn-sm btn-outline-success float-right mr-1">Loan records</a>
		</div>
		<br>
		<div class="card-body">
            <form action="{{ route('loan.update', $loan->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                <input type="hidden" name="emp_code" id="emp_code" value="{{ old('emp_code', $loan->emp_code) }}">
                <input type="hidden" name="sal_block_id" id="sal_block_id" value="{{ old('sal_block_id', $loan->sal_block_id) }}">
                
                <div class="col-md-3">
                    <label for="">Employee</label>
                    <select class="form-control select2" id="employee_id" name="employee_id">
                        <option value="">--SELECT--</option>
                        @foreach ($employees as $key => $emp)
                            <option value="{{ $emp->user_id }}" {{ $loan->user_id == $emp->user_id ? 'selected' : '' }}>
                                {{ $emp->first_name }} {{ $emp->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label for="">Loan Type</label>
                    <select name="loan_type_id" id="loan_type_id" class="form-control" required>
                        <option value="">--SELECT--</option>
                        @foreach($advanceTypes as $advanceType)
                            <option value="{{ $advanceType->id }}" {{ $loan->loan_type_id == $advanceType->id ? 'selected' : '' }}>
                                {{ $advanceType->type_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="">Salary Head</label>
                    <select name="sal_block_id" id="sal_block_id" class="form-control" required>
                        <option value="">--SELECT--</option>
                        @foreach($salaryheads as $salaryhead)
                            <option value="{{ $salaryhead->id }}" {{ $loan->sal_block_id == $salaryhead->id ? 'selected' : '' }}>
                                {{ $salaryhead->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>
            <hr>
            <div class="form-group row">
                <div class="col-md-2">
                    <label for="reference_no">Application No</label>
                    <input type="text" name="reference_no" id="reference_no" class="form-control" value="{{ old('reference_no', $loan->reference_no) }}" required>
                </div>
                <div class="col-md-3">
                    <label for="loan_amount">Loan Amount</label>
                    <input type="number" name="loan_amount" id="loan_amount" class="form-control" value="{{ old('loan_amount', $loan->loan_amount) }}" required>
                </div>
                <div class="col-md-2">
                    <label for="loan_interest_rate">Interest Rate</label>
                    <input type="number" name="loan_interest_rate" id="loan_interest_rate" class="form-control" step="0.01" value="{{ old('loan_interest_rate', $loan->loan_interest_rate) }}" required>
                </div>
                <div class="col-md-2">
                    <label for="no_of_installment">No of installment</label>
                    <input type="number" class="form-control" id="no_of_installment" name="no_of_installment" value="{{ old('no_of_installment', $loan->no_of_installment) }}"></input>
                </div>
                <div class="col-md-2">
                    <label for="principal_amount">Principal Amount</label>
                    <input type="number" class="form-control" id="principal_amount" name="principal_amount" step="0.01" value="{{ old('principal_amount', $loan->principal_amount) }}"></input>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <label for="monthly_emi">Monthly Installment</label>
                    <input type="number" class="form-control" id="monthly_emi" name="monthly_emi" value="{{ old('monthly_emi', $loan->monthly_emi) }}"></input>
                </div>
                <div class="col-md-3">
                    <label for="adj_emi">Adjustable Installment</label>
                    <input type="number" class="form-control" id="adj_emi" name="adj_emi" value="{{ old('adj_emi', $loan->adj_emi) }}"></input>
                </div>
                <div class="col-md-3">
                    <label for="adj_emi_in">Adjust in</label>
                    <select name="adj_emi_in" id="adj_emi_in" class="form-control">
                        <option value="">Select</option>
                        <option value="f" {{ old('adj_emi_in', $loan->adj_emi_in) == 'f' ? 'selected' : '' }}>First Installment</option>
                        <option value="l" {{ old('adj_emi_in', $loan->adj_emi_in) == 'l' ? 'selected' : '' }}>Last Installment</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="interest_amount">Total Interest Amount</label>
                    <input type="number" name="interest_amount" id="interest_amount" class="form-control" step="0.01" required value="{{ old('interest_amount', $loan->interest_amount) }}">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <label for="wef_month">W.E.F. Month</label>
                    <select name="wef_month" id="wef_month" class="form-control">
                        <option value="">Select Month</option>
                        <option value="1" {{ old('wef_month', $loan->from_mm) == '1' ? 'selected' : '' }}>January</option>
                        <option value="2" {{ old('wef_month', $loan->from_mm) == '2' ? 'selected' : '' }}>February</option>
                        <!-- Add remaining months -->
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="wef_year">W.E.F. Year</label>
                    <select name="wef_year" id="wef_year" class="form-control">
                        <option value="">Select Year</option>
                        @for ($year = 2025; $year <= 2035; $year++)
                            <option value="{{ $year }}" {{ old('wef_year', $loan->from_yyyy) == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="form-group row">
                    <div class="col-md-3">
                        <label for="close_advance">Close Advance</label>
                        <select class="form-control" id="close_advance" placeholder="close advance" name="close_advance" value="{{$loan->close_advance}}">
                            <option value="">--SELECT--</option>
                            <option value="Permanent" {{ $loan->close_advance == 'Permanent' ? 'selected' : '' }}>Permanent</option>
                            <option value="Temporary" {{ $loan->close_advance == 'Temporary' ? 'selected' : '' }}>Temporary</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="closed_from_month">Close From Month</label>
                        <select name="closed_from_month" id="closed_from_month" class="form-control">
							<option value="">Select Month</option>
							<option value="1" {{ old('closed_from_month', $loan->closed_from_month) == '1' ? 'selected' : '' }}>January</option>
							<option value="2" {{ old('closed_from_month', $loan->closed_from_month) == '2' ? 'selected' : '' }}>February</option>
							<option value="3" {{ old('closed_from_month', $loan->closed_from_month) == '3' ? 'selected' : '' }}>March</option>
							<option value="4" {{ old('closed_from_month', $loan->closed_from_month) == '4' ? 'selected' : '' }}>April</option>
							<option value="5" {{ old('closed_from_month', $loan->closed_from_month) == '5' ? 'selected' : '' }}>May</option>
							<option value="6" {{ old('closed_from_month', $loan->closed_from_month) == '6' ? 'selected' : '' }}>June</option>
							<option value="7" {{ old('closed_from_month', $loan->closed_from_month) == '7' ? 'selected' : '' }}>July</option>
							<option value="8" {{ old('closed_from_month', $loan->closed_from_month) == '8' ? 'selected' : '' }}>August</option>
							<option value="9" {{ old('closed_from_month', $loan->closed_from_month) == '9' ? 'selected' : '' }}>September</option>
							<option value="10" {{ old('closed_from_month', $loan->closed_from_month) == '10' ? 'selected' : '' }}>October</option>
							<option value="11" {{ old('closed_from_month', $loan->closed_from_month) == '11' ? 'selected' : '' }}>November</option>
							<option value="12" {{ old('closed_from_month', $loan->closed_from_month) == '12' ? 'selected' : '' }}>December</option>
						</select>
                    </div>
                    <div class="col-md-2">
                        <label for="closed_from_year">Close From Year</label>
                        <select name="closed_from_year" id="closed_from_year" class="form-control">
							<option value="">Select Year</option>
							@for ($year = 2024; $year <= 2035; $year++)
								<option value="{{ $year }}" {{ old('closed_from_year', $loan->closed_from_year) == $year ? 'selected' : '' }}>{{ $year }}</option>
							@endfor
						</select>
                    </div>
                    <div class="col-md-2">
                        <label for="closed_to_month">Close To Month</label>
                        <select name="closed_to_month" id="closed_to_month" class="form-control">
							<option value="">Select Month</option>
							<option value="1" {{ old('closed_to_month', $loan->closed_to_month) == '1' ? 'selected' : '' }}>January</option>
							<option value="2" {{ old('closed_to_month', $loan->closed_to_month) == '2' ? 'selected' : '' }}>February</option>
							<option value="3" {{ old('closed_to_month', $loan->closed_to_month) == '3' ? 'selected' : '' }}>March</option>
							<option value="4" {{ old('closed_to_month', $loan->closed_to_month) == '4' ? 'selected' : '' }}>April</option>
							<option value="5" {{ old('closed_to_month', $loan->closed_to_month) == '5' ? 'selected' : '' }}>May</option>
							<option value="6" {{ old('closed_to_month', $loan->closed_to_month) == '6' ? 'selected' : '' }}>June</option>
							<option value="7" {{ old('closed_to_month', $loan->closed_to_month) == '7' ? 'selected' : '' }}>July</option>
							<option value="8" {{ old('closed_to_month', $loan->closed_to_month) == '8' ? 'selected' : '' }}>August</option>
							<option value="9" {{ old('closed_to_month', $loan->closed_to_month) == '9' ? 'selected' : '' }}>September</option>
							<option value="10" {{ old('closed_to_month', $loan->closed_to_month) == '10' ? 'selected' : '' }}>October</option>
							<option value="11" {{ old('closed_to_month', $loan->closed_to_month) == '11' ? 'selected' : '' }}>November</option>
							<option value="12" {{ old('closed_to_month', $loan->closed_to_month) == '12' ? 'selected' : '' }}>December</option>
						</select>
                    </div>
                    <div class="col-md-2">
                        <label for="closed_to_year">Close To Year</label>
                        <select name="closed_to_year" id="closed_to_year" class="form-control">
							<option value="">Select Year</option>
							@for ($year = 2024; $year <= 2035; $year++)
								<option value="{{ $year }}" {{ old('closed_to_year', $loan->closed_to_year) == $year ? 'selected' : '' }}>{{ $year }}</option>
							@endfor
						</select>
                    </div>
                </div>

            <div class="row mt-5">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary btn-sm float-right">Update</button>
                </div>
            </div>
            </form>
        </div>
	</div>
</div>