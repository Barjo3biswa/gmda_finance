@extends('layouts.app')
<style>
    .select2-container {
        width: 100% !important;
    }
</style>
@section('content')
<br>
<br>
<br>
<br><br>
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
                <input type="hidden" name="advances_id" id="" value="{{ old('advances_id', $loan->id) }}">
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
                            <option value="{{ $advanceType->id }}" {{ $loan->advance_id == $advanceType->id ? 'selected' : '' }}>
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
                    <input type="text" name="reference_no" id="reference_no" class="form-control" value="{{ old('reference_no', $loan->reference_no) }}" required readonly>
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
                    <input type="number" class="form-control" id="no_of_installment" name="no_of_installment" value="{{ old('no_of_installment', $loan->no_of_installment) }}" onchange="ReducingInt()">
                </div>
                <div class="col-md-2">
                    <label for="principal_amount">Principal Amount</label>
                    <input type="number" class="form-control" id="principal_amount" name="principal_amount" step="0.01" value="{{ old('principal_amount', $loan->principal_amount) }}">
                </div>
                <div class="col-md-2">
						<label for="outstanding_principal">Outstanding Principal</label>
						<input type="number" class="form-control" id="outstanding_principal" name="outstanding_principal" step="0.01"  value="{{ old('outstanding_principal', $loan->outstanding_principal) }}" readonly>
					</div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <label for="monthly_emi">Monthly Installment</label>
                    <input type="number" class="form-control" id="monthly_emi" name="monthly_emi" value="{{ old('monthly_emi', $loan->monthly_emi) }}">
                </div>
                <div class="col-md-3">
                    <label for="adj_emi">Adjustable Installment</label>
                    <input type="number" class="form-control" id="adj_emi" name="adj_emi" value="{{ old('adj_emi', $loan->adj_emi) }}">
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
                        <label for="outstanding_interest_amount">Outstanding Interest Amount</label>
                        <input type="number" name="outstanding_interest_amount" id="outstanding_interest_amount" class="form-control" step="0.01" required value="{{ old('outstanding_interest_amount', $loan->outstanding_interest_amount) }}" readonly>
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
                {{-- <div class="form-group row">
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
                </div> --}}

            <div class="row mt-5">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary btn-sm float-right">Update</button>
                </div>
            </div>
            </form>
        </div>
	</div>

    <div class="row">
                <div class="col-lg-6">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title"><h5>Reducing Rate</h5></div>
                            <div class="ibox-content">
                                <div class="row">
                                <div class="col-sm-12 b-r">
                                    <form role="form">
                                        <div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label>Loan Amount</label>
                                                <input type="text" id="DispPAmtR" name="DispPAmtR" class="form-control" placeholder="0.00" readonly="true">
                                            </div>
                                            <div class="col-sm-4">
                                                <label>Int. Payable</label>
                                                <input type="text" id="DispRIntP" name="DispRIntP" placeholder="0.00" class="form-control" readonly="true">
                                            </div>
                                            <div class="col-sm-4">
                                                <label>Total Payable</label>
                                                <input type="text" min="0" step="1" id="DispRTotPayable" name="DispRTotPayable" placeholder="0.00" class="form-control"  readonly="true">
                                            </div>
                                        </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 b-r">
                                    <table id="memListTable" class="table table-responsive table-bordered">
                                        <thead>
                                            <tr>
                                                <th>SL</th>
                                                <th>EMI</th>
                                                <th>INT</th>
                                                <th>PRINCIPAL</th>
                                                <th>BALANCE</th>
                                            </tr>
                                        </thead>
                                        <tbody id="reducingDiv">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
	// alert("loanAmount: " + loanAmount + ", interestRate: " + interestRate + ", duration: " + duration);

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
    // alert("emi: " + emi);
    document.getElementById("monthly_emi").value = Math.round(emi);

    const principalAmount = (emi * (Math.pow(1 + monthlyRate, duration) - 1)) / (monthlyRate * Math.pow(1 + monthlyRate, duration));
    // alert("principalAmount: " + principalAmount);
    document.getElementById("principal_amount").value = Math.round(principalAmount);
    document.getElementById("outstanding_principal").value = 0;
    const totalAmount = emi * duration;
    const interestAmount = totalAmount - loanAmount;
    document.getElementById("interest_amount").value = Math.round(interestAmount);
    document.getElementById("outstanding_interest_amount").value = 0;
    // Calculate interest installment
    const noOfInstallmentsInterest = parseInt(document.getElementById("no_of_installment_interest").value) || duration;
    // alert("noOfInstallmentsInterest: " + noOfInstallmentsInterest);
    const interestInstallment = interestAmount / noOfInstallmentsInterest;
    document.getElementById("interest_installment").value = Math.round(interestInstallment);

    var f_installment = interestInstallment / noOfInstallmentsInterest;
	document.getElementById('adj_emi').value = Math.round(f_installment);

    const adjInterestEmi = interestInstallment + (f_installment || 0);
    document.getElementById('adj_interest_emi').value = Math.round(adjInterestEmi);
}


