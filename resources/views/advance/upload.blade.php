@extends('layouts.app')
@section('content')

<div class="card">
    <div class="card-header">
        <i class="fa fa-file-excel-o"></i> Upload Excel FIle.
    </div>
    <div class="card-body">

        <form method="post" action="{{route("advance.upload-store")}}" enctype="multipart/form-data">
            @csrf
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="name">Month</label>
                                {!! Form::select("month",[$salarystatus->salary_month => \App\Helpers\CommonHelper::getMonthName($salarystatus->salary_month)], $salarystatus->salary_month, [
                            "class" => "form-control ", "placeholder" => "--SELECT--", "required" => "required",'id'=>'month', "disabled" => "disabled"]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="hidden" name="_token" id="csrf" value="{{Session::token()}}">
                            <label for="name">Year</label>
                                {!! Form::select("year", [$salarystatus->salary_year => $salarystatus->salary_year], $salarystatus->salary_year, [
                            "class" => "form-control ", "placeholder" => "--SELECT--", "required" => "required",'id'=>'year', "disabled" => "disabled"]) !!}
                        </div>
                    </div>
                </div>
               
               
                <div class="form-group has-feedback">
                    <input type="file" name="file" data-validation="required" data-validation-error-msg="Please Upload Excel File"/></br>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <button type="submit" class='btn btn-primary text-white'>Upload </button>
                        <span class="badge text-danger">
                            * Download Sample File
                        </span>

                            <a href="{{asset('sample_files/advance_import.xlsx')}}" download>
                                <span class="badge badge-success">
                                    here
                                </span>
                            </a>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-3">
                        <a href="{{route('advance.processed_data_list')}}">
                            <span class="btn btn-danger">
                                Finalize Data
                            </span>
                        </a>
                    </div>
                </div>
        </form>
    </div>
</div>

@endsection
@section('css')

@endsection
@section('js')


@endsection
