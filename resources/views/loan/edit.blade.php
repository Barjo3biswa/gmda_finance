@extends('layouts.app')
<style>
    .select2-container {
        width: 100% !important;
    }
</style>
@section('content')
<br>
<br>
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
			EDIT LOAN DETAILS
			<a href="{{ route('loan.index') }}" class="btn btn-sm btn-outline-success float-right mr-1">Loan records</a>
		</div>
		<br>
		<div class="card-body">
            <form action="{{ route('loan.update', $loan->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                <input type="hidden" name="loan_id" id="" value="{{ old('loan_id', $loan->id) }}">
                <input type="hidden" name="advances_id" id="" value="{{ old('advances_id', $loan->advances_id) }}">
                <input type="hidden" name="emp_code" id="emp_code" value="{{ old('emp_code', $loan->emp_code) }}">
                <input type="hidden" name="sal_block_id" id="sal_block_id" value="{{ old('sal_block_id', $loan->sal_block_id) }}">
                
                <div class="col-md-3">
                    <label for="">Employee</label>
                    <select class="form-control select2" id="employee_id" name="employee_id" readonly>
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

                <!-- <div class="col-md-3">
                    <label for="">Salary Head</label>
                    <select name="sal_block_id" id="sal_block_id" class="form-control" required>
                        <option value="">--SELECT--</option>
                        @foreach($salaryheads as $salaryhead)
                            <option value="{{ $salaryhead->id }}" {{ $loan->sal_block_id == $salaryhead->id ? 'selected' : '' }}>
                                {{ $salaryhead->name }}
                            </option>
                        @endforeach
                    </select>
                </div> -->

            </div>
            <hr>
            <div class="form-group row">
                <div class="col-md-2">
                    <label for="reference_no">Application No</label>
                    <input type="text" name="reference_no" id="reference_no" class="form-control" value="{{ old('reference_no', $loan->reference_no) }}" required>
                </div>
                <div class="col-md-2">
                    <label for="loan_amount">Loan Amount</label>
                    <input type="number" name="loan_amount" id="loan_amount" class="form-control" value="{{ old('loan_amount', $loan->principal_amount) }}" required>
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
                <div class="col-md-2">
						<label for="outstanding_principal">Outstanding Principal</label>
						<input type="number" class="form-control" id="outstanding_principal" name="outstanding_principal" step="0.01"  value="{{ old('outstanding_principal', $loan->outstanding_principal) }}"></input>
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
                <div class="col-md-3 mt-5">
                        <label for="outstanding_interest_amount">Outstanding Interest Amount</label>
                        <input type="number" name="outstanding_interest_amount" id="outstanding_interest_amount" class="form-control" step="0.01" required value="{{ old('outstanding_interest_amount', $loan->outstanding_interest_amount) }}">
					</div>
                <div class="col-md-3 mt-5">
                        <label for="no_of_installment_interest">No of installment(Interest)</label>
                        <input type="number" name="no_of_installment_interest" id="no_of_installment_interest" class="form-control" required value="{{ old('no_of_installment_interest', $loan->no_of_installment_interest) }}">
					</div>
                    <div class="col-md-3 mt-5">
                        <label for="interest_installment">Interest Installment</label>
                        <input type="number" name="interest_installment" id="interest_installment" class="form-control" step="0.01" required value="{{ old('interest_installment', $loan->interest_emi) }}">
					</div>
                    <div class="col-md-3 mt-5">
                        <label for="adj_interest_emi">Adjustable Interest Installment</label>
                        <input type="number" name="adj_interest_emi" id="adj_interest_emi" class="form-control" step="0.01" required value="{{ old('adj_interest_emi', $loan->adj_interest_emi) }}">
					</div>
                    <div class="col-md-3 mt-5">
						<label for="adj_interest_emi_in">Adjust in</label>
						<select name="adj_interest_emi_in" id="adj_interest_emi_in" class="form-control">
                            <option value="">Select</option>
                            <option value="f" {{ old('adj_interest_emi_in', $loan->adj_interest_emi_in) == 'f' ? 'selected' : '' }}>First Installment</option>
                            <option value="l" {{ old('adj_interest_emi_in', $loan->adj_interest_emi_in) == 'l' ? 'selected' : '' }}>Last Installment</option>
                        </select>
					</div>
                    <div class="col-md-3 mt-5">
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
                <div class="col-md-3 mt-5">
                    <label for="wef_year">W.E.F. Year</label>
                    <select name="wef_year" id="wef_year" class="form-control">
                        <option value="">Select Year</option>
                        @for ($year = 2020; $year <= 2035; $year++)
                            <option value="{{ $year }}" {{ old('wef_year', $loan->from_yyyy) == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="form-group row">
                
            </div>
            <hr>
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
</div>
</div>
</div>
@section('js')
<script>
document.getElementById("loan_amount").addEventListener("change", calculatePrincipalAmount);
document.getElementById("loan_interest_rate").addEventListener("change", calculatePrincipalAmount);
document.getElementById("no_of_installment").addEventListener("change", calculatePrincipalAmount);
document.getElementById("no_of_installment_interest").addEventListener("change", calculatePrincipalAmount);

function calculatePrincipalAmount() {
    // Get values from the form
    const loanAmount = parseFloat(document.getElementById("loan_amount").value);
    const interestRate = parseFloat(document.getElementById("loan_interest_rate").value);
    const duration = parseInt(document.getElementById("no_of_installment").value);
    const noOfInstallmentsInterest = parseInt(document.getElementById("no_of_installment_interest").value) || duration;
    
    // Validate input values
    if (isNaN(loanAmount) || isNaN(interestRate) || isNaN(duration) || loanAmount <= 0 || interestRate < 0 || duration <= 0) {
        document.getElementById("principal_amount").value = 0;
        document.getElementById("monthly_emi").value = 0;
        document.getElementById("interest_amount").value = 0;
        document.getElementById("interest_installment").value = 0;
        document.getElementById("adj_interest_emi").value = 0;
        return;
    }

    // Monthly interest rate
    const monthlyRate = interestRate / 100 / 12;

    if (monthlyRate === 0) {
        // If the interest rate is 0%, the calculation becomes simple
        const emi = loanAmount / duration;
        document.getElementById("monthly_emi").value = Math.round(emi);
        document.getElementById("principal_amount").value = loanAmount;
        document.getElementById("interest_amount").value = 0;
        document.getElementById("interest_installment").value = 0;
        document.getElementById("adj_interest_emi").value = emi; // Since no interest, it's the same as EMI
        return;
    }

    // EMI Calculation formula
    const emi = (loanAmount * monthlyRate * Math.pow(1 + monthlyRate, duration)) / (Math.pow(1 + monthlyRate, duration) - 1);
    document.getElementById("monthly_emi").value = Math.round(emi);

    // Total amount paid over the loan term
    const totalAmount = emi * duration;
    
    // Total interest paid
    const interestAmount = totalAmount - loanAmount;
    document.getElementById("interest_amount").value = Math.round(interestAmount);
    
    // Interest installment calculation (split over the number of installments)
    const interestInstallment = interestAmount / noOfInstallmentsInterest;
    document.getElementById("interest_installment").value = Math.round(interestInstallment);
    
    // Adjusted interest EMI calculation
    const adjInterestEmi = emi + interestInstallment;
    document.getElementById("adj_interest_emi").value = Math.round(adjInterestEmi);
}
</script>
@endsection
