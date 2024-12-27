@extends('layouts.app')
<style>
    .select2-container {
        width: 100% !important;
    }
</style>
@section('content')
<div class="single-pro-review-area mt-t-30 mg-b-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="breadcome-heading">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <ul class="breadcome-menu">
                                <li><a href="#">Dashboard</a> <span class="bread-slash">/</span>
                                </li>
                                <li><span class="bread-blod">Loans</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="product-payment-inner-st">
                        <div id="myTabContent" class="tab-content custom-product-edit">
	<div class="card">
		<div class="card-header">
			LOAN DETAILS
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
                    <select class="form-control select2" id="employee_id" name="employee_id" disabled>
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
                        <input type="text" class="form-control" value="{{ $loan->advanceType->type_name }}" disabled>
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
                    <input type="number" class="form-control" id="adj_emi" name="adj_emi" value="{{ $loan->adj_emi }}"></input>
                </div>
                <div class="col-md-3">
                    <label for="adj_emi_in">Adjust in</label>
                    <input type="text" name="adj_emi_in" id="adj_emi_in" class="form-control" required 
       value="{{ $loan->adj_emi_in == 'f' ? 'First Installment' : ($loan->adj_emi_in == 'l' ? 'Last Installment' : '') }}">

                </div>
                <div class="col-md-3">
                    <label for="interest_amount">Total Interest Amount</label>
                    <input type="number" name="interest_amount" id="interest_amount" class="form-control" step="0.01" required value="{{ $loan->interest_amount }}">
                </div>
                <div class="col-md-3 mt-5">
                        <label for="no_of_installment_interest">No of installment(Interest)</label>
                        <input type="number" name="no_of_installment_interest" id="no_of_installment_interest" class="form-control" step="0.01" value="{{ $loan->no_of_installment_interest }}" required value="0">
					</div>
                    <div class="col-md-3 mt-5">
                        <label for="interest_installment">Interest Installment</label>
                        <input type="number" name="interest_installment" id="interest_installment" class="form-control" step="0.01" required value="{{ $loan->interest_emi }}">
					</div>
                    <div class="col-md-3 mt-5">
                        <label for="adj_interest_emi">Adjustable Interest Installment</label>
                        <input type="number" name="adj_interest_emi" id="adj_interest_emi" class="form-control" step="0.01" required value="{{ $loan->adj_interest_emi }}">
					</div>
                    <div class="col-md-3 mt-5">
						<label for="adj_interest_emi_in">Adjust in</label>
                        <input type="text" name="adj_interest_emi_in" id="adj_interest_emi_in" class="form-control" required 
       value="{{ $loan->adj_interest_emi_in == 'f' ? 'First Installment' : ($loan->adj_interest_emi_in == 'l' ? 'Last Installment' : '') }}">
 
					</div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <label for="wef_month">W.E.F. Month</label>
                    <select name="wef_month" id="wef_month" class="form-control">
                        <option value="">Select Month</option>
                        <option value="1" {{ old('wef_month', $loan->from_mm) == '1' ? 'selected' : '' }}>January</option>
                        <option value="2" {{ old('wef_month', $loan->from_mm) == '2' ? 'selected' : '' }}>February</option>
                        <option value="3" {{ old('wef_month', $loan->from_mm) == '3' ? 'selected' : '' }}>March</option>
                        <option value="4" {{ old('wef_month', $loan->from_mm) == '4' ? 'selected' : '' }}>April</option>
                        <option value="5" {{ old('wef_month', $loan->from_mm) == '5' ? 'selected' : '' }}>May</option>
                        <option value="6" {{ old('wef_month', $loan->from_mm) == '6' ? 'selected' : '' }}>June</option>
                        <option value="7" {{ old('wef_month', $loan->from_mm) == '7' ? 'selected' : '' }}>July</option>
                        <option value="8" {{ old('wef_month', $loan->from_mm) == '8' ? 'selected' : '' }}>August</option>
                        <option value="9" {{ old('wef_month', $loan->from_mm) == '9' ? 'selected' : '' }}>September</option>
                        <option value="10" {{ old('wef_month', $loan->from_mm) == '10' ? 'selected' : '' }}>October</option>
                        <option value="11" {{ old('wef_month', $loan->from_mm) == '11' ? 'selected' : '' }}>November</option>
                        <option value="12" {{ old('wef_month', $loan->from_mm) == '12' ? 'selected' : '' }}>December</option>
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
            <hr>
            <div class="form-group row">
                    <div class="col-md-3">
                        <label for="close_advance">Close Advance</label>
                        <select class="form-control" id="close_advance" placeholder="close advance" name="close_advance" value="{{$loan->close_advance}}" disabled>
                            <option value="">NA</option>
                            <option value="Permanent" {{ $loan->close_advance == 'Permanent' ? 'selected' : '' }}>Permanent</option>
                            <option value="Temporary" {{ $loan->close_advance == 'Temporary' ? 'selected' : '' }}>Temporary</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="closed_from_month">Close From Month</label>
                        <select name="closed_from_month" id="closed_from_month" class="form-control" disabled>
							<option value="">NA</option>
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
                        <select name="closed_from_year" id="closed_from_year" class="form-control" disabled>
							<option value="">NA</option>
							@for ($year = 2024; $year <= 2035; $year++)
								<option value="{{ $year }}" {{ old('closed_from_year', $loan->closed_from_year) == $year ? 'selected' : '' }}>{{ $year }}</option>
							@endfor
						</select>
                    </div>
                    <div class="col-md-2">
                        <label for="closed_to_month">Close To Month</label>
                        <select name="closed_to_month" id="closed_to_month" class="form-control" disabled>
							<option value="">NA</option>
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
                        <select name="closed_to_year" id="closed_to_year" class="form-control" disabled>
							<option value="">NA</option>
							@for ($year = 2024; $year <= 2035; $year++)
								<option value="{{ $year }}" {{ old('closed_to_year', $loan->closed_to_year) == $year ? 'selected' : '' }}>{{ $year }}</option>
							@endfor
						</select>
                    </div>
                </div>

            </form>
        </div>
	</div>
    </div>
    </div>
    </div>
    
</div>

@endsection
