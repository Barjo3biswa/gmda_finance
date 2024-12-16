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
                                <li><span class="bread-blod">Salary Head</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="product-payment-inner-st">
                        <div id="myTabContent" class="tab-content custom-product-edit">
                            <h4>Salary Head Management</h4>
                            <div class="product-tab-list tab-pane fade active in" id="description">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="sparkline8-list">
                                            <div class="review-content-section">
                                                <form action="{{ route('salary-head-store') }}" method="post"
                                                    class="dropzone dropzone-custom needsclick add-professors"
                                                    id="demo1-upload">
                                                    @csrf

                                                    @if (isset($editable))
                                                        <input hidden name="editable_id" value="{{ $editable->id }}">
                                                    @endif

                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <label for="name">Head Name</label>
                                                                <input name="name" class="form-control"
                                                                    @if (isset($editable)) value="{{ $editable->name }}" @endif>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <label for="name">Head Code</label>
                                                                <input name="code" class="form-control"
                                                                    @if (isset($editable)) value="{{ $editable->code }}" @endif>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <label for="name">Pay Head</label>
                                                                <select name="pay_head" id="pay_head" class="form-control">
                                                                    <option value="">--select--</option>
                                                                    <option value="Income"
                                                                        @if (isset($editable)) {{ $editable->pay_head == 'Income' ? 'selected' : '' }} @endif>
                                                                        Income</option>
                                                                    <option value="Deduction"
                                                                        @if (isset($editable)) {{ $editable->pay_head == 'Deduction' ? 'selected' : '' }} @endif>
                                                                        Deduction</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <label for="name">Income Type</label>
                                                                <select name="income_type" id="income_type"
                                                                    class="form-control">
                                                                    <option value="">--select--</option>
                                                                    <option value="Fix"
                                                                        @if (isset($editable)) {{ $editable->income_type == 'Fix' ? 'selected' : '' }} @endif>
                                                                        Fix</option>
                                                                    <option value="Calculative"
                                                                        @if (isset($editable)) {{ $editable->income_type == 'Calculative' ? 'selected' : '' }} @endif>
                                                                        Calculative</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <label for="name">Salary Deduction (if absent)</label>
                                                                <input type="checkbox" id="sal_deduct_if_absent" name="sal_deduct_if_absent" value="1"
                                                                @if (isset($editable))
                                                                    {{$editable->sal_deduct_if_absent==1?'checked':''}}
                                                                @endif
                                                                >
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <label for="role">Calculation Formulla</label>
                                                                <div class="row">
                                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                                        <input type="number" name="percentage"
                                                                            class="form-control"
                                                                            @if (isset($editable)) 
                                                                                value="{{ $editable->percentage }}" 
                                                                            @endif
                                                                            >
                                                                    </div>
                                                                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                                                                        <h6> % of </h6>
                                                                    </div>
                                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <select name="calculation_on[]" id="calculation_on"
                                                                            class="js-example-basic-multiple form-control"
                                                                            multiple="multiple">
                                                                            @php
                                                                            
                                                                                if (
                                                                                    isset($editable) &&
                                                                                    $editable->calculation_on
                                                                                ) {
                                                                                    $array = json_decode(
                                                                                        $editable->calculation_on,
                                                                                    );
                                                                                } else {
                                                                                    $array = [];
                                                                                }
                                                                            @endphp
                                                                            <option value="">--select--</option>
                                                                            @foreach ($salary_head as $hd)
                                                                                <option value="{{ $hd->id }}"
                                                                                    @if (isset($editable)) {{ in_array($hd->id, $array) ? 'selected' : '' }} @endif>
                                                                                    {{ $hd->name }}</option>
                                                                            @endforeach
                                                                            <option value="998"
                                                                                @if (isset($editable)) {{ in_array(998, $array) ? 'selected' : '' }} @endif>
                                                                                Gross Salary</option>
                                                                            <option value="999"
                                                                                @if (isset($editable)) {{ in_array(999, $array) ? 'selected' : '' }} @endif>
                                                                                Net salary</option>
                                                                        </select>
                                                                        <label for="role">(Summation Of)</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="payment-adress">
                                                                <button type="submit"
                                                                    class="btn btn-primary waves-effect waves-light">Save</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="sparkline8-hd">
                                            <div class="main-sparkline8-hd">
                                                <div class="row">
                                                    <h6 class="col-md-3">Salary Head List</h6>
                                                    <a href="{{ route('salary-head') }}"
                                                        class="btn btn-primary btn-xs col-md-2">Add
                                                        New</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sparkline8-graph">
                                            <div class="static-table-list">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Head Name</th>
                                                            <th>Head Code</th>
                                                            <th>Type</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($salary_head as $hd)
                                                            <tr>
                                                                <th>{{ $hd->name }}</th>
                                                                <th>{{ $hd->code }}</th>
                                                                <th>{{ $hd->pay_head }}</th>
                                                                <th>{{ $hd->status }}</th>
                                                                <th>
                                                                    <a
                                                                        href="{{ route('salary-head-edit', ['editable_id' => Crypt::encrypt($hd->id)]) }}">
                                                                        Edit</a>
                                                                </th>
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

@section('js')
<script>
    $(document).ready(function () {
        $('.js-example-basic-multiple').select2();
    });
</script>
@endsection
