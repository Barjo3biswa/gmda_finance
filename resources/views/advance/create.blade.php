@extends('layouts.app')
<style>
    .error{
        color: red;
    }
</style>
@section('content')
<div class="container" style="margin-bottom: 100px; margin-top: 50px; ; box-shadow: 0 1px 3px rgba(0,0,0,0.12);">
	<div class="card" style="background-color: #000;">
		<div class="card-header">
			NEW ADVANCE DETAILS
			<a href="{{ route('advance.index') }}" class="btn btn-sm btn-outline-success float-right mr-1">Advance records</a>
		</div>
		<div class="card-body">
			<form action="{{ route('advance.store') }}" method="POST" enctype="multipart/form-data">
				@csrf
				<div class="form-group row">
					<div class=" col-md-6">
						<label for="inputname">Employee Name</label>
						<select class="form-control select2" id="employee_id" name="employee_id">
							<option value="">--SELECT--</option>
							@foreach ($employees as $key => $emp)
								<option value="{{ $emp->user_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
							@endforeach
						</select>
					</div>

                    <input type="hidden" class="form-control" id="emp_name" name="emp_name" required="">

				</div>
				<h5><strong>Advance details</strong></h5>
				<hr />
				<div class="form-group row">
					<div class=" col-md-6">
						<label for="">Advance Ref. Policy Number</label>
						<input type="number" class="form-control" id="ref_no" placeholder="reference number" name="ref_no"
							id="policy_no" required>
					</div>

                    <div class=" col-md-3">
                        <label for="">Advance Type</label>
                        <select name="advance_type_id" id="advance_type_id" class="form-control" required>
                            <option value="">--All--</option>
                            @foreach($advanceTypes as $advanceType)
                                <option value="{{ $advanceType->id }}" {{ old('advance_type_id') == $advanceType->id ? 'selected' : '' }}>{{ $advanceType->type_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class=" col-md-3">
                        <label for="">Advance Head</label>

                        
                    </div>

				</div>


                <div class="form-group row">
                    <div class="col-md-6">
						<label for="inputAddress">Duration</label>
						<input type="number" class="form-control  text-right" id="duration" name="duration" placeholder="0"></textarea>
					</div>
                    <div class="col-md-3">
						<label for="inputAddress">Advance Amount</label>
						<input type="number" class="form-control  text-right" id="amount" name="amount" placeholder="0.00"></textarea>
					</div>
					<div class="col-md-3">
						<label for="inputAddress">Installment</label>
						<input type="number" class="form-control  text-right" id="instalment" name="instalment" placeholder="0.00"></textarea>
					</div>
				</div>


				<div class="form-group row">
					<div class="col-md-6">
						<label for="inputcompany_name">W.E.F. Month</label>
						<input type="text" class="form-control" id="wef_month" placeholder="wef month" name="wef_month" required>
					</div>
					<div class="col-md-6">
						<label for="inputcompany_name">W.E.F. Year</label>
						<input type="text" class="form-control" id="wef_year" placeholder="wef year" name="wef_year" required>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-md-6">
						<label for="inputStart_date">Advance Start Date</label>
						<input class="form-control" id="start_date" placeholder="start date" name="start_date" required>
					</div>
                    <div class="col-md-6">
						<label for="">Advance Closing date</label>
						<input class="form-control" name="closing_date" id="closing_date" placeholder="closing date">
					</div>
				</div>

				<button type="submit" class="btn btn-primary">Submit</button>

			</form>
		</div>
	</div>
</div>
@endsection
@section('js')
	<script>
	 $(document).ready(function() {
	  $("#employee_id").change(function(event) {
        var name = $("#employee_id option:selected").text();
        name = name.replace(/[^a-zA-Z ]/g, '');
        console.log(name);
	   $("#emp_name").val(name);
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
	//   onSelect: function() {
	// 	calculateMonth();
    //         }
	 });
	</script>


	<script>
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
	</script>

	<script>
	function calculateMonth() {

		console.log('test');

		var start_date = $('#start_date').val();
		var closing_date = $('#closing_date').val();
		var start_date = new Date(start_date);
		var closing_date = new Date(closing_date);
		var months = (closing_date.getFullYear() - start_date.getFullYear()) * 12;
		months -= start_date.getMonth();
		months += closing_date.getMonth();
		console.log(months);
		$('#duration').val(months);
	}
	</script>


@endsection
