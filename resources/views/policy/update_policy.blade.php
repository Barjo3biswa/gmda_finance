@extends('layouts.app')
@section('content')

    <style>
        .margin-tb {
            color: #53b5e6;
        }
    </style>
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
                                <li><span class="bread-blod">Update Policy</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="product-payment-inner-st">
                        <div id="myTabContent" class="tab-content custom-product-edit">
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <i class="fa fa-filter"></i>Filter
            <a class="btn btn-sm btn-outline-primary float-right mr-1" href="{{route('policy.processpolicy')}}">
                <i class="fa fa-list"></i> Process LIC
            </a>
        </div>
        <div class="card-body">

        <form method="get" action="{{route('policy.search')}}">

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name">Emp code {{request("id")}}</label>
                        <select name="id" id="categories" class="form-control select2">
                            @foreach ($emp as $key=>$data)
                              <option value="{{$data->code}}" {{request("id") == $data->code ? "selected"  : ""}}>{{$data->first_name}} {{$data->last_name}}</option>
                            @endforeach
                        </select>


                    </div>
                </div>
            </div>

      <button type="submit" class='btn btn-primary btn-sm'>Search </button>

    </div>

</form>
</div>
</div>



    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-list-alt"></i> Policy Records
                    <a class="btn btn-success  float-right mr-1 btn-xs" href="{{route('policy.create')}}"> Add New Policy</a>
                </div>
                <div class="card-body">
                    <form method="post" action="{{route('policy.updatepolicydata')}}">
                        @csrf
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>
                                        <input class="form-check-input" id="checkall" name="applicable_for_all" type="checkbox">
                                    </th>
                                    <th>Serial No.</th>
                                    <th>Employee Name</th>
                                    <th>Employee Code</th>
                                    <th>Policy Number</th>
                                    <th>Monthly Premium</th>
                                    <th width="100px">Start Date</th>
                                    <th>Last Date of deduction</th>
                                    <th>Last Month/year</th>
                                    <th>Status</th>
                                    <th>Remarks</th>
                                </tr> <!-- Modal -->
                            </tbody>
                            <tbody>
                                @foreach ($policies as $key=>$data)
                                <tr>
                                    <td>
                                        <input value="{{ $data->id}}"class="form-check-input checkboxes" id="applicable-for-all" name="emp_id[]" type="checkbox">
                                    </td>
                                    <td>{{ $key+1}}</td>
                                    <td>{{ $data->employees->first_name}} {{ $data->employees->last_name}}</td>
                                    <td>{{$data->employee_code}}</td>
                                    <td>{{$data->policy_no}}</td>
                                    <td>{{ $data->monthly_premium }}</td>
                                    <td>{{ $data->start_date}}</td>
                                    <td>{{$data->closing_date }}</td>
                                    <td>{{$data->closing_month}}/{{$data->closing_year}}</td>
                                    <td>
                                        {{$data->status_name}}

                                    </td>
                                    <td>
                                            {{$data->closing_reason}}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>
                        {{$policies->appends(request()->all())->links()}}
                    </div>

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                Update Policy
                                {{-- <a class="btn btn-sm btn-outline-primary float-right mr-1" href="{{route('lte_transaction.list')}}">
                                    <i class="fa fa-list"></i> Update Policy
                                </a> --}}
                            </div>
                            <div class="card-body">
                                <div class="btn-group  col-md-6">
                                    <input name="comment" type="text" class="form-control" placeholder="Comment">
                                </div>

                            <div class="btn-group">
                                <button type="submit" class="btn btn-primary btn-sm" name="stop_status" value="0">Stop Policy</button>
                            <!-- <button  type="submit" class="btn btn-primary">Stop Policy<label>
                                    <input hidden name="status" value="0">
                                </label></button>-->
                            </div>
                            <div class="btn-group">
                                <button type="submit" class="btn btn-primary btn-sm" name="close_status" value="9">Close Policy</button>
                            <!-- <button  type="submit" class="btn btn-primary">Close Policy<label>
                                    <input hidden name="status" value="9">
                                </label></button>-->
                            </div>
                            <div class="btn-group">
                                <button type="submit" class="btn btn-primary btn-sm" name="enable_status" value="1">Enable Policy</button>
                            <!-- <button  type="submit" class="btn btn-primary">Enable Policy<label>
                                    <input hidden name="status" value="9">
                                </label></button>-->
                            </div>


                            </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>


    @if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
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
</div>
@endsection
@section('js')
<script>
$("#checkall").click(function (){
    if ($("#checkall").is(':checked')){
       $(".checkboxes").each(function (){
          $(this).prop("checked", true);
          });
       }else{
          $(".checkboxes").each(function (){
               $(this).prop("checked", false);
          });
       }
});
</script>


@endsection
