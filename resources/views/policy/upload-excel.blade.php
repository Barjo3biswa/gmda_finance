@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">
        {{-- excel fa fa icon --}}
        <i class="fa fa-file-excel-o"></i> Upload LIC Policy Excel
        <a href="{{ route('policy.index') }}" class="btn btn-sm btn-outline-success float-right mr-1">Policy records</a>
    </div>
    <div class="card-body">
        {{-- <form action="{{ route('policy.store-excel') }}" method="POST" enctype="multipart/form-data"> --}}
        {!! Form::open(["route" =>  'policy.store-excel', "files" => true]) !!}
            @csrf
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label for="month">Salary Month</label>
                        {!! Form::select("salary_month", $month_array, request("month"), ["class" => "form-control select2", "required" => true]) !!}
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="year">Salary Year</label>
                        {!! Form::select("salary_year", $year_array, request("year"), ["class" => "form-control select2", "required" => true]) !!}
                    </div>
                </div>
                <div class="col-8">
                    <div class="form-group">
                        <div class="d-flex justify-content-between mb-2">
                            <label for="year">Excel File </label>
                            <a class="btn btn-sm btn-success" href="{{url('sample_files/lic-import-sample.xlsx')}}">Download Sample File</a>
                        </div>
                        {!! Form::file("file", ["class" => "form-control", "accept" => ".xlsx,.xls,.csv", "required" => true]) !!}
                   </div>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
    {!! Form::close() !!}
    </div>
</div>
@endsection