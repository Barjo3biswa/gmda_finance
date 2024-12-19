@extends('layouts.app')
@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            Employee Details
            <a href="{{route('advance.index')}}" class="btn btn-success float-right btn-xs mr-1"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
        <div class="card-body">
            <form action="{{route('advance.update',[$advance->id])}}" method="POST" enctype="multipart/form-data">
                @csrf
                {{ method_field('PUT') }}
                <div class="form-group row">
                    <div class=" col-md-6">
                        <label for="inputname">Employee Name</label>
                        <input type="text" class="form-control" id="emp_name" name="emp_name" value="{{$advance->employee->first_name}} {{$advance->employee->last_name}}">
                        <input type="hidden" class="form-control" id="employee_id" name="employee_id" value="{{$advance->user_id}}">
                        <input type="hidden" class="form-control" id="emp_code" name="emp_code" value="{{$advance->employee->code}}">
                    </div>
                </div>
                <div class="card-title">
                    <strong>Advance details</strong>
                    <hr />
                </div>
                <div class="row mb-3">
                    <div class=" col-md-3">
						<label for="">Ref. No:</label>
						{{$advance->reference_no}}
                        <input type="hidden" class="form-control" id="ref_no" name="ref_no" value="{{$advance->reference_no}}">
					</div>

                    <!-- <input type="hidden" class="form-control" id="loan_type" name="loan_type" value="{{$advance->loan_type_id}}"> -->
                    <input type="hidden" class="form-control" id="loan_head_id" name="loan_head_id" value="{{$advance->loan_head_id}}">
				
                    <div class=" col-md-3">
						<label for="">Advance Type:</label>
						{{$advance->advanceType->type_name}}
                        <input type="hidden" class="form-control" id="loan_type_id" name="loan_type_id" value="{{$advance->advance_id ?? ''}}">
					</div>
                    <div class=" col-md-4">
						<label for="">Salary Head:</label>
						{{$advance->salhead->name ?? 'NA'}}
                        <input type="hidden" class="form-control" id="sal_block_id" name="sal_block_id" value="{{$advance->salhead->id ?? ''}}">
					</div>
                </div>
                <br>
                <div class="form-group row">
                    <div class=" col-md-3">
                        <label for="loan_amount">Loan Amount</label>
                        <input type="number" name="loan_amount" id="loan_amount" class="form-control"  value="{{$advance->principal_amount ?? 0}}" required>
                    </div>
                    <!-- <div class=" col-md-3">
                        <label for="loan_interest_rate">Interest Rate</label>
                        <input type="number" name="loan_interest_rate" id="loan_interest_rate" class="form-control" step="0.01" value="{{old('loan_interest_rate') ?? 0}}" required>
                    </div> -->
                    <div class="col-md-3">
						<label for="no_of_installment">No of installment</label>
						<input type="number" class="form-control" id="no_of_installment" name="no_of_installment" value="{{$advance->duration}}"></input>
					</div>
                    <div class="col-md-3">
						<label for="principal_amount">Principal Amount</label>
						<input type="number" class="form-control" id="principal_amount" name="principal_amount" step="0.01"  value="{{$advance->principal_amount ?? 0}}"></input>
					</div>
				</div>

                <div class="form-group row">
                    <div class="col-md-3">
						<label for="monthly_emi">Installment</label>
						<input type="number" class="form-control" id="monthly_emi" name="monthly_emi" value="{{$advance->monthly_installment ?? 0}}" ></input>
					</div>
                    <div class="col-md-3">
						<label for="adj_emi">Adjustable Installment</label>
						<input type="number" class="form-control" id="adj_emi" name="adj_emi" value="{{old('adj_emi') ?? $advance->adjustable_installment ?? 0}}" ></input>
					</div>
                    <div class="col-md-3">
						<label for="adj_emi_in">Adjust in</label>
						<select name="adj_emi_in" id="adj_emi_in" class="form-control">
                            <option value="">Select</option>
                            <option value="f" {{ old('adj_emi_in',$advance->adjust_in) == 'f' ? 'selected' : '' }}>First Installment</option>
                            <option value="l" {{ old('adj_emi_in',$advance->adjust_in) == 'l' ? 'selected' : '' }}>Last Installment</option>
                        </select>
					</div>
                    <!-- <div class="col-md-3">
						<label for="">Recovered Amount</label>
						<input type="number" name="recovered_amount" id="recovered_amount" class="form-control" step="0.01" required value="{{$advance->recovered_amount}}">
					</div> -->
                    <!-- <div class="col-md-3">
                        <label for="interest_amount">Total Interest Amount</label>
                        <input type="number" name="interest_amount" id="interest_amount" class="form-control" step="0.01" required value="{{$advance->interest_amount}}">
					</div>
                    <div class="col-md-3 mt-5">
                        <label for="no_of_installment_interest">No of installment(Interest)</label>
                        <input type="number" name="no_of_installment_interest" id="no_of_installment_interest" class="form-control" step="0.01" required value="0">
					</div>
                    <div class="col-md-3 mt-5">
                        <label for="interest_installment">Interest Installment</label>
                        <input type="number" name="interest_installment" id="interest_installment" class="form-control" step="0.01" required value="{{$advance->interest_installment ?? 0}}">
					</div>
                    <div class="col-md-3 mt-5">
                        <label for="adj_interest_emi">Adjustable Interest Installment</label>
                        <input type="number" name="adj_interest_emi" id="adj_interest_emi" class="form-control" step="0.01" required value="{{$advance->interest_installment ?? 0}}">
					</div>
                    <div class="col-md-3 mt-5">
						<label for="adj_interest_emi_in">Adjust in</label>
						<select name="adj_interest_emi_in" id="adj_interest_emi_in" class="form-control">
                            <option value="">Select</option>
                            <option value="f" {{ old('adj_interest_emi_in') == 'f' ? 'selected' : '' }}>First Installment</option>
                            <option value="l" {{ old('adj_interest_emi_in') == 'l' ? 'selected' : '' }}>Last Installment</option>
                        </select>
					</div> -->
                    <!-- <div class="col-md-3 mt-5">
							<label for="interest_recovered">Recovered Interest Amount</label>
							<input type="number" name="interest_recovered" id="interest_recovered" class="form-control" step="0.01" required value="{{$advance->interest_recovered}}">
					</div> -->
                </div>

				<div class="form-group row">
					<div class="col-md-3">
						<label for="wef_month">W.E.F. Month</label>
                        <select name="wef_month" id="wef_month" class="form-control">
							<option value="">Select Month</option>
							<option value="1" {{ old('wef_month', (string) $advance->installment_month) == '1' ? 'selected' : '' }}>January</option>
                            <option value="2" {{ old('wef_month', (string) $advance->installment_month) == '2' ? 'selected' : '' }}>February</option>
                            <option value="3" {{ old('wef_month', (string) $advance->installment_month) == '3' ? 'selected' : '' }}>March</option>
                            <option value="4" {{ old('wef_month', (string) $advance->installment_month) == '4' ? 'selected' : '' }}>April</option>
                            <option value="5" {{ old('wef_month', (string) $advance->installment_month) == '5' ? 'selected' : '' }}>May</option>
                            <option value="6" {{ old('wef_month', (string) $advance->installment_month) == '6' ? 'selected' : '' }}>June</option>
                            <option value="7" {{ old('wef_month', (string) $advance->installment_month) == '7' ? 'selected' : '' }}>July</option>
                            <option value="8" {{ old('wef_month', (string) $advance->installment_month) == '8' ? 'selected' : '' }}>August</option>
                            <option value="9" {{ old('wef_month', (string) $advance->installment_month) == '9' ? 'selected' : '' }}>September</option>
                            <option value="10" {{ old('wef_month', (string) $advance->installment_month) == '10' ? 'selected' : '' }}>October</option>
                            <option value="11" {{ old('wef_month', (string) $advance->installment_month) == '11' ? 'selected' : '' }}>November</option>
                            <option value="12" {{ old('wef_month', (string) $advance->installment_month) == '12' ? 'selected' : '' }}>December</option>
						</select>
					</div>
					<div class="col-md-3">
						<label for="wef_year">W.E.F. Year</label>
                        <select name="wef_year" id="wef_year" class="form-control">
							<option value="">Select Year</option>
							@for ($year = 2020; $year <= 2035; $year++)
								<option value="{{ $year }}" {{ old('wef_year', $advance->installment_year) == $year ? 'selected' : '' }}>{{ $year }}</option>
							@endfor
						</select>
					</div>
				</div>

				<!-- <div class="form-group row">
					<div class="col-md-6">
						<label for="inputStart_date">Advance Start Date</label>
						<input class="form-control" id="start_date" placeholder="start date" name="start_date" value="{{$advance->start_date}}">
					</div>
                    <div class="col-md-6">
						<label for="">Advance Closing date</label>
						<input class="form-control" name="closing_date" id="closing_date" placeholder="closing date" value="{{$advance->closing_date}}">
					</div>
				</div> -->

                <div class="form-group row">
                    <div class="col-md-3">
                        <label for="close_advance">Close Advance</label>
                        <select class="form-control" id="close_advance" placeholder="close advance" name="close_advance" value="{{$advance->close_advance}}">
                            <option value="">--SELECT--</option>
                            <option value="Permanent" {{ $advance->close_advance == 'Permanent' ? 'selected' : '' }}>Permanent</option>
                            <option value="Temporary" {{ $advance->close_advance == 'Temporary' ? 'selected' : '' }}>Temporary</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="closed_from_month">Close From Month</label>
                        <select name="closed_from_month" id="closed_from_month" class="form-control">
							<option value="">Select Month</option>
							<option value="1" {{ old('closed_from_month', $advance->closed_from_month) == '1' ? 'selected' : '' }}>January</option>
							<option value="2" {{ old('closed_from_month', $advance->closed_from_month) == '2' ? 'selected' : '' }}>February</option>
							<option value="3" {{ old('closed_from_month', $advance->closed_from_month) == '3' ? 'selected' : '' }}>March</option>
							<option value="4" {{ old('closed_from_month', $advance->closed_from_month) == '4' ? 'selected' : '' }}>April</option>
							<option value="5" {{ old('closed_from_month', $advance->closed_from_month) == '5' ? 'selected' : '' }}>May</option>
							<option value="6" {{ old('closed_from_month', $advance->closed_from_month) == '6' ? 'selected' : '' }}>June</option>
							<option value="7" {{ old('closed_from_month', $advance->closed_from_month) == '7' ? 'selected' : '' }}>July</option>
							<option value="8" {{ old('closed_from_month', $advance->closed_from_month) == '8' ? 'selected' : '' }}>August</option>
							<option value="9" {{ old('closed_from_month', $advance->closed_from_month) == '9' ? 'selected' : '' }}>September</option>
							<option value="10" {{ old('closed_from_month', $advance->closed_from_month) == '10' ? 'selected' : '' }}>October</option>
							<option value="11" {{ old('closed_from_month', $advance->closed_from_month) == '11' ? 'selected' : '' }}>November</option>
							<option value="12" {{ old('closed_from_month', $advance->closed_from_month) == '12' ? 'selected' : '' }}>December</option>
						</select>
                    </div>
                    <div class="col-md-2">
                        <label for="closed_from_year">Close From Year</label>
                        <select name="closed_from_year" id="closed_from_year" class="form-control">
							<option value="">Select Year</option>
							@for ($year = 2024; $year <= 2035; $year++)
								<option value="{{ $year }}" {{ old('closed_from_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
							@endfor
						</select>
                    </div>
                    <div class="col-md-2">
                        <label for="closed_to_month">Close To Month</label>
                        <select name="closed_to_month" id="closed_to_month" class="form-control">
							<option value="">Select Month</option>
							<option value="1" {{ old('closed_to_month', $advance->closed_to_month) == '1' ? 'selected' : '' }}>January</option>
							<option value="2" {{ old('closed_to_month', $advance->closed_to_month) == '2' ? 'selected' : '' }}>February</option>
							<option value="3" {{ old('closed_to_month', $advance->closed_to_month) == '3' ? 'selected' : '' }}>March</option>
							<option value="4" {{ old('closed_to_month', $advance->closed_to_month) == '4' ? 'selected' : '' }}>April</option>
							<option value="5" {{ old('closed_to_month', $advance->closed_to_month) == '5' ? 'selected' : '' }}>May</option>
							<option value="6" {{ old('closed_to_month', $advance->closed_to_month) == '6' ? 'selected' : '' }}>June</option>
							<option value="7" {{ old('closed_to_month', $advance->closed_to_month) == '7' ? 'selected' : '' }}>July</option>
							<option value="8" {{ old('closed_to_month', $advance->closed_to_month) == '8' ? 'selected' : '' }}>August</option>
							<option value="9" {{ old('closed_to_month', $advance->closed_to_month) == '9' ? 'selected' : '' }}>September</option>
							<option value="10" {{ old('closed_to_month', $advance->closed_to_month) == '10' ? 'selected' : '' }}>October</option>
							<option value="11" {{ old('closed_to_month', $advance->closed_to_month) == '11' ? 'selected' : '' }}>November</option>
							<option value="12" {{ old('closed_to_month', $advance->closed_to_month) == '12' ? 'selected' : '' }}>December</option>
						</select>
                    </div>
                    <div class="col-md-2">
                        <label for="closed_to_year">Close To Year</label>
                        <select name="closed_to_year" id="closed_to_year" class="form-control">
							<option value="">Select Year</option>
							@for ($year = 2024; $year <= 2035; $year++)
								<option value="{{ $year }}" {{ old('closed_to_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
							@endfor
						</select>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-sm">Update</button>

            </form>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
   $(document).ready(function() {
	//   $("#employee_id").change(function(event) {
    //     var name = $("#employee_id option:selected").text();
    //     name = name.replace(/[^a-zA-Z ]/g, '');
    //     console.log(name);
	//    $("#emp_name").val(name);
	//   });
	  // $(".select2").select2();
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
<script>
    // document.addEventListener('DOMContentLoaded', function() {
    //     const amountInput = document.getElementById('principal_amount');
    //     const durationInput = document.getElementById('duration');
    //     const installmentInput = document.getElementById('monthly_installment');
    //     const adjustableInstallmentInput = document.getElementById('adjustable_installment');
    //     const adjustInSelect = document.getElementById('adjust_in');

    //     function calculateInstallment() {
    //         const amount = parseFloat(amountInput.value) || 0;
    //         const duration = parseInt(durationInput.value) || 1;
            
    //         // Calculate base installment and round up to nearest integer
    //         let installment = Math.ceil(amount / duration);
            
    //         // Calculate total after rounding up
    //         const totalAfterRounding = installment * duration;
            
    //         // Calculate adjustment needed
    //         const adjustment = totalAfterRounding - amount;
            
    //         // Set the adjustable installment (the difference for first/last month)
    //         if (adjustment > 0) {
    //             const adjustedInstallment = installment - adjustment;
    //             //adjustableInstallmentInput.value = adjustedInstallment.toFixed(2);
    //         } else {
    //             //adjustableInstallmentInput.value = installment.toFixed(2);
    //         }
            
    //         installmentInput.value = installment.toFixed(2);
    //     }

    //     amountInput.addEventListener('input', calculateInstallment);
    //     durationInput.addEventListener('input', calculateInstallment);
    //     //adjustInSelect.addEventListener('change', calculateInstallment);
    // });
</script>

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

    if (isNaN(loanAmount) || isNaN(interestRate) || isNaN(duration) || loanAmount <= 0 || interestRate < 0 || duration <= 0) {
        document.getElementById("principal_amount").value = 0;
        return;
    }
    const monthlyRate = interestRate / 100 / 12;

    if (monthlyRate === 0) {
        alert("z");
        document.getElementById("principal_amount").value = loanAmount;
        return;
    }

    const emi = (loanAmount * monthlyRate * Math.pow(1 + monthlyRate, duration)) / (Math.pow(1 + monthlyRate, duration) - 1);
    alert("emi: " + emi);
    document.getElementById("monthly_emi").value = Math.round(emi);

    const principalAmount = (emi * (Math.pow(1 + monthlyRate, duration) - 1)) / (monthlyRate * Math.pow(1 + monthlyRate, duration));
    alert("principalAmount: " + principalAmount);
    document.getElementById("principal_amount").value = Math.round(principalAmount);
    const totalAmount = emi * duration;
    const interestAmount = totalAmount - loanAmount;
    document.getElementById("interest_amount").value = Math.round(interestAmount);
    
    // Calculate interest installment
    const noOfInstallmentsInterest = parseInt(document.getElementById("no_of_installment_interest").value) || duration;
    alert("noOfInstallmentsInterest: " + noOfInstallmentsInterest);
    const interestInstallment = interestAmount / noOfInstallmentsInterest;
    document.getElementById("interest_installment").value = Math.round(interestInstallment);
}
</script>
@endsection
