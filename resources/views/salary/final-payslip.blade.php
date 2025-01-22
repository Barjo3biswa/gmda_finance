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
                {{-- <div class="row">
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
                </div> --}}
                <div class="product-payment-inner-st">
                    <div id="myTabContent" class="tab-content custom-product-edit">
                        <h5>Pay Slip</h5>
                        <div class="product-tab-list tab-pane fade active in" id="description">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="main-sparkline8-hd">

                                            </div>
                                            <div class="row">
                                                <h4>Pay Slip For
                                                    {{-- {{ \Carbon\Carbon::createFromDate(null,
                                                    $curr_salary_blk->month)->format('F') }}/{{ $curr_salary_blk->year
                                                    }} --}}
                                                </h4>
                                            </div>
                                            <div class="main-sparkline8-hd">

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h5>Emp Name: {{ $emp_details->name }}</h5>
                                            <h5>Emp Code: {{ $emp_details->emp_code }}</h5>
                                            <h5>Present Count: {{ $attendance->present_count ?? 'NA' }}</h5>
                                            <h5>Leave Count: {{ $attendance->leave_count ?? 'NA' }}</h5>
                                            <h5>Half Day Count: {{ $attendance->hd_count ?? 'NA' }}</h5>
                                            <h5>Absent Count: {{ $attendance->absent_count ?? 'NA' }}</h5>
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
                                                        <th colspan=2>
                                                            <h4>Income</h4>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>Head</th>
                                                        <th>Amount</th>
                                                    </tr>
                                                </thead>

                                            </table>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th colspan=2>
                                                            <h4>Deduction</h4>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>Head</th>
                                                        <th>Amount</th>
                                                    </tr>
                                                </thead>

                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        {{-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
                                        </div> --}}
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