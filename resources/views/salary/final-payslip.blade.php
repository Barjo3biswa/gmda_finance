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

    .product-payment-inner-st span {
        padding: 4px 0;

    }

    .header hr {
        margin-bottom: 8px !important;
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
                                    {{-- <button class="btn btn-primary btn-xs"
                                        onclick="printDiv('printableArea')">Print</button> --}}
                                    <a class="btn btn-primary btn-xs"
                                        href="{{ route('download-all-payslip', ['id' => Crypt::encrypt($salary_block->id), 'emp_id' => $emp_details->id]) }}">
                                        Download
                                    </a>
                                </div>
                            </div>
                            <div class="row" id="printableArea">
                                @include('salary.common-payslip')
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