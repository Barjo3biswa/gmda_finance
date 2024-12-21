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
                                <li><span class="bread-blod">Salary Process</span> <span class="bread-slash">/</span>
                                </li>
                                <li><span class="bread-blod">Pay Slip</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="product-payment-inner-st">
                        <div id="myTabContent" class="tab-content custom-product-edit">
                            <h4>Pay Slip (Indivisual)</h4>
                            <div class="product-tab-list tab-pane fade active in" id="description">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="main-sparkline8-hd">
                                                    <form action="">
                                                        <div class="row">
                                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
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
                                                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                                                                <div class="form-group">
                                                                    <input type="submit" name="button" value="Filter"
                                                                        class="btn btn-primary btn-xs"
                                                                        style="margin-top: 25px;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="row">
                                                    @php
                                                        $curr_salary_blk = $salary_block
                                                            ->where('id', $view_salary_block)
                                                            ->first();
                                                    @endphp
                                                    <h4>Salary Slip For
                                                        {{ \Carbon\Carbon::createFromDate(null, $curr_salary_blk->month)->format('F') }}/{{ $curr_salary_blk->year }}
                                                    </h4>
                                                </div>
                                                <div class="main-sparkline8-hd">
                                                    <form action="{{ route('reduce-working-days') }}" method="post">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                                                <div class="form-group">
                                                                    <label for="name">Pay Cut Days Count</label>
                                                                    <input type="number" class="form-control"
                                                                        name="pay_cut_day"
                                                                        value={{ $attendance->days_in_month - $salary_head[0]->totaldays($salary_head[0]->id, $view_salary_block, $emp_id) }}>
                                                                </div>
                                                            </div>
                                                            @if ($is_editable_flag)
                                                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                                                                <div class="form-group">
                                                                    <input type="hidden" name="emp_id"
                                                                        value="{{ $emp_id }}">
                                                                    <input type="hidden" name="sal_block_id"
                                                                        value="{{ $view_salary_block }}">
                                                                    <input type="submit" value="Apply"
                                                                        class="btn btn-primary btn-xs"
                                                                        style="margin-top: 25px;">
                                                                </div>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                    <h5>Emp Name: {{$emp_details->name}}</h5>
                                                    <h5>Emp Code: {{$emp_details->emp_code}}</h5>
                                                    <h5>Present Count: {{$attendance->present_count ??"NA"}}</h5>
                                                    <h5>Leave Count: {{$attendance->leave_count ??"NA"}}</h5>
                                                    <h5>Half Day Count: {{$attendance->hd_count ??"NA"}}</h5>
                                                    <h5>Absent Count: {{$attendance->absent_count ??"NA"}}</h5>
                                            </div>
                                        </div>                                        
                                        <div class="row">
                                            @php
                                                $income = 0;
                                                $deduction = 0;
                                            @endphp
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th colspan={{ $is_editable_flag ? 3 : 2 }}>
                                                                <h4>Income</h4>
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th>Head</th>
                                                            <th>Amount</th>
                                                            @if ($is_editable_flag)
                                                                <th>Action</th>
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($salary_head->where('pay_head', 'Income') as $hd)
                                                            <tr>
                                                                <td>{{ $hd->name }}</td>
                                                                <td>{{ $hd->TempAmount($hd->id, $view_salary_block, $emp_id) }}
                                                                </td>
                                                                @if ($is_editable_flag)
                                                                    <td>
                                                                        <button type="button" data-toggle="modal"
                                                                            data-target="#exampleModalCenter"
                                                                            onclick="appenFunction({{ $hd->id }}, {{ $view_salary_block }}, {{ $emp_id }})">
                                                                            Edit
                                                                        </button>
                                                                    </td>
                                                                @endif
                                                            </tr>
                                                            @php
                                                                $income =
                                                                    $income +
                                                                    $hd->TempAmount(
                                                                        $hd->id,
                                                                        $view_salary_block,
                                                                        $emp_id,
                                                                    );
                                                            @endphp
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th colspan={{ $is_editable_flag ? 3 : 2 }}>
                                                                <h4>Deduction</h4>
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th>Head</th>
                                                            <th>Amount</th>
                                                            @if ($is_editable_flag)
                                                                <th>Action</th>
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($salary_head->where('pay_head', 'Deduction') as $hd)
                                                            <tr>
                                                                <td>{{ $hd->name }}</td>
                                                                <td>{{ $hd->TempAmount($hd->id, $view_salary_block, $emp_id) }}
                                                                </td>
                                                                @if ($is_editable_flag)
                                                                    <td>
                                                                        <button type="button" data-toggle="modal"
                                                                            data-target="#exampleModalCenter"
                                                                            onclick="appenFunction({{ $hd->id }}, {{ $view_salary_block }}, {{ $emp_id }})">
                                                                            Edit
                                                                        </button>
                                                                    </td>
                                                                @endif
                                                            </tr>
                                                            @php
                                                                $deduction =
                                                                    $deduction +
                                                                    $hd->TempAmount(
                                                                        $hd->id,
                                                                        $view_salary_block,
                                                                        $emp_id,
                                                                    );
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
                                                            <th>Net Salary</th>
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
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true" style="margin-top: 231px">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="{{ route('update-amount') }}" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Edit Amount</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="hd_id" id="hd_id">
                        <input type="hidden" name="blk_id" id="blk_id">
                        <input type="hidden" name="emp_id" id="emp_id">
                        <label for="">New Amount</label>
                        <input type="number" name="amount" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-xs">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('js')
    <script>
        function appenFunction(hd_id, blk_id, emp_id) {
            $('#hd_id').val(hd_id);
            $('#blk_id').val(blk_id);
            $('#emp_id').val(emp_id);
        }
    </script>
@endsection
