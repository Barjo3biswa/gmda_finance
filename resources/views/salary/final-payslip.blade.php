@extends('layouts.app')
@section('css')
<style>
    .alert {
        padding: 0px;
        margin-bottom: 4px;
        border: 1px solid transparent;
        border-radius: 4px;
        font-size: 12px;
    }

    .heading {
        font-weight: bold;
    }

    hr {
        margin: 0rem 0 !important;
    }

    @media print {
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .alert,
        .navbar,
        .footer,
        .no-print {
            display: none;
            /* Hide unnecessary elements like navbars and footers */
        }

        .container {
            margin: 0 auto;
            width: 100%;
            max-width: 100%;
        }

        .heading {
            font-weight: bold;
        }

        hr {
            margin: 10px 0;
        }

        .btn {
            display: none;
            /* Hide buttons */
        }
    }
</style>
@endsection
@section('content')
<div class="single-pro-review-area mt-t-30 mg-b-15">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                {{-- <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="breadcome-heading">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <ul class="breadcome-menu">
                            <li><a href="#">Dashboard</a> <span class="heading" class="bread-slash">/</span>
                            </li>
                            <li><span class="heading" class="bread-blod">Salary Process</span> <span class="heading"
                                    class="bread-slash">/</span>
                            </li>
                            <li><span class="heading" class="bread-blod">Pay Slip</span>
                            </li>
                        </ul>
                    </div>
                </div> --}}
                <div class="product-payment-inner-st">
                    <div id="myTabContent" class="tab-content custom-product-edit">
                        <div class="product-tab-list tab-pane fade active in" id="description">
                            <div class="row">
                                <div class="col-md-2">
                                </div>
                                <div class="col-md-8" style="display: flex;justify-content: end;">
                                    <button class="btn btn-primary btn-xs"
                                        onclick="printDiv('printableArea')">Print</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                </div>
                                <div class="col-md-8" id="printableArea">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="row" style="display: flex; justify-content: center;">
                                                <img class="main-logo" src="{{ asset('logo/logo.png') }}" alt=""
                                                    style="max-width: 120px;" />
                                            </div>
                                            <div class="row"
                                                style="display: flex; justify-content: center; text-align: center;">
                                                <h5>Guwahati Metropolitan Development Authority</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <h5>PAY SLIP for
                                                {{ \Carbon\Carbon::createFromDate(null, $salary_block->month)->format('F') }},
                                                {{ $salary_block->year }}
                                                Financial Year:
                                                {{ $salary_block->month >= 4 ? $salary_block->year . '-' . ($salary_block->year + 1) : ($salary_block->year - 1) . '-' . $salary_block->year }}
                                            </h5>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-6"><span class="heading">Emp Code:</span>
                                                    {{ $emp_details->emp_code }}</div>
                                                <div class="col-md-6"><span class="heading">Name:</span>
                                                    {{ $emp_details->name }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6"><span class="heading">Desig:</span>
                                                    {{ $emp_details->employee->designation->name ?? 'NA'}}
                                                </div>
                                                <div class="col-md-6"><span class="heading">Dept.:</span>
                                                    {{ $emp_details->employee->department->name ?? 'NA'}}</div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6"><span class="heading">Bank A/C No.:</span>
                                                    {{ $emp_details->employee->bank_ac_no ?? "NA"}}
                                                </div>
                                                <div class="col-md-6"><span class="heading">PF A/C No.:</span>
                                                    {{ $emp_details->employee->pf_no ?? "NA"}}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <span class="heading">PAN:</span>
                                                    {{ $emp_details->employee->pan_no ?? "NA"}},
                                                    <span class="heading">PRAN:</span>
                                                    {{ $emp_details->employee->pran_no ?? "NA"}}
                                                </div>
                                                <div class="col-md-6"><span class="heading">Email Address:</span>
                                                    {{ $emp_details->email ?? "NA"}}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <hr>
                                        <div class="col-md-6">
                                            <div class="col-md-12">
                                                <span class="heading">Claims</span>
                                            </div>
                                            @foreach ($claims as $cl)
                                                <div class="col-md-12"
                                                    style="display: flex;justify-content: space-between;">
                                                    <span>{{ $cl->salary_head_name }}</span><span>{{ $cl->amount }}</span>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="col-md-6">
                                            <div class="col-md-12">
                                                <span class="heading">Deductions</span>
                                            </div>
                                            @foreach ($deductions as $cl)
                                                <div class="col-md-12"
                                                    style="display: flex;justify-content: space-between;">
                                                    <span>{{ $cl->salary_head_name }}</span><span>{{ $cl->amount }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="row">
                                        <hr>
                                        <div class="col-md-12">
                                            <div class="col-md-2">
                                                <span class="heading">Total:</span>
                                            </div>
                                            <div class="col-md-4" style="display: flex;justify-content: end;">
                                                <span class="heading">{{ $salary->gross }}</span>
                                            </div>
                                            <div class="col-md-6" style="display: flex;justify-content: end;">
                                                <span class="heading">{{ $salary->deduction }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <hr>
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                            </div>
                                            <div class="col-md-6" style="display: flex;justify-content: space-between;">
                                                <span class="heading">NET PAY:</span>
                                                <span class="heading">{{ $salary->net }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <span>Amount in words:</span>
                                        <span class="heading">
                                            {{ ucfirst(collect(explode(' ', NumberFormatter::create('en', NumberFormatter::SPELLOUT)->format($salary->net)))
    ->map(fn($word) => ucfirst($word))
    ->join(' ')) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->

        @endsection
        @section('js')
        <script>
            function printDiv(divName) {
                // Get the content of the specified div
                var printContents = document.getElementById(divName).innerHTML;

                // Open a new window
                var printWindow = window.open('', '_blank', 'height=600,width=800');

                // Write the HTML structure into the new window
                printWindow.document.write(`
        <html>
            <head>
                <title>Print Payslip</title>
                <link rel="stylesheet" href="styles.css"> <!-- Include your stylesheets -->
                <style>
                    @media print {
                        body {
                            font-family: Arial, sans-serif;
                            margin: 0;
                            padding: 0;
                        }
                        .btn, .no-print {
                            display: none; /* Hide buttons */
                        }
                        .container {
                            width: 100%;
                            margin: 0 auto;
                        }
                    }
                </style>
            </head>
            <body>
                ${printContents}
            </body>
        </html>
    `);

                // Close the document to ensure all content is loaded
                printWindow.document.close();

                // Trigger the print dialog after the new window loads
                printWindow.onload = function () {
                    printWindow.print();
                    printWindow.close();
                };
            }


        </script>
        @endsection