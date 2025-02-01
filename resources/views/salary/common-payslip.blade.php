<div class="col-md-2">
</div>
<div class="col-md-8">
    <div class="row">
        <div class="col-md-3" style="background: #f5f5f5;">
            <div class="row" style="display: flex; justify-content: center;">
                <img class="main-logo" src="{{ asset('logo/logo.png') }}" alt="" style="max-width: 120px;" />
            </div>
            <div class="row" style="display: flex; justify-content: center; text-align: center;">
                <h5>Guwahati Metropolitan Development Authority</h5>
            </div>
        </div>
        <div class="col-md-9 header" style="padding-right: 0;">
            <h5>PAY SLIP for
                {{ \Carbon\Carbon::createFromDate(null, $salary_block->month)->format('F') }},
                {{ $salary_block->year }}
                Financial Year:
                {{ $salary_block->month >= 4 ? $salary_block->year . '-' . ($salary_block->year + 1) : ($salary_block->year - 1) . '-' . $salary_block->year }}
            </h5>
            <hr>
            <div class="row media-flex">
                <div class="col-md-6"><span class="heading">Emp Code:</span>
                    {{ $emp_details->emp_code }}</div>
                <div class="col-md-6"><span class="heading">Name:</span>
                    {{ $emp_details->name }}
                </div>
            </div>
            <div class="row media-flex">
                <div class="col-md-6"><span class="heading">Desig:</span>
                    {{ $emp_details->employee->designation->name ?? 'NA'}}
                </div>
                <div class="col-md-6"><span class="heading">Dept.:</span>
                    {{ $emp_details->employee->department->name ?? 'NA'}}</div>
            </div>
            <div class="row media-flex">
                <div class="col-md-6"><span class="heading">Bank A/C No.:</span>
                    {{ $emp_details->employee->bank_ac_no ?? "NA"}}
                </div>
                <div class="col-md-6"><span class="heading">PF A/C No.:</span>
                    {{ $emp_details->employee->pf_no ?? "NA"}}
                </div>
            </div>
            <div class="row media-flex">
                <div class="col-md-6">
                    <span class="heading">PAN:</span>
                    {{ $emp_details->employee->pan_no ?? "NA"}},
                    <span class="heading">PRAN:</span>
                    {{ $emp_details->employee->pran_no ?? "NA"}}
                </div>
                <div class="col-md-6"><span class="heading">Email Address:</span>
                    {{ $emp_details->email ?? "NA"}}</div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row media-flex">
        <div class="col-md-6">
            <div class="col-md-12" style="margin: 8px 0;">
                <span class="heading">Claims</span>
            </div>
            @foreach ($claims as $cl)
                <div class="col-md-12" style="display: flex;justify-content: space-between;gap:20px;">
                    <span>{{ $cl->salary_head_name }}</span><span>{{ $cl->amount }}</span>
                </div>
            @endforeach
        </div>

        <div class="col-md-6">
            <div class="col-md-12" style="margin: 8px 0;">
                <span class="heading">Deductions</span>
            </div>
            @foreach ($deductions as $cl)
                <div class="col-md-12" style="display: flex;justify-content: space-between;">
                    <span>{{ $cl->salary_head_name }}</span><span>{{ $cl->amount }}</span>
                </div>
            @endforeach
        </div>
    </div>
    <div class="row">
        <hr>
        <div class="col-md-12">
            <div class="col-md-2" style="margin: 4px 0">
                <span class="heading">Total:</span>
            </div>
            <div class="col-md-4" style="display: flex;justify-content: end;">
                <span class="heading">{{ $salary->gross }}</span>
            </div>
            <div class="col-md-6" style="display: flex;justify-content: end;">
                <span class="heading">{{ $salary->deduction }}</span>
            </div>
        </div>
    </div>
    <div class="row">
        <hr>
        <div class="col-md-12">
            <div class="col-md-6">
            </div>
            <div class="col-md-6" style="display: flex;justify-content: space-between;">
                <span class="heading">NET PAY:</span>
                <span class="heading">{{ $salary->net }}</span>
            </div>
        </div>
    </div>
    <div class="row">
        <span>Amount in words:</span>
        <span class="heading">
            {{ ucfirst(collect(explode(' ', NumberFormatter::create('en', NumberFormatter::SPELLOUT)->format($salary->net)))
    ->map(fn($word) => ucfirst($word))
    ->join(' ')) }}
        </span>
    </div>
</div>