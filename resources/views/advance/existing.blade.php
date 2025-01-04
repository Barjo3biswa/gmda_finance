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
                                <li><span class="bread-blod">Create Advances</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="product-payment-inner-st">
                        <div id="myTabContent" class="tab-content custom-product-edit">
                            <div class="card">
                                <div class="card-header">
                                    CREATE ADVANCE
                                    <a href="{{ route('advance.index') }}" class="btn btn-sm btn-outline-success float-right mr-1">Advance
                                        records</a>
                                </div>
                                <br>
                                <div class="card-body">
                                    <form action="{{ route('advance.store-existing') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                    <div class="row">
                        <input type="hidden" name="emp_code" id="emp_code">
                        <input type="hidden" name="sal_block_id" id="sal_block_id">
                        <div class="col-md-3">
                            <label for="">Employee</label>
                            <select class="js-example-basic-multiple form-control" id="employee_id" name="employee_id">
                                <option value="">--SELECT--</option>
                                @foreach ($employees as $key => $emp)
                                    <option value="{{ $emp->user_id }}">{{ $emp->first_name }} {{ $emp->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <!-- <div class=" col-md-3">
                                <label for="">Advance Group</label>
                                <select name="advance_group_id" id="advance_group_id" class="form-control" required>
                                    <option value="">--SELECT--</option>
                                    @foreach ($advanceGroups as $advanceGroup)
    <option value="{{ $advanceGroup->id }}" {{ old('advance_group_id') == $advanceGroup->id ? 'selected' : '' }}>{{ $advanceGroup->group_name }}</option>
    @endforeach
                                </select>
                            </div> --> --}}
                        <div class=" col-md-3">
                            <label for="">Advance Type</label>
                            <select name="advance_type_id" id="advance_type_id" class="form-control" required>
                                <option value="">--SELECT--</option>
                                @foreach ($advanceTypes as $advanceType)
                                    <option value="{{ $advanceType->id }}"
                                        {{ old('advance_type_id') == $advanceType->id ? 'selected' : '' }}>
                                        {{ $advanceType->type_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- <!-- <div class=" col-md-3">
                                                    <label for="">Salary Head</label>
                                                    <select name="sal_block_id" id="sal_block_id" class="form-control" required>
                                                        <option value="">--SELECT--</option>
                                                        @foreach ($salaryheads as $salaryhead)
    <option value="{{ $salaryhead->id }}" {{ old('sal_block_id') == $salaryhead->id ? 'selected' : '' }}>{{ $salaryhead->name }}</option>
    @endforeach
                                                    </select>
                                                </div> --> --}}

                    </div>
                    <hr>
                    <div class="form-group row">
                        <div class=" col-md-3">
                            <label for="reference_no">Application No</label>
                            <input type="text" name="reference_no" id="reference_no" class="form-control" value="" readonly>
                        </div>
                        <div class=" col-md-3">
                            <label for="loan_amount">Advance Amount</label>
                            <input type="number" name="loan_amount" id="loan_amount" class="form-control" value="0" onchange="calculateInstallment()"
                                required>
                        </div>
                        {{-- <!-- <div class=" col-md-2">
                                                                <label for="loan_interest_rate">Interest Rate</label>
                                                                <input type="number" name="loan_interest_rate" id="loan_interest_rate" class="form-control" step="0.01" value="{{ old('loan_interest_rate') ?? 0 }}" required>
                                                            </div> --> --}}
                        <div class="col-md-2">
                            <label for="no_of_installment">No of installment</label>
                            <input type="number" class="form-control" id="no_of_installment" name="no_of_installment" onchange="calculateInstallment()"
                                value="0"></input>
                        </div>
                        <div class="col-md-2">
                            <label for="principal_amount">Principal Amount</label>
                            <input type="number" class="form-control" id="principal_amount" name="principal_amount"
                                step="0.01" value="0"></input>
                        </div>
                        <div class="col-md-2">
                            <label for="outstanding_principal">Outstanding Principal</label>
                            <input type="number" class="form-control" id="outstanding_principal"
                                name="outstanding_principal" step="0.01" value="0"></input>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-3">
                            <label for="monthly_emi">Monthly Installment</label>
                            <input type="number" class="form-control" id="monthly_emi" name="monthly_emi" value="0"
                                step="0.01"></input>
                        </div>
                        <div class="col-md-3">
                            <label for="adj_emi">Adjustable Installment</label>
                            <input type="number" class="form-control" id="adj_emi" name="adj_emi" value="0"></input>
                        </div>
                        <div class="col-md-3">
                            <label for="adj_emi_in">Adjust in</label>
                            <select name="adj_emi_in" id="adj_emi_in" class="form-control">
                                <option value="">Select</option>
                                <option value="f" {{ old('adj_emi_in') == 'f' ? 'selected' : '' }}>First Installment
                                </option>
                                <option value="l" {{ old('adj_emi_in') == 'l' ? 'selected' : '' }}>Last Installment
                                </option>
                            </select>
                        </div>
                        {{-- <!-- <div class="col-md-3">
                                                                                <label for="interest_amount">Total Interest Amount</label>
                                                                                <input type="number" name="interest_amount" id="interest_amount" class="form-control" step="0.01" required value="0">
                                                             </div>
                                                                            <div class="col-md-3 mt-5">
                                                                                <label for="no_of_installment_interest">No of installment(Interest)</label>
                                                                                <input type="number" name="no_of_installment_interest" id="no_of_installment_interest" class="form-control" step="0.01" required value="0">
                                                             </div>
                                                                            <div class="col-md-3 mt-5">
                                                                                <label for="interest_installment">Interest Installment</label>
                                                                                <input type="number" name="interest_installment" id="interest_installment" class="form-control" step="0.01" required value="0">
                                                             </div>
                                                                            <div class="col-md-3 mt-5">
                                                                                <label for="adj_interest_emi">Adjustable Interest Installment</label>
                                                                                <input type="number" name="adj_interest_emi" id="adj_interest_emi" class="form-control" step="0.01" required value="0">
                                                             </div>
                                                                            <div class="col-md-3 mt-5">
                                                              <label for="adj_interest_emi_in">Adjust in</label>
                                                              <select name="adj_interest_emi_in" id="adj_interest_emi_in" class="form-control">
                                                                                    <option value="">Select</option>
                                                                                    <option value="f" {{ old('adj_interest_emi_in') == 'f' ? 'selected' : '' }}>First Installment</option>
                                                                                    <option value="l" {{ old('adj_interest_emi_in') == 'l' ? 'selected' : '' }}>Last Installment</option>
                                                                                </select>
                                                             </div> --> --}}

                    </div>

                    <div class="form-group row">
                        <div class="col-md-3">
                            <label for="wef_month">W.E.F. Month</label>
                            <select name="wef_month" id="wef_month" class="form-control">
                                <option value="">Select Month</option>
                                <option value="1" {{ old('wef_month') == '1' ? 'selected' : '' }}>January</option>
                                <option value="2" {{ old('wef_month') == '2' ? 'selected' : '' }}>February</option>
                                <option value="3" {{ old('wef_month') == '3' ? 'selected' : '' }}>March</option>
                                <option value="4" {{ old('wef_month') == '4' ? 'selected' : '' }}>April</option>
                                <option value="5" {{ old('wef_month') == '5' ? 'selected' : '' }}>May</option>
                                <option value="6" {{ old('wef_month') == '6' ? 'selected' : '' }}>June</option>
                                <option value="7" {{ old('wef_month') == '7' ? 'selected' : '' }}>July</option>
                                <option value="8" {{ old('wef_month') == '8' ? 'selected' : '' }}>August</option>
                                <option value="9" {{ old('wef_month') == '9' ? 'selected' : '' }}>September</option>
                                <option value="10" {{ old('wef_month') == '10' ? 'selected' : '' }}>October</option>
                                <option value="11" {{ old('wef_month') == '11' ? 'selected' : '' }}>November</option>
                                <option value="12" {{ old('wef_month') == '12' ? 'selected' : '' }}>December</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="wef_year">W.E.F. Year</label>
                            <select name="wef_year" id="wef_year" class="form-control">
                                <option value="">Select Year</option>
                                @for ($year = 2020; $year <= 2035; $year++)
                                    <option value="{{ $year }}" {{ old('wef_year') == $year ? 'selected' : '' }}>
                                        {{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-sm float-right">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
        
    </div>

@endsection
@section('js')
    <script>
        function calculateInstallment() {
    // Get the values from the form
    var loanAmount = parseFloat(document.getElementById("loan_amount").value) || 0;
    var noOfInstallment = parseInt(document.getElementById("no_of_installment").value) || 0;
    var principalAmount = parseFloat(document.getElementById("principal_amount").value) || 0;

    // Calculate Monthly EMI (Example formula)
    var monthlyEmi = 0;
    if (noOfInstallment > 0) {
        monthlyEmi = loanAmount / noOfInstallment;
    }

    // Set calculated monthly EMI
    document.getElementById("monthly_emi").value = monthlyEmi.toFixed(2);

    document.getElementById("principal_amount").value = loanAmount;

    // Optionally, calculate Adjustable EMI (you can implement logic here)
    var adjustableEmi = 0;
    // Adjust as per your specific needs, here just setting a default value
    if (monthlyEmi > 0) {
        adjustableEmi = monthlyEmi * 1.05; // Adjusted EMI by 5% for example
    }
    document.getElementById("adj_emi").value = adjustableEmi.toFixed(2);
}
    </script>
@endsection
