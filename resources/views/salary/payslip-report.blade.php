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
                <div class="row">
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
                </div>
                <div class="product-payment-inner-st">
                    <div id="myTabContent" class="tab-content custom-product-edit">
                        <h4>Salary Management</h4>
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
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        @php
                                            $curr_salary_blk = $salary_block
                                                ->where('id', $view_salary_block)
                                                ->first();
                                        @endphp
                                        <h4>Employee List
                                            {{-- {{ \Carbon\Carbon::createFromDate(null,
                                            $curr_salary_blk->month)->format('F') }}/{{ $curr_salary_blk->year }} --}}
                                        </h4>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover" id="dtExample">
                                                <thead>
                                                    <tr>
                                                        <th>SL</th>
                                                        <th>Emp Name</th>
                                                        <th>Designation</th>
                                                        <th>IFSC Code</th>
                                                        <th>A/C No</th>
                                                        <th>Net Pay(Rs)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($salary_master as $key => $sal)
                                                        <tr>
                                                            <th>{{ ++$key }}</th>
                                                            <th>{{ $sal->user->name }}</th>
                                                            <th>{{ $sal->employee->designation->name ?? "NA" }}</th>
                                                            <th>{{ $sal->employee->bank_ifsc_no ?? 'NA' }}</th>
                                                            <th>{{ $sal->employee->bank_ac_no ?? "NA" }}</th>
                                                            <th>{{ $sal->net }}</th>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
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
        // Initialize DataTable
        new DataTable('#dtExample', {
            pageLength: 150,
            layout: {
                topStart: {
                    buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
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
    @endsection