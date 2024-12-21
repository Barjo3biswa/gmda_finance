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
                                <li><span class="bread-blod">Hold & Unhold Salary</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="product-payment-inner-st">
                        <div id="myTabContent" class="tab-content custom-product-edit">
                            <h4>Hold & Unhold Salary</h4>
                            <div class="product-tab-list tab-pane fade active in" id="description">
                                <div class="row">
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <div class="sparkline8-hd">
                                            <div class="main-sparkline8-hd">
                                                <div class="row">
                                                    <h6 class="col-md-3">Employee List</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sparkline8-graph">
                                            <div class="static-table-list">
                                                <table class="table table-bordered" id="dtExample">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Emp Code</th>
                                                            <th>Emp Name</th>
                                                            <th>Department</th>
                                                            <th>Designation</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($employee as $key => $emp)
                                                            <tr>
                                                                <th>{{ ++$key }}</th>
                                                                <th>{{ $emp->emp_code }}</th>
                                                                <th>{{ $emp->name }}</th>
                                                                <th>{{ $emp->employee->department->name ?? 'NA' }}</th>
                                                                <th>{{ $emp->employee->designation->name ?? 'NA' }}</th>
                                                                <th>NA</th>
                                                                <th>
                                                                    <a href="#" class="btn btn-primary btn-xs"
                                                                        data-toggle="modal" data-target="#exampleModal"
                                                                        onclick="appendId({{ $emp->id }})">Hold</a>
                                                                    <a href="#"
                                                                        class="btn btn-primary btn-xs">Unhold</a>
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


    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" style="margin-top: 110px;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Hold Salary</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="{{ route('hold-salary') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Holding Type</label>
                                <select name="holding_type" id="holding_type" class="form-control"
                                    onchange="myshowHideFunction()" required>
                                    <option value="">--select--</option>
                                    <option value="permanent">Permanent</option>
                                    <option value="temprary">Temprary</option>
                                </select>
                            </div>
                        </div>
                        <div class="row" id="from_to_date">
                            <div class="col-md-6">
                                <label for="">Holding From</label>
                                <input type="date" name="from_date" id="from_date" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="">Holding To</label>
                                <input type="date" name="to_date" id="to_date" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="">Holding Reason</label>
                                <textarea id="reason" name="reason" rows="4" cols="50" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="row" style="display: flex; justify-content: center; margin-top: 10px;">
                            <input type="hidden" name="emp_id" id="emp_id">
                            <input type="submit" class="btn btn-primary btn-xs" value="Save Changes">
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    {{-- <script>
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2();
        });
    </script> --}}
    <script>
        $(document).ready(function() {
            new DataTable('#dtExample', {
                pageLength: 20,
                layout: {
                    topStart: {
                        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                    }
                },
            })
        })
    </script>
    <script>
        $("#from_to_date").hide();
        $("#from_date").prop('disabled', true);
        $("#to_date").prop('disabled', true);

        function myshowHideFunction() {
            let holding_type = $("#holding_type").val();
            if (holding_type == 'temprary') {
                $("#from_to_date").show();
                $("#from_date").prop('disabled', false);
                $("#to_date").prop('disabled', false);
            } else {
                $("#from_to_date").hide();
                $("#from_date").prop('disabled', true);
                $("#to_date").prop('disabled', true);
            }
        }

        function appendId(id) {
            $("#emp_id").val(id)
        }
    </script>
@endsection
