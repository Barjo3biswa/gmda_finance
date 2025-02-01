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

    .table-responsive {
        overflow-x: auto;
    }

    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #007bff;
        border-radius: 4px;
    }

    .td_danger {
        background-color: rgba(250, 0, 0, 0.5);
        /* Faded red */
    }

    .pay_cut {
        background-color: rgba(250, 0, 0, 0.7);
        /* Faded red */
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
                            <li><a href="#">Dashboard</a> <span class="bread-slash">/</span>
                            </li>
                            <li><span class="bread-blod">Salary Process</span>
                            </li>
                        </ul>
                    </div>
                </div> --}}
                <div class="product-payment-inner-st">
                    <div id="myTabContent" class="tab-content custom-product-edit">
                        <h4>Payslip View</h4>
                        <div class="product-tab-list tab-pane fade active in" id="description">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="sparkline8-hd col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="main-sparkline8-hd">
                                                <form action="">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                                            <div class="form-group">
                                                                <label for="name">Month</label>
                                                                <select name="month" id="month" class="form-control">
                                                                    <option value="">--select--</option>
                                                                    @for ($i = 1; $i < 13; $i++)
                                                                        <option value="{{ $i }}">
                                                                            {{ \Carbon\Carbon::createFromDate(null, $i, 1)->format('F') }}
                                                                        </option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                                            <div class="form-group">
                                                                <label for="name">Year</label>
                                                                <select name="year" id="year" class="form-control">
                                                                    <option value="">--select--</option>
                                                                    @for ($j = 2025; $j <= 2030; $j++)
                                                                        <option value="{{ $j }}">{{ $j }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"
                                                            style="display: flex">
                                                            <div class="form-group">
                                                                <input type="submit" name="button" value="Filter"
                                                                    class="btn btn-primary btn-xs"
                                                                    style="margin-top: 25px; margin-left: 10px;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
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
    </div>
    @endsection
    @section('js')
    <script>
        const checkAllBox = document.getElementById('checkAll');
        const checkboxes = document.querySelectorAll('.date');
        checkAllBox.addEventListener('change', function () {
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                checkAllBox.checked = Array.from(checkboxes).every(cb => cb.checked);
            });
        });

        // Initialize DataTable
        new DataTable('#dtExample', {
            pageLength: 150,
            layout: {
                topStart: {
                    //buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                    buttons: []
                }
            },
            footerCallback: function (row, data, start, end, display) {
                var api = this.api();

                // Helper function to convert string to numeric value
                var intVal = function (i) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ? i : 0;
                };

                // Iterate over each column to calculate sums
                api.columns().every(function (index) {
                    // Adjust the range to include only summable columns (e.g., salary heads and net)
                    if (index > 1 && index < api.columns().count() - 1) {
                        var total = api
                            .column(index, {
                                page: 'current'
                            }) // Use 'current' for visible rows
                            .data()
                            .reduce(function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        // Update the footer with the calculated total
                        $(api.column(index).footer()).html(total.toLocaleString());
                    }
                });
            }
        });
    </script>

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