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
                                <li><span class="bread-blod">New Loan</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="product-payment-inner-st">
                        <div id="myTabContent" class="tab-content custom-product-edit">
                            <div class="card">
                                <div class="card-header">
                                    NEW LOAN DETAILS
                                    <a href="{{ route('loan.index') }}" class="btn btn-sm btn-outline-success float-right mr-1">Loan records</a>
                                </div>
                                <br>
                                <div class="card-body">
                                    <form action="{{ route('loan.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf

                                        <input type="hidden" name="table_data" id="tableData">

                                        <div class="row">
                                            <input type="hidden" name="emp_code" id="emp_code">
                                            <input type="hidden" name="sal_block_id" id="sal_block_id">
                                            <div class="col-md-3">
                                                <label for="">Employee</label>
                                                <select class="form-control select2" id="employee_id" name="employee_id" required>
                                                    <option value="">--SELECT--</option>
                                                    @foreach ($employees as $key => $emp)
                                                        <option value="{{ $emp->user_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <!-- <div class=" col-md-3">
                                                <label for="">Advance Group</label>
                                                <select name="advance_group_id" id="advance_group_id" class="form-control" required>
                                                    <option value="">--SELECT--</option>
                                                    @foreach($advanceGroups as $advanceGroup)
                                                        <option value="{{ $advanceGroup->id }}" {{ old('advance_group_id') == $advanceGroup->id ? 'selected' : '' }}>{{ $advanceGroup->group_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div> -->
                                            <div class=" col-md-3">
                                                <label for="">Loan Type</label>
                                                <select name="loan_head_id" id="loan_head_id" class="form-control" required>
                                                    <option value="">--SELECT--</option>
                                                    @foreach($advanceTypes as $advanceType)
                                                        <option value="{{ $advanceType->id }}" {{ old('loan_head_id') }}>{{ $advanceType->type_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- <div class=" col-md-3">
                                                <label for="">Salary Head</label>
                                                <select name="sal_block_id" id="sal_block_id" class="form-control" required>
                                                    <option value="">--SELECT--</option>
                                                    @foreach($salaryheads as $salaryhead)
                                                        <option value="{{ $salaryhead->id }}" {{ old('sal_block_id') == $salaryhead->id ? 'selected' : '' }}>{{ $salaryhead->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div> -->

                                        </div>
                                        <hr>
                                        <div class="form-group row">
                                            <div class=" col-md-2">
                                                <label for="reference_no">Application No</label>
                                                <input type="text" name="reference_no" id="reference_no" class="form-control"  value="" required>
                                            </div>
                                            <div class=" col-md-3">
                                                <label for="loan_amount">Loan Amount</label>
                                                <input type="number" name="loan_amount" id="loan_amount" class="form-control"  value="0" required>
                                            </div>
                                            <div class=" col-md-2">
                                                <label for="loan_interest_rate">Interest Rate</label>
                                                <input type="number" name="loan_interest_rate" id="loan_interest_rate" class="form-control" step="0.01" value="{{old('loan_interest_rate') ?? 0}}" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="no_of_installment">No of installment</label>
                                                <input type="number" class="form-control" id="no_of_installment" name="no_of_installment" value="0" onchange="ReducingInt();">
                                            </div>
                                            <div class="col-md-2">
                                                <label for="principal_amount">Principal Amount</label>
                                                <input type="number" class="form-control" id="principal_amount" name="principal_amount" step="0.01"  value="0">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <label for="monthly_emi">Monthly Installment(Principal)</label>
                                                <input type="number" class="form-control" id="monthly_emi" name="monthly_emi" value="0" step="0.01" >
                                            </div>
                                            <div class="col-md-3">
                                                <label for="adj_emi">Adjustable Installment</label>
                                                <input type="number" class="form-control" id="adj_emi" name="adj_emi" value="0" step="0.01" >
                                            </div>
                                            <div class="col-md-3">
                                                <label for="adj_emi_in">Adjust in</label>
                                                <select name="adj_emi_in" id="adj_emi_in" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="f" {{ old('adj_emi_in') == 'f' ? 'selected' : '' }}>First Installment</option>
                                                    <option value="l" {{ old('adj_emi_in') == 'l' ? 'selected' : '' }}>Last Installment</option>
                                                </select>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <label for="interest_amount">Total Interest Amount</label>
                                                <input type="number" name="interest_amount" id="interest_amount" class="form-control" step="0.01" required value="0">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="no_of_installment_interest">No of installment(Interest)</label>
                                                <input type="number" name="no_of_installment_interest" id="no_of_installment_interest" class="form-control" required value="0" onchange="calculatePrincipalAmount();">
                                            </div>
                                            <div class="col-md-2">
                                                <label for="interest_installment">Interest Installment</label>
                                                <input type="number" name="interest_installment" id="interest_installment" class="form-control" step="0.01" required value="0">
                                            </div>
                                            <div class="col-md-2">
                                                <label for="adj_interest_emi">Adjustable Interest Installment</label>
                                                <input type="number" name="adj_interest_emi" id="adj_interest_emi" class="form-control" step="0.01" required value="0">
                                            </div>
                                            <div class="col-md-2">
                                                <label for="adj_interest_emi_in">Adjust in</label>
                                                <select name="adj_interest_emi_in" id="adj_interest_emi_in" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="f" {{ old('adj_interest_emi_in') == 'f' ? 'selected' : '' }}>First Installment</option>
                                                    <option value="l" {{ old('adj_interest_emi_in') == 'l' ? 'selected' : '' }}>Last Installment</option>
                                                </select>
                                            </div>
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
                                                    @for ($year = 2025; $year <= 2035; $year++)
                                                        <option value="{{ $year }}" {{ old('wef_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mt-5">
                                            <div class="col-md-12" style="text-align: right;">
                                                <button type="submit" class="btn btn-primary btn-sm float-right">Submit</button>
                                            </div>
                                        </div>
                                    
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
@endsection
@section('js')
<script>
function calculatePrincipalAmount() {
    var loanAmount = parseFloat(document.getElementById('loan_amount').value) || 0;
    var interestRate = parseFloat(document.getElementById('loan_interest_rate').value) || 0;
    var numInstallments = parseInt(document.getElementById('no_of_installment').value) || 0;

    if (loanAmount > 0 && interestRate > 0 && numInstallments > 0) {
        document.getElementById('principal_amount').value = document.getElementById('loan_amount').value;
        var emi = Math.floor(loanAmount/numInstallments);
        document.getElementById('monthly_emi').value = emi.toFixed(0);

        var totalInterest = Math.floor(numInstallments*((numInstallments+1)/2)*(emi/12)*(interestRate/100));
        document.getElementById('interest_amount').value = totalInterest;

        var interestInstallment = Math.floor(totalInterest / numInstallments);
        // interestInstallment = interestInstallment.toFixed(0);
        // document.getElementById('interest_installment').value = interestInstallment;

        var adjEmi = parseFloat(document.getElementById('adj_emi').value) || 0;
        var f_installment = interestInstallment / numInstallments;
        var adjustedEmi = loanAmount -( emi * (numInstallments-1));
        document.getElementById('adj_emi').value = adjustedEmi.toFixed(0);

        var noOfInstallmentsInterest = parseInt(document.getElementById('no_of_installment_interest').value) || 0;
        if (noOfInstallmentsInterest > 0) {
            var interestInstallment = Math.floor(totalInterest/noOfInstallmentsInterest);
            document.getElementById('interest_installment').value = interestInstallment;
            var adjInterestEmi = totalInterest-(interestInstallment * (noOfInstallmentsInterest-1));
            document.getElementById('adj_interest_emi').value = Math.floor(adjInterestEmi);
        } else {
            document.getElementById('interest_installment').value = '0';
            document.getElementById('adj_interest_emi').value = '0';
        }
    } else {
        document.getElementById('monthly_emi').value = '0';
        document.getElementById('interest_amount').value = '0';
        document.getElementById('interest_installment').value = '0';
        document.getElementById('adj_emi').value = '0';
    }
}

document.getElementById('loan_amount').addEventListener('input', calculatePrincipalAmount);
document.getElementById('loan_interest_rate').addEventListener('input', calculatePrincipalAmount);
document.getElementById('no_of_installment').addEventListener('input', calculatePrincipalAmount);
document.getElementById('interest_amount').addEventListener('input', calculateInterestEMIAdjustment);
document.getElementById('no_of_installment_interest').addEventListener('change', calculateInterestEMIAdjustment);


function ReducingInt()
{
    var selectElement = document.getElementById("loan_head_id");
    // Get the selected option
    var selectedOption = selectElement.options[selectElement.selectedIndex];
    // Get the text of the selected option
    var selectedText = selectedOption.text;
    // alert(selectedText);
    if(selectedText != "New House Buiding Advance")
    {
        // alert("Please enter no of installment interest");
        return false;
    }
    var p=document.getElementById("loan_amount").value;
    var n=document.getElementById("no_of_installment").value;
    var r=document.getElementById("loan_interest_rate").value;

var REMI = Math.abs(parseFloat(PMT(((r/100)/12), n, p))); //output */
document.getElementById('monthly_emi').value = Math.round(REMI);
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
var adjustedEmi = parseFloat(Math.round(REMI)+(parseFloat(totalRPayable)-(Math.round(REMI)*n)));
document.getElementById('adj_emi').value = Math.round(adjustedEmi);
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

    $("form").on("submit", function(e) {
        var data = collectTableData();
        $('#tableData').val(JSON.stringify(data));
    });
</script>

<script>
    $(document).ready(function() {
        $('#loan_head_id').change(function() {
            var loanTypeId = $(this).val();
            if (loanTypeId) {
                $.ajax({
                    url: "{{ route('loan.checkLoanType') }}",
                    method: 'GET',
                    data: { loan_type_id: loanTypeId },
                    success: function(response) {
                        if (response.is_reducing) {
                            $('#interest_amount').prop('disabled', true).val('');
                            $('#no_of_installment_interest').prop('disabled', true).val('');
                            $('#interest_installment').prop('disabled', true).val('');
                            $('#adj_interest_emi').prop('disabled', true).val('');
                            $('#adj_interest_emi_in').prop('disabled', true).val('');
                            $('#interest_amount').hide();
                            $('#no_of_installment_interest').hide();
                            $('#interest_installment').hide();
                            $('#adj_interest_emi').hide();
                            $('#adj_interest_emi_in').hide();
                            $('#wef_month').hide();
                            $('#wef_year').hide();
                        } else {
                            $('#interest_amount').prop('disabled', false);
                            $('#no_of_installment_interest').prop('disabled', false);
                            $('#interest_installment').prop('disabled', false);
                            $('#adj_interest_emi').prop('disabled', false);
                            $('#adj_interest_emi_in').prop('disabled', false);
                            $('#interest_amount').show();
                            $('#no_of_installment_interest').show();
                            $('#interest_installment').show();
                            $('#adj_interest_emi').show();
                            $('#adj_interest_emi_in').show();
                            $('#wef_month').show();
                            $('#wef_year').show();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("There was an error checking the loan type: " + error);
                    }
                });
            } else {
                $('#loan_amount').prop('disabled', false);
            }
        });

        $('#loan_head_id').trigger('change');
    });
</script>
@endsection