function ReducingInt()
{
    var selectElement = document.getElementById("loan_type_id");
    // Get the selected option
    var selectedOption = selectElement.options[selectElement.selectedIndex];
    // Get the text of the selected option
    var selectedText = selectedOption.text;
    // alert(selectedText);
    if(selectedText != "New House Buiding Advance")
    {
        // alert("New House Buiding Advance");
        return false;
    }
    var p=document.getElementById("loan_amount").value;
    var n=document.getElementById("no_of_installment").value;
    var r=document.getElementById("loan_interest_rate").value;

var REMI = Math.abs(parseFloat(PMT(((r/100)/12), n, p))); //output */
var totalRPayable = parseFloat(REMI)*parseFloat(n);
$('#DispPAmtR').val(p);
$('#DispRIntP').val( Math.round(totalRPayable-p).toFixed(2));
//$('#DispRTotPayable').val(totalRPayable.toFixed(0));
$('#DispRTotPayable').val( Math.round(totalRPayable).toFixed(2));
var ReducingIntPerMonth = 0;
var ReducingPrincipal = p;
var table_body = '';
var dispTD = '';
var totP = 0;
var totI = 0;
var d = false;
var dd = 1;
for(var i=0;i<n;i++){
        if((i == n-1 && d == true) || (i<n-1))
        {
            dd=2;
            table_body+='<tr>';
        }
        for(var j=0;j<5;j++){
            if(j == 0)
                dispTD = (i+1);
            else if(j == 1 && i!=n-1)
                dispTD = parseFloat(REMI);
            else if(j == 1 && i==n-1)
                dispTD=Math.round(REMI)+(parseFloat(totalRPayable)-(Math.round(REMI)*n));
            else if(j == 2){
                ReducingIntPerMonth = (parseFloat(ReducingPrincipal)*1*(parseFloat(r)/100))/12
                //dispTD = Math.round(ReducingIntPerMonth);
                dispTD = ReducingIntPerMonth;
                totI=totI+dispTD
                }
            else if(j == 3) {
                dispTD = (parseFloat(REMI)-parseFloat(ReducingIntPerMonth));
                    principalRefund = parseFloat(dispTD);
                    totP += parseFloat(dispTD);
                }
            else if(j == 4) {
                dispTD = (parseFloat(ReducingPrincipal)-parseFloat(principalRefund));
                //alert(dispTD);
                ReducingPrincipal = (parseFloat(ReducingPrincipal)-parseFloat(principalRefund));
                }
                if(j!=0){
                table_body +='<td>';
                table_body +=Math.round(dispTD).toFixed(2);
                table_body +='</td>';
            }else{
                table_body +='<td>';
                table_body +=dispTD;
                table_body +='</td>';
            }
        }
            table_body+='</tr>';
        }
        table_body+='<tr><td></td><td></td>';
        table_body +='<td><strong>';
        table_body += Math.round(totI).toFixed(2);
        table_body +='</strong></td><td><strong>';
        table_body += Math.round(totP).toFixed(2);
        table_body+='</strong></td></tr>';
        /*table_body+='</table>';*/
        $('#reducingDiv').html(table_body);

    }

function PMT(i, n, p) {
    return i * p * Math.pow((1 + i), n) / (1 - Math.pow((1 + i), n));
}
</script>

<script>
    // Function to collect table data
    function collectTableData() {
        var tableData = [];
        $("#memListTable tbody tr").each(function() {
            var row = {};
            var balance = $(this).find("td").eq(4).text().trim();
            if (balance === "" || balance === "0.00") {
                return;
            }
            $(this).find("td").each(function(index) {
                switch(index) {
                    case 0: row.sl = $(this).text(); break;
                    case 1: row.emi = $(this).text(); break;
                    case 2: row.int = $(this).text(); break;
                    case 3: row.principal = $(this).text(); break;
                    case 4: row.balance = $(this).text(); break;
                }
            });
            tableData.push(row);
        });
        return tableData;
    }

    // Attach event to the form submission
    $("form").on("submit", function(e) {
        var data = collectTableData();
        // Serialize data and set it in the hidden input
        $('#tableData').val(JSON.stringify(data)); // Convert array to JSON string

        // Form will now submit with the serialized data
    });
</script>
@endsection
