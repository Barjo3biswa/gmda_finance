@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="breadcome-list">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <div class="breadcome-heading">
                            <h3>Summary Head Report</h3>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <ul class="breadcome-menu">
                            <li><a href="#">Dashboard</a> <span class="bread-slash">/</span>
                            </li>
                            <li><span class="bread-blod">Summary Head Report</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="white-box">
                <h3 class="box-title">Summary Head Report Filter</h3>
                <form method="GET" action="{{ route('report.summaryHeadReport') }}"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="salary_head_type">Salary Head Type</label>
                                <select name="salary_head_type" id="salary_head_type" class="form-control">
                                    <option value="">All Types</option>
                                    @foreach($salaryHeadTypes as $headType)
                                        <option value="{{ $headType->pay_head }}" {{ request('salary_head_type') == $headType->pay_head ? 'selected' : '' }}>
                                            {{ $headType->pay_head }}
                                        </option>
                                    @endforeach
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
                                    <option value="">All Categories</option>
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
                                    @php
                                        $currentYear = date('Y');
                                        $years = range($currentYear - 5, $currentYear + 5);
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
                    <div class="row">
                        <div class="col-md-3 align-self-end">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Generate Report</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if(!empty($data) && count($data) > 0)
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="white-box">
                <h3 class="box-title">Summary Report Results</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Salary Head</th>
                                <th>Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $grandTotal = 0; @endphp
                            @foreach($data as $item)
                                <tr>
                                    <td>{{ $item->salaryHead->name ?? 'Unknown Head' }}</td>
                                    <td>{{ number_format($item->total_amount, 2) }}</td>
                                </tr>
                                @php $grandTotal += $item->total_amount; @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="text-right">
                                    <strong>Grand Total</strong>
                                </th>
                                <th>
                                    <strong>{{ number_format($grandTotal, 2) }}</strong>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @php
                    $filterContext = [];

                    if(request('salary_head_type')) {
                        $filterContext[] = "Salary Head Type: " . request('salary_head_type');
                    }

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

                    if(request('from_month') && request('to_month')) {
                        $months = [
                            '01' => 'January', '02' => 'February', '03' => 'March',
                            '04' => 'April', '05' => 'May', '06' => 'June',
                            '07' => 'July', '08' => 'August', '09' => 'September',
                            '10' => 'October', '11' => 'November', '12' => 'December'
                        ];
                        $filterContext[] = "Month Range: " . $months[request('from_month')] . " to " . $months[request('to_month')];
                    }
                @endphp

                @if(count($filterContext) > 0)
                    <div class="alert alert-info mt-3">
                        <strong>Filter Context:</strong><br>
                        @foreach($filterContext as $context)
                            {{ $context }}<br>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    @if(!empty($groupedData) && count($groupedData) > 0 && !request('salary_head_type'))
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="white-box">
                <h3 class="box-title">Summary Report Results (Grouped by Salary Head Type)</h3>
                @php $overallGrandTotal = 0; @endphp
                @foreach($groupedData as $type => $items)
                    <h4 class="mt-4">{{ $type }} Type</h4>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Salary Head</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $typeTotal = 0; @endphp
                                @foreach($items as $item)
                                    <tr>
                                        <td>{{ $item->salaryHead->name ?? 'Unknown Head' }}</td>
                                        <td>{{ number_format($item->total_amount, 2) }}</td>
                                    </tr>
                                    @php
                                        $typeTotal += $item->total_amount;
                                        $overallGrandTotal += $item->total_amount;
                                    @endphp
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-right">
                                        <strong>{{ $type }} Type Total</strong>
                                    </th>
                                    <th>
                                        <strong>{{ number_format($typeTotal, 2) }}</strong>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endforeach
                {{-- <div class="alert alert-info mt-3">
                    <strong>Overall Grand Total:</strong> {{ number_format($overallGrandTotal, 2) }}
                </div> --}}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
