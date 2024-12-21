@extends('layouts.app')

@section('content')
<div class="analytics-sparkle-area">
    <div class="container-fluid">



        <div class="col-md-12 mt-5">


        </div>

        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 analytics-content-border">
                <div class="analytics-sparkle-line reso-mg-b-30">
                    <h6 style="margin-bottom: 8px;"><span class="tuition-fees"
                            style="font-size: 12px;font-weight: 600;color: #bf4c4c;">OVERVIEW

                            Total Employees
                           {{ $employees->count() }}, Total Paycuts {{ $totalpaycut }}</span></h6>
                    <div class="row">

                        @foreach($paycuts as $paycut )
                        <div class="col-md-3">
                            <div class="analytics-content">
                                <h5>
                                    {{ $paycut->employee->full_name }}
                                    {{-- {{ $loop->iteration }} --}}
                                </h5>
                                <h2><span class="counter">{{ $paycut->amount }}</span> <span
                                        class="tuition-fees"></span></h2>
                                {{-- <span class="text-info">
                                  {{ $paycut->employee->full_name }}
                                </span>
                                <div class="progress m-b-0">
                                    <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="50"
                                        aria-valuemin="0" aria-valuemax="100" style="width:%;">
                                        <span class="sr-only"></span>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                        @endforeach




                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                <div class="analytics-sparkle-line reso-mg-b-30">
                    <div class="analytics-content">
                        <h5>Income Charts</h5>
                        <canvas id="salaryHeadsChartIncome"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                <div class="analytics-sparkle-line reso-mg-b-30">
                    <div class="analytics-content">
                        <h5>Deduction Charts</h5>
                        <canvas id="salaryHeadsChartDeduction"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const incomeHeadAmounts = @json($incomeHeadAmounts);

        const ctxIncome = document.getElementById('salaryHeadsChartIncome').getContext('2d');
        new Chart(ctxIncome, {
            type: 'bar',
            data: {
                labels: incomeHeadAmounts.map(head => head.head_name),
                datasets: [{
                    label: 'Salary Head Amounts',
                    data: incomeHeadAmounts.map(head => head.total_amount),
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                aspectRatio: 2,
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
    });

    document.addEventListener('DOMContentLoaded', function() {
        const deductionHeadAmounts = @json($deductionHeadAmounts);

        const ctxDeduction = document.getElementById('salaryHeadsChartDeduction').getContext('2d');
        new Chart(ctxDeduction, {
            type: 'bar',
            data: {
                labels: deductionHeadAmounts.map(head => head.head_name),
                datasets: [{
                    label: 'Salary Head Amounts',
                    data: deductionHeadAmounts.map(head => head.total_amount),
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                aspectRatio: 2,
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
    });
    </script>
    @endsection
