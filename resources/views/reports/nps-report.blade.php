@php
use Carbon\Carbon;
@endphp
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
                                <li><span class="bread-blod">NPS Report</span>
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
                                    <form action="{{ route('report.npsReport') }}" method="GET"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="report_type">Select Report Type</label>
                                                    <select name="report_type" id="report_type" class="form-control" required>
                                                        <option value="">Select Report Type</option>
                                                        <option value="monthly" {{ request('report_type') == 'monthly' ? 'selected' : '' }}>Monthly Report</option>
                                                        <option value="summary" {{ request('report_type') == 'summary' ? 'selected' : '' }}>Summary Report</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="department">Select Department</label>
                                                    <select name="department" id="department" class="form-control">
                                                        <option value="">All Departments</option>
                                                        @foreach($departments as $department)
                                                            <option value="{{ $department->id }}" {{ request('department') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="section">Select Section</label>
                                                    <select name="section" id="section" class="form-control">
                                                        <option value="">All Sections</option>
                                                        @foreach($sections as $section)
                                                            <option value="{{ $section->id }}" {{ request('section') == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="appointment_type">Appointment Type</label>
                                                    <select name="appointment_type" id="appointment_type" class="form-control">
                                                        <option value="">All Types</option>
                                                        @foreach($appointmenttypes as $type)
                                                            <option value="{{ $type->name }}" {{ request('appointment_type') == $type->name ? 'selected' : '' }}>{{ $type->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="pf_category">PF Category</label>
                                                    <select name="pf_category" id="pf_category" class="form-control">
                                                        <option value="" selected="" disabled="">Select</option>
                                                        <option value="" {{ request('pf_category') == '' ? 'selected' : '' }}>ALL</option>
                                                        <option value="CPF" {{ request('pf_category') == 'CPF' ? 'selected' : '' }}>CPF</option>
                                                        <option value="EPF" {{ request('pf_category') == 'EPF' ? 'selected' : '' }}>EPF</option>
                                                        <option value="GPF" {{ request('pf_category') == 'GPF' ? 'selected' : '' }}>GPF</option>
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
                                                                '01' => 'January', '02' => 'February', '03' => 'March',
                                                                '04' => 'April', '05' => 'May', '06' => 'June',
                                                                '07' => 'July', '08' => 'August', '09' => 'September',
                                                                '10' => 'October', '11' => 'November', '12' => 'December'
                                                            ];
                                                        @endphp
                                                        @foreach($months as $monthNum => $monthName)
                                                            <option value="{{ $monthNum }}" {{ request('from_month') == $monthNum ? 'selected' : '' }}>
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
                                                        @foreach($months as $monthNum => $monthName)
                                                            <option value="{{ $monthNum }}" {{ request('to_month') == $monthNum ? 'selected' : '' }}>
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

                            @if (!empty($data))
                                @if(request()->report_type == 'monthly')
                                <div class="card mt-3">
                                    <div class="card-header">
                                        Monthly NPS Report
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>SL. No.</th>
                                                        <th>Employee Code</th>
                                                        <th>Employee Name</th>
                                                        <th>Month</th>
                                                        <th>Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($data as $transaction)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $transaction->emp_code }}</td>
                                                        <td>{{ $transaction->employee->first_name }} {{ $transaction->employee->last_name }}</td>
                                                        <td>
                                                           {{ Carbon::createFromFormat('m', $transaction->month)->monthName }}
                                                        </td>
                                                        <td>{{ number_format($transaction->amount, 2) }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="3" class="text-right"><strong>Total</strong></td>
                                                        <td><strong>{{ number_format($data->sum('amount'), 2) }}</strong></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="card mt-3">
                                    <div class="card-header">
                                        NPS Summary Report
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-info">
                                            <strong>Total NPS Amount:</strong>
                                            @if(request('from_month') && request('to_month'))
                                                {{ number_format($data, 2) }}
                                                (From {{ Carbon::createFromFormat('m', request('from_month'))->monthName }}
                                                to {{ Carbon::createFromFormat('m', request('to_month'))->monthName }} {{ request('year') }})
                                            @else
                                                {{ number_format($data, 2) }}
                                                (Year {{ request('year') }})
                                            @endif

                                            @php
                                                $filterContext = [];

                                                if(request('department')) {
                                                    $department = $departments->firstWhere('id', request('department'));
                                                    $filterContext[] = "Department: " . ($department ? $department->name : 'N/A');
                                                }

                                                if(request('section')) {
                                                    $section = $sections->firstWhere('id', request('section'));
                                                    $filterContext[] = "Section: " . ($section ? $section->name : 'N/A');
                                                }

                                                if(request('appointment_type')) {
                                                    $filterContext[] = "Appointment Type: " . request('appointment_type');
                                                }

                                                if(request('pf_category')) {
                                                    $filterContext[] = "PF Category: " . request('pf_category');
                                                }
                                            @endphp

                                            @if(count($filterContext) > 0)
                                                <br>
                                                <strong>Filter Context:</strong>
                                                <br>
                                                @foreach($filterContext as $context)
                                                    {{ $context }}<br>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endif
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
