@extends('layouts.app')
@section('content')

    <style>
        .margin-tb {
            color: #53b5e6;
        }
    </style>
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <i class="fa fa-filter"></i>Filter
            <a class="btn btn-sm btn-outline-primary float-right mr-1" href="">
                <i class="fa fa-list"></i> Process Advance Data
            </a>
        </div>
        <div class="card-body">

        <form method="get" action="{{route('advance.search')}}">

            <div class="row">

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="name">Type</label>
                        {!! Form::select("type", $advance_types, request("type"), ["class" => "form-control select2", "placeholder" => "--All--"]) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="name">Emp code {{request("id")}}</label>
                        <select name="emp_id" id="categories" class="form-control select2">
                            <option value="">--ALL--</option>
                            @foreach ($employees as $key=>$data)
                              <option value="{{$data->id}}" {{request("emp_id") == $data->id ? "selected"  : ""}}>{{$data->full_name_with_code}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="name">Status</label>
                        <select name="status" id="categories" class="form-control select2">
                            <option value="">--All--</option>
                            <option value="0">Stoped</option>
                            <option value="1">Active</option>
                            <option value="2">Closed</option>
                        </select>
                    </div>
                </div>
            </div>

      <button type="submit" class='btn btn-primary'>Search </button>

    </div>

</form>
</div>
</div>


<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-list-alt"></i> Advance Records
                    <a class="btn btn-success  float-right mr-1 btn-sm" href="{{route('advance.create')}}"> Add New Advance</a>
                </div>
                <div class="card-body">

                    <form method="post" action="{{route('advance.update_data')}}">
                        @csrf
                        <table class="table table-sm table-bordered">
                            <tbody>
                                <tr>
                                    <th>
                                        <div class="form-check checkbox">
                                            <input class="form-check-input" id="checkall" name="applicable_for_all" type="checkbox">
                                        </div>
                                    </th>
                                    <th>SL No.</th>
                                    <th>Emp. Name</th>
                                    <th>Emp. Code</th>
                                    <th>Adv. type</th>
                                    <th>Amount</th>
                                    <th>Monthly Premium</th>
                                    <th>Remaining Amount</th>
                                    <th width="100px">Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Remarks</th>
                                </tr> <!-- Modal -->
                            </tbody>
                            <tbody>
                                @forelse($advances as $key=>$data)
                                <tr>
                                    <td>
                                        <div class="form-check checkbox">
                                            <input value="{{ $data->id}}"class="form-check-input checkboxes" id="applicable-for-all" name="id[]" type="checkbox">
                                            {{-- <input type="hidden" name="loan_type_id[]" class="form-check-input checkboxes" id="applicable-for-all" value="{{$data->loan_type_id}}" >
                                            <input type="hidden" name="loan_head_id[]" class="form-check-input checkboxes" id="applicable-for-all" value="{{$data->loan_head_id}}">    --}}
                                        </div>
                                    </td>
                                    <td>{{ $key+1}}</td>
                                    <td>{{ $data->employees->full_name}}</td>
                                    <td>{{$data->employees->code}}</td>
                                    <td>{{$data->advanceType->type_name}}</td>
                                    <td class="text-right">{{$data->amount}}</td>
                                    <td class="text-right">{{ $data->monthly_installment }}</td>
                                    <td class="text-right">{{ $data->remaining_amount }}</td>
                                    <td>{{ $data->start_date}}</td>
                                    <td>{{$data->closing_date }}</td>
                                    <td>
                                        @if ($data->status == 1)
                                            <span class="badge badge-success">Active</span>
                                        @endif
                                        @if($data->status == 0)
                                            <span class="badge badge-warning">Stoped</span>
                                        @endif
                                        @if($data->status == 2)
                                            <span class="badge badge-danger">Closed</span>
                                        @endif
                                    </td>
                                    <td>
                                        

                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="9"class="text-center">
                                            <span class="text-danger">No data found</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                        {{-- {{$policies->appends(request()->all())->links()}} --}}
                    </div>

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                Update
                                {{-- <a class="btn btn-sm btn-outline-primary float-right mr-1" href="{{route('lte_transaction.list')}}">
                                    <i class="fa fa-list"></i> Update Policy
                                </a> --}}
                            </div>
                            <div class="card-body">
                                <div class="btn-group  col-md-6">
                                    <input name="comment" type="text" class="form-control" placeholder="Comment" required>
                                </div>

                            <div class="btn-group">
                                <button type="submit" class="btn btn-primary" name="stop_status" value="0">Stop Advance</button>
                            </div>
                            <div class="btn-group">
                                <button type="submit" class="btn btn-primary" name="close_status" value="2">Close Advance</button>
                            </div>
                            <div class="btn-group">
                                <button type="submit" class="btn btn-primary" name="enable_status" value="1">Enable Advance</button>
                            </div>


                            </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
    @endif
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
