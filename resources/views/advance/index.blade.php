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
                                <li><span class="bread-blod">Approved Advances</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="product-payment-inner-st">
                        <div id="myTabContent" class="tab-content custom-product-edit">
                            {{-- <div class="container"
                                style="margin-bottom: 100px; margin-top: 50px; box-shadow: 0 1px 3px rgba(0,0,0,0.12);"> --}}
                            <div class="card mb-3">
                                <div class="card-header">
                                    <i class="fa fa-filter"></i> Filter
                                    <a class="btn btn-sm btn-outline-primary float-right mr-1" href="">
                                        <i class="fa fa-list"></i> Process Advances Data
                                    </a>
                                </div>
                                <div class="card-body">
                                    <form method="get" action="">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="name">Type</label>
                                                    <select name="advance_type_id" id="advance_type_id" class="form-control"
                                                        required>
                                                        <option value="">--All--</option>
                                                        @foreach ($advanceTypes as $advanceType)
                                                            <option value="{{ $advanceType->id }}"
                                                                {{ old('advance_type_id') == $advanceType->id ? 'selected' : '' }}>
                                                                {{ $advanceType->type_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="name">Department</label>
                                                    <select name="department_id" id="department"
                                                        class="form-control select2">
                                                        <option value="">--All--</option>
                                                        @foreach ($departments as $key => $department)
                                                            <option value="{{ $department->id }}"
                                                                {{ request('department') == $department->id ? 'selected' : '' }}>
                                                                {{ $department->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="name">Employee</label>
                                                    <select name="employee_id" id="categories" class="form-control select2">
                                                        <option value="">--All--</option>
                                                        @foreach ($emp as $employee)
                                                            <option value="{{ $employee->id }}"
                                                                {{ $employee->id == request('employee_id') ? 'selected' : '' }}>
                                                                {{ $employee->first_name }} {{ $employee->last_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2" style="padding-top: 24px;">
                                                <button type="submit" class='btn btn-primary btn-sm text-white mt-4'>Search
                                                </button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                            <br>
                            <div class="card mt-2">
                                <div class="card-header">
                                    <!-- <a class="btn btn-success btn btn-xs float-right mr-1" href="{{ route('advance.create') }}"><i class="fa fa-plus"></i> New Advance</a> | -->
                                    <a class="btn btn-success btn btn-xs float-right mr-1"
                                        href="{{ route('advance.existing') }}"><i class="fa fa-plus"></i> Existing
                                        Advance</a>
                                </div>
                                <div class="card-body">
                                    @if ($message = Session::get('success'))
                                        <div class="alert alert-success">
                                            <p>{{ $message }}</p>
                                        </div>
                                    @endif
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm font-12">
                                            <tbody>
                                                <tr>
                                                    <th>SL</th>
                                                    <th>Emp. Name</th>
                                                    <th>Type</th>
                                                    <th>Total Amount</th>
                                                    <th class="text-right">Deduction Amount</th>
                                                    <th class="text-right">Recoverd Amount</th>
                                                    <th width="100px">WEF Month</th>
                                                    <th>WEF Year</th>
                                                    <th width="230px">Action</th>
                                                    <th></th>
                                                </tr> <!-- Modal -->
                                            </tbody>
                                            <tbody>
                                                @foreach ($advanceRequests as $key => $data)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $data->employee->first_name }}({{ $data->employee->code }})
                                                        </td>
                                                        <td>{{ $data->advanceType->type_name ?? 'NA' }}</td>
                                                        <td class="text-right">{{ $data->principal_amount }}</td>
                                                        <td class="text-right">{{ $data->monthly_installment }}</td>
                                                        <td class="text-right">{{ $data->recovered_amount }}</td>
                                                        <td>{{ date('F', mktime(0, 0, 0, $data->installment_month, 1)) }}
                                                        </td>
                                                        <td>{{ $data->installment_year }}</td>
                                                        <td>
                                                            {{-- <a href="" class="btn btn-danger btn-sm">Delete</a> --}}
                                                            <a href="{{ route('advance.edit', ['id' => $data->id]) }}"
                                                                class="btn btn-success btn-xs">View/Update</a>
                                                        </td>
                                                        <td>
                                                            @if ($data->has_loan_master)
                                                                <span class="badg badge-succes">for processing</span>
                                                            @else
                                                                <span class="badg badge-warnin">Pending</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <!-- <tfoot>
                                                                                                <tr>
                                                                                                    <th colspan="4" class="text-right">Total</th>
                                                                                                    <th class="text-right">
                                                                                                        @php
                                                                                                            echo number_format(
                                                                                                                (float) $advanceRequests->sum(
                                                                                                                    'amount',
                                                                                                                ),
                                                                                                                2,
                                                                                                            );
                                                                                                        @endphp</th>
                                                                                                    <th class="text-right">
                                                                                                    @php
                                                                                                        echo number_format(
                                                                                                            (float) $advanceRequests->sum(
                                                                                                                'monthly_installment',
                                                                                                            ),
                                                                                                            2,
                                                                                                        );
                                                                                                    @endphp
                                                                                                    </th>
                                                                                                    <th class="text-right">
                                                                                                    @php
                                                                                                        echo number_format(
                                                                                                            (float) $advanceRequests->sum(
                                                                                                                'remaining_amount',
                                                                                                            ),
                                                                                                            2,
                                                                                                        );
                                                                                                    @endphp
                                                                                                    </th>
                                                                                                    <th colspan="3"></th>
                                                                                                </tr>
                                                                                            </tfoot> -->
                                        </table>
                                    </div>
                                    {{-- {{$policies->appends(request()->all())->links()}} --}}
                                </div>

                            </div>
                            {{-- </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function(() {
                    $(".select2").select2();
                });
    </script>
@endsection
