@extends('layouts.app')
@section('content')
<div class="container" style="margin-bottom: 100px; margin-top: 50px;">

    <h4>Advance/Loan Details</h4>
    
    <div class="card">
        <div class="card-header">
            <h5>Reference No: {{ $loanMaster->reference_no }}</h5>
        </div>
        <div class="card-body">
        <div class="row mb-4" style="margin-top: 20px; margin-bottom: 20px;">
        <div class="col-md-12">
            <h5 style="border-bottom: 1px solid #000; width: auto; display: inline-block; margin-right: 20px;">Employee Details:</h5>
            <table style="width: auto; width: 80%;">
                <tr>
                    <td><strong>Name:</strong> {{ $loanMaster->employee->first_name }} {{ $loanMaster->employee->last_name }}</td>
                    <td><strong>Employee Code:</strong> {{ $loanMaster->emp_code }}</td>
                    <td><strong>Basic Pay:</strong> {{ $loanMaster->employee->basic_pay }}</td>
                    <td><strong>Grade Pay:</strong> {{ $loanMaster->employee->grade_pay }}</td>
                </tr>
            </table>
        </div>
        </div>
            <table class="table" style="width: 60%;">
                <tr>
                    <th>Loan Type</th>
                    <td>{{ $loanMaster->advanceType->type_name }}</td>
                </tr>
                <tr>
                    <th>Loan Amount</th>
                    <td>{{ number_format($loanMaster->loan_amount, 2) }}</td>
                </tr>
                <tr>
                    <th>Interest Rate</th>
                    <td>{{ $loanMaster->loan_interest_rate }}%</td>
                </tr>
                <tr>
                    <th>Outstanding Principal</th>
                    <td>{{ number_format($loanMaster->outstanding_principal, 2) }}</td>
                </tr>
                <tr>
                    <th>No. of Installments</th>
                    <td>{{ $loanMaster->no_of_installment }}</td>
                </tr>
                <tr>
                    <th>Monthly Installment</th>
                    <td>{{ number_format($loanMaster->monthly_emi, 2) }}</td>
                </tr>
                <tr>
                    <th>Adjustable Installment</th>
                    <td>{{ number_format($loanMaster->adj_emi, 2) }}</td>
                </tr>
                <tr>
                    <th>Adjustable Installment In</th>
                    <td>{{ number_format($loanMaster->adj_emi_in) }}</td>
                </tr>
                <tr>
                    <th>Interest Amount</th>
                    <td>{{ number_format($loanMaster->interest_amount, 2) }}</td>
                </tr>
                <tr>
                    <th>Outstanding Interest Amount</th>
                    <td>{{ number_format($loanMaster->outstanding_interest_amount, 2) }}</td>
                </tr>
                <tr>
                    <th>Interest Installment</th>
                    <td>{{ number_format($loanMaster->interest_installment, 2) }}</td>
                </tr>
                <tr>
                    <th>Adujstable Interest Installment</th>
                    <td>{{ number_format($loanMaster->adj_interest_emi, 2) }}</td>
                </tr>
                <tr>
                    <th>Installment Month</th>
                    <td>{{ number_format($loanMaster->adj_interest_emi, 2) }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ $loanMaster->temp_status }}</td>
                </tr>
            </table>

            <a href="{{ route('advance.index') }}" class="btn btn-primary btn-xs">Back to List</a>
        </div>
    </div>
</div>
    
@endsection