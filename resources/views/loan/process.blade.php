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
                            <div class="card">
                                <div class="card-header">
                                    <i class="fa fa-filter"></i> Filter
                                    <a class="btn btn-sm btn-outline-primary float-right mr-1"
                                        href="{{ route('loan.processed_loan_list') }}">
                                        <i class="fa fa-list"></i> View processed loan data
                                    </a>
                                </div>
                                <div class="card-body">
                                    <form method="get" action="">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="name">Loan Type</label>
                                                    <select name="advance_type_id" id="advance_type_id" class="form-control"
                                                        required>
                                                        <option value="">--SELECT--</option>
                                                        @foreach ($advanceTypes as $advanceType)
                                                            <option value="{{ $advanceType->id }}"
                                                                {{ old('advance_type_id') == $advanceType->id ? 'selected' : '' }}>
                                                                {{ $advanceType->type_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="name">Department</label>
                                                    <select name="department_id" id="department"
                                                        class="form-control select2">
                                                        <option value="">--All--</option>
                                                        @foreach ($departments as $key => $department)
                                                            <option value="{{ $department->id }}"
                                                                {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                                                {{ $department->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="name">Employee</label>
                                                    <select name="employee_id" id="categories" class="form-control select2">
                                                        <option value="">--All--</option>
                                                        @foreach ($emp as $employee)
                                                            <option value="{{ $employee->id }}">
                                                                {{ $employee->full_name_with_code }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="name">Month</label>
                                                    <select name="installment_month" id="installment_month"
                                                        class="form-control" required>
                                                        <option value="">Select Month</option>
                                                        <option value="1"
                                                            {{ old('installment_month') == '1' ? 'selected' : '' }}>January
                                                        </option>
                                                        <option value="2"
                                                            {{ old('installment_month') == '2' ? 'selected' : '' }}>
                                                            February
                                                        </option>
                                                        <option value="3"
                                                            {{ old('installment_month') == '3' ? 'selected' : '' }}>March
                                                        </option>
                                                        <option value="4"
                                                            {{ old('installment_month') == '4' ? 'selected' : '' }}>April
                                                        </option>
                                                        <option value="5"
                                                            {{ old('installment_month') == '5' ? 'selected' : '' }}>May
                                                        </option>
                                                        <option value="6"
                                                            {{ old('installment_month') == '6' ? 'selected' : '' }}>June
                                                        </option>
                                                        <option value="7"
                                                            {{ old('installment_month') == '7' ? 'selected' : '' }}>July
                                                        </option>
                                                        <option value="8"
                                                            {{ old('installment_month') == '8' ? 'selected' : '' }}>August
                                                        </option>
                                                        <option value="9"
                                                            {{ old('installment_month') == '9' ? 'selected' : '' }}>
                                                            September</option>
                                                        <option value="10"
                                                            {{ old('installment_month') == '10' ? 'selected' : '' }}>
                                                            October
                                                        </option>
                                                        <option value="11"
                                                            {{ old('installment_month') == '11' ? 'selected' : '' }}>
                                                            November</option>
                                                        <option value="12"
                                                            {{ old('installment_month') == '12' ? 'selected' : '' }}>
                                                            December</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="name">Year</label>
                                                    <select name="installment_year" id="installment_year"
                                                        class="form-control" required>
                                                        <option value="">Select Year</option>
                                                        @for ($year = 2024; $year <= 2035; $year++)
                                                            <option value="{{ $year }}"
                                                                {{ old('installment_year') == $year ? 'selected' : '' }}>
                                                                {{ $year }}
                                                            </option>
                                                        @endfor
                                                    </select>

                                                </div>
                                            </div>
                                        </div>

                                        <button type="submit" class='btn btn-primary text-white btn-sm'>Search </button>

                                    </form>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <i class="fa fa-list-alt"></i> Pending Loan List
                                </div>
                                <div class="card-body">

                                    @if ($message = Session::get('success'))
                                        <div class="alert alert-success">
                                            <p>{{ $message }}</p>
                                        </div>
                                    @endif
                                    @if (!$salarystatus)
                                        <div class="alert alert-warning">
                                            <p>No active salary block found. Please create or activate a salary block first.
                                            </p>
                                        </div>
                                    @else
                                        <form action="{{ route('loan.process_loan_data') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="salary_block_id" value="{{ $salarystatus->id }}">
                                            <table class="table table-bordered table-sm" id="employee_table">
                                                <tbody>
                                                    <tr>
                                                        <th>
                                                            <input type="checkbox" id="checkAll" />
                                                        </th>
                                                        <th>SL.</th>
                                                        <th>Employee Name</th>
                                                        <th>Loan Type</th>
                                                        <th>Salary Head</th>
                                                        <th>Principal Amount</th>
                                                        <th>Outstanding Principal</th>
                                                        <th>Interest Amount</th>
                                                        <th>Outstanding Interest Amount</th>
                                                        <th>Principal EMI</th>
                                                        <th>Interest EMI</th>
                                                        <!-- <th>Month</th>
                                                        <th>Year</th> -->
                                                    </tr>
                                                </tbody>
                                                <tbody>
                                                    @forelse ($advances as $key => $advance)
                                                        <tr>
                                                            <td>
                                                                @if ($advance->advancesprocessdata)
                                                                    @if ($salarystatus->isAdvanceProcessed())
                                                                        <strong class="text-danger">Processed</strong>
                                                                    @else
                                                                        <strong class="text-info">Added for
                                                                            processing</strong>
                                                                    @endif
                                                                @else
                                                                    <input name="datas[{{ $key }}][advance_id]"
                                                                        type="checkbox" value="{{ $advance->id }}"
                                                                        onclick="chkBox(this)"
                                                                        id="checkbox-{{ $advance->id }}" />
                                                                    <input name="allrows[{{ $key }}][advance_id]"
                                                                        type="checkbox" value="{{ $advance->id }}"
                                                                        id="checkbox-{{ $advance->id }}" checked
                                                                        hidden />
                                                                @endif
                                                            </td>
                                                            <td>{{ $key + 1 }}</td>
                                                            <td>{{ optional($advance->employee)->first_name ?? 'N/A' }}
                                                                ({{ optional($advance->employee)->code ?? 'N/A' }})</td>
                                                            <td>{{ optional($advance->advanceType)->type_name ?? 'N/A' }}
                                                            </td>
                                                            <td>
                                                                {{ optional($advance->salhead)->name ?? 'N/A' }}
                                                                <input type="hidden"
                                                                    name="datas[{{ $key }}][loan_head_id]"
                                                                    value={{ $advance->sal_block_id }}>
                                                            </td>
                                                            <td class="text-right">
                                                                {{ $advance->principal_amount }}
                                                            </td>
                                                            <td class="text-right">
                                                                {{ $advance->outstanding_principal }}
                                                            </td>
                                                            <td class="text-right">
                                                                {{ $advance->interest_amount }}
                                                            </td>
                                                            <td class="text-right">
                                                                {{ $advance->outstanding_interest_amount }}
                                                            </td>
                                                            <td class="text-right">
                                                                <input type="number"
                                                                    name="datas[{{ $key }}][monthly_premium]"
                                                                    value={{ $advance->principal_installment }}
                                                                    disabled="disabled">
                                                            </td>
                                                            <td class="text-right">
                                                                <input type="number"
                                                                    name="datas[{{ $key }}][monthly_premium]"
                                                                    value={{ $advance->interest_emi }}
                                                                    disabled="disabled">
                                                            </td>
                                                            <!-- <td>{{ $advance->start_date }}</td>
                                                        <td>{{ $advance->closing_date }}</td> -->
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="7" class="text-center text-danger">No Data
                                                                Found</td>
                                                        </tr>
                                                    @endempty
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="6" class="text-right">Total</th>
                                                    <th class="text-right">
                                                        @php
                                                            //echo number_format((float)$advance->sum("principal_installment"), 2);
                                                        @endphp
                                                    </th>
                                                    <th colspan="2"></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        {{ $advances->links() }}
                                        <input type="submit" class="btn btn-primary btn-sm"
                                            value="Submit for Processing" id="process">
                                        <a class="btn btn-outline-primary btn-sm"
                                            href="{{ route('advance.processed_data_list') }}">
                                            <i class="fa fa-list"></i> View processed Advance data
                                        </a>
                                    </form>
                                @endif
                                {{-- {{$policies->appends(request()->all())->links()}} --}}
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
    disableProcessButton = function() {
        if ($('#employee_table input:checkbox:checked').not("#checkAll").length > 0) {
            $('#process').prop('disabled', false);
        } else {
            $('#process').prop('disabled', true);
        }
    }
    disableProcessButton();
    $(document).ready(function() {
        $("#checkAll").click(function() {
            $('#employee_table input:checkbox').not(this).prop('checked', this.checked);
            disableProcessButton();
        });
        $("#process").click(function() {
            if ($("#employee_table input:checkbox:checked").length == 0) {
                alert('Please select at least one policy');
                return false;
            }
        });
        // toggle selectAll checkbox
        $('#employee_table input:checkbox').click(function() {
            if ($('#employee_table input:checkbox:checked').length == $(
                    '#employee_table input:checkbox').length) {
                $('#checkAll').prop('checked', true);
                //enable all input number of the table
                $('.enableme').prop('disabled', false);

            } else {
                $('#checkAll').prop('checked', false);
                // $('.enableme').prop('disabled', true);

            }
            disableProcessButton();
        });
    });

    //     $("#checkall").click(function (){
    //     if ($("#checkall").is(':checked')){
    //        $(".checkboxes").each(function (){
    //           $(this).prop("checked", true);
    //           });
    //        }else{
    //           $(".checkboxes").each(function (){
    //                $(this).prop("checked", false);
    //           });
    //        }
    // });

    function chkBox(obj) {
        console.log(obj);
        $(obj).parents("tr").find("input[type='number']").prop('disabled', !$(obj).is(":checked"));
        $(obj).parents("tr").find("input[type='hidden']").prop('disabled', !$(obj).is(":checked"));
    }
</script>
@endsection
