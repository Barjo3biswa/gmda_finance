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
        }

        /* .pay_cut {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            background-color: rgba(250, 0, 0, 0.7);
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        } */
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
                                <li><span class="bread-blod">Pay Cut</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="product-payment-inner-st">
                        <div id="myTabContent" class="tab-content custom-product-edit">
                            <h4>Pay Cut Management</h4>
                            <div class="product-tab-list tab-pane fade active in" id="description">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="sparkline8-hd col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="main-sparkline8-hd">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('save-pay-cut', $id) }}" class="btn btn-primary btn-xs"
                                    onclick="return confirm('Are you sure you want to Save? No Edit Will be allowd after Saved.');">Save
                                    &
                                    Proceed</a>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover" id="dtExample">
                                                    <thead>
                                                        <tr>
                                                            <th>Emp Name</th>
                                                            <th>Pay Cut Days</th>
                                                            <th>Gross</th>
                                                            <th>Deduction</th>
                                                            @foreach ($salary_head as $hd)
                                                                <th width="5%">{{ $hd->code }}</th>
                                                            @endforeach
                                                            <th width="5%">Net(with pay cut)</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($employee as $key => $emp)
                                                            @php
                                                                $ind_gross = $emp->grossSalary(
                                                                    $view_salary_block,
                                                                    'draft',
                                                                );
                                                                $ind_deduct = $emp->deductSalary(
                                                                    $view_salary_block,
                                                                    'draft',
                                                                );
                                                                $indi_net = $ind_gross - $ind_deduct;
                                                                $class = '';
                                                                if ($indi_net < 0) {
                                                                    $class = 'td_danger';
                                                                }
                                                            @endphp
                                                            <tr>
                                                                <td class="{{ $class }}">
                                                                    {{ $emp->name }}({{ $emp->emp_code }})</td>
                                                                <td class="{{ $class }}">
                                                                    {{ $attendance->days_in_month - $all_sal_head[0]->totaldays($all_sal_head[0]->id, $view_salary_block, $emp->id) }}
                                                                </td>
                                                                <td class="{{ $class }}">{{ $ind_gross }}</td>
                                                                <td class="{{ $class }}">{{ $ind_deduct }}</td>
                                                                @foreach ($salary_head as $hd)
                                                                    @php
                                                                        $amount = $emp->getHeadAmount(
                                                                            $view_salary_block,
                                                                            $hd->id,
                                                                        );
                                                                        $pay_cut = '';
                                                                        if ($hd->pay_cut_hd == 1 && $amount > 0) {
                                                                            $pay_cut = 'pay_cut';
                                                                        }
                                                                    @endphp
                                                                    <td class="{{ $class }} {{ $pay_cut }}">
                                                                        {{ $amount }}
                                                                    </td>
                                                                @endforeach
                                                                <td class="{{ $class }}">{{ $indi_net }}</td>
                                                                <td class="{{ $class }}">
                                                                    @php
                                                                        $flag = \App\Helpers\commonHelper::checkFlag(
                                                                            $pay_cut_head->id,
                                                                            $emp->id,
                                                                        );
                                                                    @endphp
                                                                    <a
                                                                        href="{{ route('payslip', ['id' => Crypt::encrypt($emp->id), 'sl_blk' => $view_salary_block]) }}"><i
                                                                            class="fa fa-eye"></i></a>
                                                                    @if ($flag == false)
                                                                        <a onclick="return confirm('Are you sure you Include pay cut amount in salary?');"
                                                                            href="{{ route('include-exclude', ['hd_id' => $pay_cut_head->id, 'emp_id' => $emp->id]) }}"
                                                                            class="btn btn-primary btn-xs">Include</a>
                                                                    @else
                                                                        <a onclick="return confirm('Are you sure you Exclude pay cut amount in salary?');"
                                                                            href="{{ route('include-exclude', ['hd_id' => $pay_cut_head->id, 'emp_id' => $emp->id]) }}"
                                                                            class="btn btn-danger btn-xs">Exclude</a>
                                                                    @endif
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
            // Initialize DataTable
            new DataTable('#dtExample', {
                pageLength: 150,
                layout: {
                    topStart: {
                        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                    }
                },
            });
        </script>
    @endsection
