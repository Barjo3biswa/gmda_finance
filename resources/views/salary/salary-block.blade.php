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
                                <li><span class="bread-blod">Salary Block</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="product-payment-inner-st">
                        <div id="myTabContent" class="tab-content custom-product-edit">
                            <h4>Salary Block Management</h4>
                            <div class="product-tab-list tab-pane fade active in" id="description">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="sparkline8-hd">
                                            <div class="main-sparkline8-hd">
                                                <div class="row">
                                                    <h6 class="col-md-3">Salary Block List</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sparkline8-graph">
                                            <div class="static-table-list">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Month</th>
                                                            <th>Year</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($blocks as $blok)
                                                            <tr>
                                                                <td>{{ \Carbon\Carbon::createFromDate(null, $blok->month)->format('F') }}
                                                                </td>
                                                                <td>{{ $blok->year }}
                                                                </td>
                                                                <td>
                                                                    @if ($blok->sal_process_status == 'block')
                                                                        <span class="badge badge-danger">Blocked</span>
                                                                    @else
                                                                        <span
                                                                            class="badge badge-success">Unblocked</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($blok->sal_process_status == 'block')
                                                                        <a href="{{ route('block-unblock', Crypt::encrypt($blok->id)) }}"
                                                                            onclick="return confirm('Are you sure you want to Change?');">Unblock</a>
                                                                    @else
                                                                        <a href="{{ route('block-unblock', Crypt::encrypt($blok->id)) }}"
                                                                            onclick="return confirm('Are you sure you want to Change?');">block</a>
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
    </div>
@endsection
