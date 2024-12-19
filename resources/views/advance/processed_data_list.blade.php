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
                                <li><span class="bread-blod">Processed Advance List</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="product-payment-inner-st">
                        <div id="myTabContent" class="tab-content custom-product-edit">
    <div class="card">
        <div class="card-header">
            <i class="fa fa-filter"></i> Filter
            <a class="btn btn-sm btn-outline-primary float-right mr-1" href="{{route("advance.processadvance")}}">
                <i class="fa fa-list"></i> Process Advance Data
            </a>
        </div>
        <div class="card-body">

            <form method="get" action="" autocomplete="off">
                <div class="row">
                    {{-- <div class="col-md-4">
                        <div class="form-group">
                            <label for="name">Type</label>
                            {!! Form::select("type", $advance_types, request("type"), ["class" => "form-control select2", "placeholder" => "--All--"]) !!}
                        </div>
                    </div> --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="name">Month</label>
                            <select name="month" class="form-control select2" required>
                                <option value="" disabled selected>--All--</option>
                                <!-- Loop through the months array -->
                                @foreach (\App\Helpers\CommonHelper::allMonthArray() as $key => $month)
                                    <option value="{{ $key }}" {{ request('month') == $key ? 'selected' : '' }}>
                                        {{ $month }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="name">Year</label>
                            <select name="year" class="form-control select2" required>
                                <option value="" disabled selected>--All--</option>
                                @foreach (\App\Helpers\CommonHelper::getYearList() as $year)
                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <button type="submit" class='btn btn-primary btn-sm text-white'>Search </button>

            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <i class="fa fa-list-alt"></i> ADVANCE RECORDS
            <!-- <a class="btn btn-sm btn-outline-success float-right mr-1" href="{{ route("advance.processed_data_list", request()->merge(["export" => "excel"])->all())}}">
                <i class="fa fa-file-excel-o"></i> Export
            </a> -->
        </div>
        <div class="card-body">
            <form action="{{ route('advance.process_advance_data_post') }}" method="POST">
            @csrf
            <input type="hidden" name="month" value="{{ request("month") }}">
            <input type="hidden" name="year" value="{{ request("year") }}">
            <table class="table table-bordered table-sm" id="employee_table">
                <tbody>
                    <tr>
                        <th>SL.</th>
                        <th>Emp. Code</th>
                        <th>Emp Name</th>
                        <th>Ref. No</th>
                        <th>Advance Type</th>
                        <th class="text-right">Installment Amt.</th>
                        <th>Month</th>
                        <th>Year</th>
                        <th>Action</th>
                    </tr>
                </tbody>
                <tbody>
                    @forelse ($processed_data as $key => $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row->employee->code ?? 'N/A' }}</td>
                            <td>{{ $row->employee->first_name ?? 'N/A' }}</td>
                            <td>{{ $row->reference_no ?? 'N/A' }}</td>
                            <td>{{ $row->advanceType->type_name ?? "NA" }}</td>
                            <td class="text-right">{{ $row->amount ?? 'N/A' }}</td>
                            <td>{{ \App\Helpers\CommonHelper::getMonthName($row->month) ?? 'N/A' }}</td>
                            <td>{{ $row->year ?? 'N/A' }}</td>
                            <td>
                                @if ($row->isProcessingAllowed())

                                    <a href="{{route("advance.processed_data_list_delete", $row->id)}}" class="btn btn-danger btn-xs"
                                        onclick="return confirm('Are you sure to delete this record?')">
                                        <i class="fa fa-trash"></i>
                                    </a>

                                @else
                                    <span class="badge badge-primary">Processed at: {{$row->processed_at}}</span>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-danger">No Data Found</td>
                        </tr>
                    @endempty
                    <tfoot>
                        <tr>
                            <th class="text-right" colspan="5">Total</th>
                            <th class="text-right">{{number_format(($processed_data->sum("amount") ?? 0.00), 2)}}</th>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                </tbody>
            </table>
            <!-- <button type="submit" class="btn btn-primary" id="process" onClick="return confirm('Are you sure?')">
                    Finalize & process
                </button>

                <script>
                    var processButton = document.getElementById('process');
                    var processAllowed = {{ $processed_data->where('process_allowed', 1)->isEmpty() ? 'false' : 'true' }};
                    
                    if (!processAllowed) {
                        processButton.disabled = true;
                    }
                </script>

            </form> -->
            {{-- {{$policies->appends(request()->all())->links()}} --}}
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
</div>
    
@endsection
@section('js')
    <script>
    </script>


@endsection
