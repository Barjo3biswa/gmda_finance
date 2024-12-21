@extends('layouts.app')
@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
    <style>
        .alert {
            padding: 0px;
            margin-bottom: 4px;
            border: 1px solid transparent;
            border-radius: 4px;
            font-size: 12px;
        }
    </style>
    <style>
        .custom-scrollbar {
            max-height: 538px;
            overflow-y: auto;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #888;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: #555;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background-color: #f1f1f1;
        }

        .sparkline8-list {
            height: 325px;
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
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="sparkline8-hd col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="main-sparkline8-hd">
                                                    <form action="">
                                                        <div class="row">
                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                <div class="form-group">
                                                                    <label for="name">Salary Block</label>
                                                                    <select name="sal_block" id="sal_block"
                                                                        class="form-control">
                                                                        <option value="">--select--</option>
                                                                        @foreach ($salary_block as $blok)
                                                                            <option value="{{ $blok->id }}"
                                                                                {{ $view_salary_block == $blok->id ? 'selected' : '' }}>
                                                                                {{ \Carbon\Carbon::createFromDate(null, $blok->month)->format('F') }}/{{ $blok->year }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"
                                                                style="display: flex">
                                                                <div class="form-group">
                                                                    <input type="hidden" name="view" value="process">
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
                                        <div class="row">
                                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                                                <div class="static-table-list custom-scrollbar">
                                                    <table class="table table-bordered ">
                                                        <thead>
                                                            <tr>
                                                                <th colspan='2'>
                                                                    <h6>Process Steps</h6>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($process_steps as $step)
                                                                <tr>
                                                                    <th>{{ $step->name }}</th>
                                                                    @if ($is_editable_flag)
                                                                        <th>
                                                                            @if ($step->status == 'underprocess')
                                                                                <a href="{{ route($step->route, $step->id) }}"
                                                                                    class="btn btn-primary btn-xs"
                                                                                    onclick="return confirm('Are you sure you want Process?');">Process</a>
                                                                            @else
                                                                                <a href="{{ route($step->route, $step->id) }}"
                                                                                    class="btn btn-warning btn-xs"
                                                                                    onclick="return confirm('Are you sure you want Process?');">Reprocess</a>
                                                                            @endif

                                                                        </th>
                                                                    @else
                                                                        <th><a href="#"
                                                                                class="btn btn-info btn-xs">Completed</a>
                                                                        </th>
                                                                    @endif

                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="row">
                                            @php
                                                $curr_salary_blk = $salary_block
                                                    ->where('id', $view_salary_block)
                                                    ->first();
                                            @endphp
                                            <h4>Salary Outstanding For
                                                {{ \Carbon\Carbon::createFromDate(null, $curr_salary_blk->month)->format('F') }}/{{ $curr_salary_blk->year }}
                                            </h4>
                                            @php
                                                $income = 0;
                                                $deduction = 0;
                                            @endphp
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th colspan=3>
                                                                <h4>Income</h4>
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th>Head</th>
                                                            <th>Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($salary_head->where('pay_head', 'Income') as $hd)
                                                            <tr>
                                                                <td>{{ $hd->name }}</td>
                                                                <td>{{ $hd->TempAmountTotal($hd->id, $view_salary_block) }}
                                                                </td>
                                                            </tr>
                                                            @php
                                                                $income =
                                                                    $income +
                                                                    $hd->TempAmountTotal($hd->id, $view_salary_block);
                                                            @endphp
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th colspan=3>
                                                                <h4>Deduction</h4>
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th>Head</th>
                                                            <th>Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($salary_head->where('pay_head', 'Deduction') as $hd)
                                                            <tr>
                                                                <td>{{ $hd->name }}</td>
                                                                <td>{{ $hd->TempAmountTotal($hd->id, $view_salary_block) }}
                                                                </td>
                                                            </tr>
                                                            @php
                                                                $deduction =
                                                                    $deduction +
                                                                    $hd->TempAmountTotal($hd->id, $view_salary_block);
                                                            @endphp
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Income Total</th>
                                                            <th>{{ $income }}</th>
                                                        </tr>
                                                        <tr>
                                                            <th>Deduction Total</th>
                                                            <th>{{ $deduction }}</th>
                                                        </tr>
                                                        <tr>
                                                            <th>Net Salary Total</th>
                                                            <th>{{ $income - $deduction }}</th>
                                                        </tr>
                                                    </thead>
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
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>
        {{-- <script>
        $(document).ready(function() {
            $('#dtExample').DataTable();
        })
    </script> --}}
    @endsection
