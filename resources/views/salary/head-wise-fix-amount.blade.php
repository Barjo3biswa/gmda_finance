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
                                <li><span class="bread-blod">Head Wise Amount</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="product-payment-inner-st">
                        <div id="myTabContent" class="tab-content custom-product-edit">
                            <h4>Head Wise Amount Distribution</h4>
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
                                                                    <label for="name">Employee Name</label>
                                                                    <select name="employee_id" id="employee_id"
                                                                        class="js-example-basic-multiple form-control">
                                                                        <option value="">--select--</option>
                                                                        @foreach ($employee as $emp)
                                                                            <option value="{{ $emp->id }}"
                                                                                {{ $emp->id == $editable_id ? 'selected' : '' }}>
                                                                                {{ $emp->name }}</option>
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
                                        @if ($editable_id)
                                            <form action="{{ route('save-record') }}" method="post">
                                                @csrf
                                                <div class="row">
                                                    {{-- <div class="sparkline8-list"> --}}
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
                                                                        <td>
                                                                            <input type="number" class="form-control"
                                                                                name="amount[{{ $hd->id }}][]"
                                                                                value="{{ $hd->masterAmount($hd->id, $editable_id) }}">
                                                                        </td>
                                                                    </tr>
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
                                                                        <td>
                                                                            <input type="number" class="form-control"
                                                                                name="amount[{{ $hd->id }}][]"
                                                                                value="{{ $hd->masterAmount($hd->id, $editable_id) }}">
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"
                                                            style="display:flex; justify-content:right">
                                                            <input type="hidden" name="employee_id"
                                                                value="{{ $editable_id }}">
                                                            <input type="submit" class="btn btn-primary" value="Submit">
                                                        </div>
                                                    </div>
                                                    {{-- </div> --}}
                                                </div>
                                            </form>
                                        @endif
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
