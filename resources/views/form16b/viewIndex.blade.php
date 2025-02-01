@extends('layouts.app')
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
                                <li><span class="bread-blod">Process Form16b</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="product-payment-inner-st">
                        <div id="myTabContent" class="tab-content custom-product-edit">
                            <div class="card">
                                <div class="card-header">
                                    Select an Employee
                                    {{-- <a href="{{route('advance.index')}}" class="btn btn-success float-right btn-xs mr-1"><i class="fa fa-arrow-left"></i> Back</a> --}}
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('form16.view') }}" method="GET" enctype="multipart/form-data">
                                        @csrf

                                        <div class="form-group row">
                                            <div class="col-md-2">
                                                <label for="employee_id">Select Employee</label>
                                                <select name="employee_id" id="employee_id" class="form-control" required>
                                                    <option value="">Select an Employee</option>
                                                    @foreach ($employees as $employee)
                                                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="financial_year">Financial Year</label>
                                                <select name="financial_year" id="financial_year" class="form-control"
                                                    required>
                                                    <option value="">Select Financial Year</option>
                                                    @php
                                                        $currentYear = date('Y');
                                                        $currentMonth = date('m');
                                                        $financialYears = [];

                                                        // Generate financial years (April to March)
                                                        for ($i = -1; $i < 3; $i++) {
                                                            $startYear =
                                                                $currentMonth >= 4
                                                                    ? $currentYear + $i
                                                                    : $currentYear - 1 + $i;
                                                            $endYear = $startYear + 1;
                                                            $financialYears[] = $startYear . '-' . $endYear;
                                                        }
                                                    @endphp

                                                    @foreach ($financialYears as $year)
                                                        <option value="{{ $year }}">{{ $year }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-sm">Download Form16b</button>

                                    </form>
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
@endsection
@section('js')
    <script></script>
@endsection
