@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 mt-5">


        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 analytics-content-border">
            <div class="analytics-sparkle-line reso-mg-b-30">
                <h6 style="margin-bottom: 8px;"><span class="tuition-fees"
                        style="font-size: 12px;font-weight: 600;color: #bf4c4c;">OVERVIEW
                        PAYCUT</span></h6>
                <div class="analytics-content">
                    <h5>Total Pay Cut</h5>
                    <h2 class="text-primary">₹ {{ number_format($totalpaycut, 2) }}</h2>
                    <div class="m-t-20">
                        <h6>Top 3 Pay Cut Employees</h6>
                        <ul class="list-unstyled">
                            @foreach($paycuts as $paycut)
                                <li>
                                    {{ $paycut->employee->full_name ?? 'N/A' }}:
                                    ₹ {{ number_format($paycut->amount, 2) }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
            <div class="analytics-sparkle-line reso-mg-b-30">
                <div class="analytics-content">
                    <h5>Estimated Income Amount Charts</h5>
                    <canvas id="salaryHeadsChartIncome"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
            <div class="analytics-sparkle-line reso-mg-b-30">
                <div class="analytics-content">
                    <h5>Estimated Deduction Amount Charts</h5>
                    <canvas id="salaryHeadsChartDeduction"></canvas>
                </div>
            </div>
        </div>
    </div>

    @if($isFinalized == 1)
        <div class="row">
            <div class="col-md-12 mt-5">


            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                <div class="analytics-sparkle-line reso-mg-b-30">
                    <div class="analytics-content">
                        <h5>Actual Income Amount Charts</h5>
                        <canvas id="salaryHeadsChartActualIncome"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                <div class="analytics-sparkle-line reso-mg-b-30">
                    <div class="analytics-content">
                        <h5>Actual Deduction Amount Charts</h5>
                        <canvas id="salaryHeadsChartActualDeduction"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                <div class="analytics-sparkle-line reso-mg-b-30">
                    <div class="analytics-content">
                        <h5>Estimated vs Actual Comparison</h5>
                        <canvas id="salaryHeadsChartComparison"></canvas>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Estimated Income Chart
        const estimatedIncomeHeadAmounts = @json($estimatedIncomeHeadAmounts);
        const ctxIncome = document.getElementById('salaryHeadsChartIncome').getContext('2d');
        new Chart(ctxIncome, {
            type: 'bar',
            data: {
                labels: estimatedIncomeHeadAmounts.map(head => head.head_name),
                datasets: [{
                    label: 'Estimated Income Amounts',
                    data: estimatedIncomeHeadAmounts.map(head => head.total_amount),
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                aspectRatio: 1,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Amount'
                        }
                    }
                }
            }
        });

        // Estimated Deduction Chart
        const estimatedDeductionHeadAmounts = @json($esitimatedDeductionHeadAmounts);
        const ctxDeduction = document.getElementById('salaryHeadsChartDeduction').getContext('2d');
        new Chart(ctxDeduction, {
            type: 'bar',
            data: {
                labels: estimatedDeductionHeadAmounts.map(head => head.head_name),
                datasets: [{
                    label: 'Estimated Deduction Amounts',
                    data: estimatedDeductionHeadAmounts.map(head => head.total_amount),
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                aspectRatio: 1,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Amount'
                        }
                    }
                }
            }
        });

        @if($isFinalized == 1)
        // Actual Income Chart
        const actualIncomeHeadAmounts = @json($actualIncomeHeadAmounts);
        const ctxActualIncome = document.getElementById('salaryHeadsChartActualIncome').getContext('2d');
        new Chart(ctxActualIncome, {
            type: 'bar',
            data: {
                labels: actualIncomeHeadAmounts.map(head => head.head_name),
                datasets: [{
                    label: 'Actual Income Amounts',
                    data: actualIncomeHeadAmounts.map(head => head.total_amount),
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                aspectRatio: 1,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Amount'
                        }
                    }
                }
            }
        });

        // Actual Deduction Chart
        const actualDeductionHeadAmounts = @json($actualDeductionHeadAmounts);
        const ctxActualDeduction = document.getElementById('salaryHeadsChartActualDeduction').getContext('2d');
        new Chart(ctxActualDeduction, {
            type: 'bar',
            data: {
                labels: actualDeductionHeadAmounts.map(head => head.head_name),
                datasets: [{
                    label: 'Actual Deduction Amounts',
                    data: actualDeductionHeadAmounts.map(head => head.total_amount),
                    backgroundColor: 'rgba(255, 206, 86, 0.6)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                aspectRatio: 1,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Amount'
                        }
                    }
                }
            }
        });

        // Estimated vs Actual Comparison Chart
        const ctxComparison = document.getElementById('salaryHeadsChartComparison').getContext('2d');
        new Chart(ctxComparison, {
            type: 'bar',
            data: {
                labels: estimatedIncomeHeadAmounts.map(head => head.head_name),
                datasets: [
                    {
                        label: 'Estimated Amounts',
                        data: estimatedIncomeHeadAmounts.map(head => head.total_amount),
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Actual Amounts',
                        data: actualIncomeHeadAmounts.map(head => head.total_amount),
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                aspectRatio: 1,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Amount'
                        }
                    }
                }
            }
        });

        @endif
    });
</script>
@endsection
