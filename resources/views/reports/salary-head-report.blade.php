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
                                <li><span class="bread-blod">Salary Head Report</span>
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
                                    <form action="{{ route('report.salaryHeadReport') }}" method="GET"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="salary_head">Select Salary Head</label>
                                                    <select name="salary_head" id="salary_head" class="form-control"
                                                        required>
                                                        <option value="">Select Salary Head</option>
                                                        @foreach ($SalaryHead as $head)
                                                            <option value="{{ $head->id }}"
                                                                {{ request('salary_head') == $head->id ? 'selected' : '' }}>
                                                                {{ $head->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="appointment_type">Appointment Type</label>
                                                    <select name="appointment_type" id="appointment_type"
                                                        class="form-control">
                                                        <option value="">All Types</option>
                                                        @foreach ($appointmenttypes as $type)
                                                            <option value="{{ $type->name }}"
                                                                {{ request('name') == $type->name ? 'selected' : '' }}>
                                                                {{ $type->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="pf_category">PF Category</label>
                                                    <select name="pf_category" id="pf_category" type="text"
                                                        class="form-control" placeholder="" value="">
                                                        <option value="">All Categories</option>
                                                        <option value="CPF"
                                                            {{ request('pf_category') == 'CPF' ? 'selected' : '' }}>CPF
                                                        </option>
                                                        <option value="EPF"
                                                            {{ request('pf_category') == 'EPF' ? 'selected' : '' }}>EPF
                                                        </option>
                                                        <option value="GPF"
                                                            {{ request('pf_category') == 'GPF' ? 'selected' : '' }}>GPF
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="department">Select Department</label>
                                                    <select name="department" id="department" class="form-control">
                                                        <option value="">All Departments</option>
                                                        @foreach ($departments as $department)
                                                            <option value="{{ $department->id }}"
                                                                {{ request('department') == $department->id ? 'selected' : '' }}>
                                                                {{ $department->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="section">Select Section</label>
                                                    <select name="section" id="section" class="form-control">
                                                        <option value="">All Sections</option>
                                                        @foreach ($sections as $section)
                                                            <option value="{{ $section->id }}"
                                                                {{ request('section') == $section->id ? 'selected' : '' }}>
                                                                {{ $section->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="from_month">From Month</label>
                                                    <select name="from_month" id="from_month" class="form-control">
                                                        <option value="">Select From Month</option>
                                                        @php
                                                            $months = [
                                                                '01' => 'January',
                                                                '02' => 'February',
                                                                '03' => 'March',
                                                                '04' => 'April',
                                                                '05' => 'May',
                                                                '06' => 'June',
                                                                '07' => 'July',
                                                                '08' => 'August',
                                                                '09' => 'September',
                                                                '10' => 'October',
                                                                '11' => 'November',
                                                                '12' => 'December',
                                                            ];
                                                        @endphp
                                                        @foreach ($months as $monthNum => $monthName)
                                                            <option value="{{ $monthNum }}"
                                                                {{ request('from_month') == $monthNum ? 'selected' : '' }}>
                                                                {{ $monthName }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="to_month">To Month</label>
                                                    <select name="to_month" id="to_month" class="form-control">
                                                        <option value="">Select To Month</option>
                                                        @foreach ($months as $monthNum => $monthName)
                                                            <option value="{{ $monthNum }}"
                                                                {{ request('to_month') == $monthNum ? 'selected' : '' }}>
                                                                {{ $monthName }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="year">Select Year</label>
                                                    <select name="year" id="year" class="form-control" required>
                                                        <option value="">Select Year</option>
                                                        @php
                                                            $currentYear = date('Y');
                                                            $years = range($currentYear - 5, $currentYear + 1);
                                                        @endphp
                                                        @foreach ($years as $year)
                                                            <option value="{{ $year }}"
                                                                {{ request('year', $currentYear) == $year ? 'selected' : '' }}>
                                                                {{ $year }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary mr-2">Generate Report</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            @if (!empty($data) && count($data) > 0)
                                <div class="card mt-3">
                                    <div class="card-header">
                                        Salary Head Report
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>SL. No.</th>
                                                        <th>Employee Code</th>
                                                        <th>Employee Name</th>
                                                        <th>Salary Head Name</th>
                                                        <th>Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($data as $transaction)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $transaction->emp_code }}</td>
                                                            <td>{{ $transaction->employee->first_name }}
                                                                {{ $transaction->employee->last_name }}</td>
                                                            <td>{{ $transaction->salary_head_name }}</td>
                                                            <td>{{ number_format($transaction->amount, 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="4" class="text-right"><strong>Total</strong></td>
                                                        <td><strong>{{ number_format($data->sum('amount'), 2) }}</strong>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif
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
