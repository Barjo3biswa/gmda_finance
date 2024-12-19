@extends('layouts.app')
<style>
	.error {
		color: red;
	}

</style>
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
                                <li><span class="bread-blod">New Policy</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="product-payment-inner-st">
                        <div id="myTabContent" class="tab-content custom-product-edit">
	<div class="card">
		<div class="card-header">
			NEW POLICY DETAILS
			<a href="{{ route('policy.index') }}" class="btn btn-sm btn-outline-success float-right mr-1">Policy records</a>
		</div>
		<div class="card-body">
			<form action="{{ route('policy.store') }}" method="POST" enctype="multipart/form-data">
				@csrf
				<div class="form-group row">
					<div class="col-md-4">
						<label for="inputname">Employee Name</label>
						<select class="form-control select2" id="employee_id" name="employee_id">
							<option value="">--SELECT--</option>
							@foreach ($employees as $key => $emp)
								<option value="{{ $emp->user_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
							@endforeach
						</select>
					</div>
					{{-- <div class=" col-md-6">
                     <label for="">Employee Code</label>
                    <select class="form-control" id="employee_code" name="employee_code">
                        @foreach ($employees as $key => $employee)
                        <option value=" {{$employee->code}}" id="employee_id">{{$employee->code}}</option>
                        @endforeach
                    </select>
                </div> --}}
				</div>
				<h5><strong>Policy details</strong></h5>
				<hr />
				<div class="form-group row">
					<div class=" col-md-6">
						<label for="">LIC Policy Number</label>
						<input type="number" class="form-control" id="policy_no" placeholder="policy number" name="policy_no"
							id="policy_no" required>
                        {{-- @error('policy_no') <span class="text-danger error">{{ $message }}</span>@enderror --}}
					</div>
					<div class="col-md-6">
						<label for="inputMobileNumber">Self/Dependent Name</label>
						<input type="text" class="form-control" id="dependent_name" name="dependent_name" placeholder="dependent name"
							required="">
					</div>
				</div>
				<div class="form-group row">
					<div class=" col-md-6">
						<label for="inputname">LIC Policy Name</label>
						<input type="text" class="form-control" id="policy_name" name="policy_name" value="LIC" required>
					</div>
					<!-- <div class="col-md-6">
						<label for="inputAddress">Policy Amount</label>
						<input type="number" class="form-control  text-right" id="amount" name="amount" placeholder="0.00"></textarea>
					</div> -->
				</div>

				<div class="form-group row">
					<div class="col-md-6">
						<label for="inputAddress">Monthly Premium</label>
						<input type="number" class="form-control  text-right" id="monthly_premium" name="monthly_premium"
							placeholder="0.00" required=""></textarea>
					</div>
					<div class="col-md-3">
						<label for="inputcompany_name">W.E.F. Month</label>
						<select name="wef_month" id="wef_month" class="form-control">
							<option value="">Select Month</option>
							<option value="1" {{ old('wef_month') == '1' ? 'selected' : '' }}>January</option>
							<option value="2" {{ old('wef_month') == '2' ? 'selected' : '' }}>February</option>
							<option value="3" {{ old('wef_month') == '3' ? 'selected' : '' }}>March</option>
							<option value="4" {{ old('wef_month') == '4' ? 'selected' : '' }}>April</option>
							<option value="5" {{ old('wef_month') == '5' ? 'selected' : '' }}>May</option>
							<option value="6" {{ old('wef_month') == '6' ? 'selected' : '' }}>June</option>
							<option value="7" {{ old('wef_month') == '7' ? 'selected' : '' }}>July</option>
							<option value="8" {{ old('wef_month') == '8' ? 'selected' : '' }}>August</option>
							<option value="9" {{ old('wef_month') == '9' ? 'selected' : '' }}>September</option>
							<option value="10" {{ old('wef_month') == '10' ? 'selected' : '' }}>October</option>
							<option value="11" {{ old('wef_month') == '11' ? 'selected' : '' }}>November</option>
							<option value="12" {{ old('wef_month') == '12' ? 'selected' : '' }}>December</option>
						</select>
					</div>
					<div class="col-md-3">
						<label for="inputcompany_name">W.E.F. Year</label>
						<select name="wef_year" id="wef_year" class="form-control">
							<option value="">Select Year</option>
							@for ($year = 2025; $year <= 2035; $year++)
								<option value="{{ $year }}" {{ old('wef_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
							@endfor
						</select>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-md-6">
						<label for="inputStart_date">LIC Start Date</label>
						<input type="date" class="form-control" id="start_date" placeholder="start date" name="start_date" required>
					</div>

					<div class="col-md-6">
						<label for="inputMature_date">LIC Mature Date</label>
						<input type="date" name="maturity_date" id="maturity_date" class="form-control" placeholder="Maturity Date">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-6">
						<label for="">Lic Closing date</label>
						<input type="date" class="form-control" name="closing_date" id="closing_date" placeholder="closing date">
					</div>


				</div>
				<button type="submit" class="btn btn-primary">Submit</button>

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
@section('js')
	<script>
	 $(document).ready(function() {
	  $("#employee_id").change(function(event) {
	   $("#dependent_name").val($("#employee_id option:selected").text());
	  });
	  // $(".select2").select2();
	 });

	 $('#wef_month').Zebra_DatePicker({
	  format: 'm',
	 });
	 $('#wef_year').Zebra_DatePicker({
	  format: 'Y',
	 });
	 $('#start_date').Zebra_DatePicker({
	  format: 'Y/m/d',
	 });
	 $('#maturity_date').Zebra_DatePicker({
	  format: 'Y/m/d',
	 });
	 $('#closing_date').Zebra_DatePicker({
	  format: 'Y/m/d',
	 });
	</script>


	{{-- <script>
	 $('#myfun').validate({
	  rules: {
	   blockName: {
	    required: true,
	    remote: {
	     url: "checkblock.php",
	     type: "post"
	    }
	   }
	  },
	  messages: {
	   blockName: {
	    remote: "Block already in use!"
	   }
	  }

	 });
	</script> --}}


@endsection
