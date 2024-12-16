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
                                <li><span class="bread-blod">Excel Upload</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="product-payment-inner-st">
                        <div id="myTabContent" class="tab-content custom-product-edit">
                            {{-- <h4>Excel Upload For Salary</h4> --}}
                            <div class="product-tab-list tab-pane fade active in" id="description">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="sparkline8-list">
                                            <h6>Head Wise Salary Import To Temprary Workspace</h6>
                                            <div class="review-content-section">
                                                <form action="{{ route('hd-wise-import') }}" method="post"
                                                    class="dropzone dropzone-custom needsclick add-professors" id="demo1-upload"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    @if (isset($editable))
                                                        <input type="hidden" name="id" value="{{ $editable->id }}">
                                                    @endif
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <a href="{{ route('sample-excel-hd') }}">Click here for
                                                                    csv sample </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label for="date">Select Head</label>
                                                                <select name="head_name" class="form-control" required>
                                                                    <option value="">--select--</option>
                                                                    @foreach ($head as $hd)
                                                                        <option value="{{ $hd->id }}">{{ $hd->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label for="date">CSV Upload</label>
                                                                <input type="file" name="excel_file" class="form-control"
                                                                    required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-3">
                                                            <div class="payment-adress">
                                                                <button type="submit"
                                                                    class="btn btn-primary waves-effect waves-light btn-xs">Import</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="sparkline8-list">
                                            <h6>Employee Salary Import To Head Wise Amount</h6>
                                            <div class="review-content-section">
                                                <form action="{{ route('employee-wise-import') }}" method="post"
                                                    class="dropzone dropzone-custom needsclick add-professors" id="demo1-upload"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    @if (isset($editable))
                                                        <input type="hidden" name="id" value="{{ $editable->id }}">
                                                    @endif
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <a href="{{ route('sample-excel-emp') }}">Click here for
                                                                    csv sample </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label for="date">CSV Upload</label>
                                                                <input type="file" name="excel_file" class="form-control"
                                                                    required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-3">
                                                            <div class="payment-adress">
                                                                <button type="submit"
                                                                    class="btn btn-primary waves-effect waves-light btn-xs">Import</button>
                                                            </div>
                                                        </div>
                                                    </div>
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
