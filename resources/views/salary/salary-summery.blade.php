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
                                            <h4>Salary Summery For
                                                {{ \Carbon\Carbon::createFromDate(null, $curr_salary_blk->month)->format('F') }}/{{ $curr_salary_blk->year }}
                                            </h4>
                                            <div class="static-table-list">
                                                <table class="table table-bordered " id="dtExample">
                                                    <thead>
                                                        <tr>
                                                            <th>Emp Name</th>
                                                            <th>Emp Code</th>
                                                            
                                                            @foreach ($salary_head as $hd)
                                                                <th>{{$hd->code}}</th>
                                                            @endforeach
                                                            <!-- <th>Gross</th>
                                                            <th>Deduction</th> -->
                                                            <th>Net</th>
                                                            <th>Pay Slip</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($employee as $key => $emp)
                                                            @php
                                                                $ind_gross = $emp->grossSalary($view_salary_block);
                                                                $ind_deduct = $emp->deductSalary($view_salary_block);
                                                                $indi_net = $ind_gross - $ind_deduct;
                                                            @endphp
                                                            <tr>
                                                                <td>{{ $emp->name }}</td>
                                                                <td>{{ $emp->emp_code }}</td>
                                                                @foreach ($salary_head as $hd)
                                                                    <th>{{$emp->getHeadAmount($view_salary_block,$hd->id)}}</th>
                                                                @endforeach
                                                                <!-- <td>{{ $ind_gross }}</td>
                                                                <td>{{ $ind_deduct }}</td> -->
                                                                <td>{{ $indi_net }}</td>
                                                                <td><a
                                                                        href="{{ route('payslip', ['id' => Crypt::encrypt($emp->id), 'sl_blk' => $view_salary_block]) }}">View</a>
                                                                </td>
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
            new DataTable('#dtExample', {
                layout: {
                    topStart: {
                        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                    }
                }
            });
        </script>
    @endsection
