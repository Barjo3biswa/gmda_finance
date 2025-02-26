<style>
    @media print {
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .alert,
        .navbar,
        .footer,
        .no-print {
            display: none;
            /* Hide unnecessary elements like navbars and footers */
        }

        .container {
            margin: 0 auto;
            width: 100%;
            max-width: 100%;
        }

        .heading {
            font-weight: bold;
        }

        hr {
            margin: 10px 0;
        }

        .btn {
            display: none;
            /* Hide buttons */
        }

        .product-payment-inner-st span {
            padding: 8px 0 !important;
        }

        .header hr {
            margin-bottom: 8px !important;
        }

        .media-flex {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

    }
</style>
<div class="col-md-2">
</div>
<div class="col-md-8">
    <div class="row">
        <div class="col-md-3">
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
                {{-- <div class="col-md-6"><span class="heading">Email Address:</span>
                    {{ $emp_details->email ?? "NA"}}</div> --}}
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
                    <span>{{ $cl->salary_head_name }}</span><span>{{ number_format($cl->amount, 2) }}</span>
                </div>
            @endforeach
        </div>

        <div class="col-md-6">
            <div class="col-md-12" style="margin: 8px 0;">
                <span class="heading">Deductions</span>
            </div>
            @foreach ($deductions as $cl)
                <div class="col-md-12" style="display: flex;justify-content: space-between;">
                    <span>{{ $cl->salary_head_name }}</span><span>{{ number_format($cl->amount, 2) }}</span>
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
                <span class="heading">{{ number_format($salary->gross, 2) }}</span>
            </div>
            <div class="col-md-6" style="display: flex;justify-content: end;">
                <span class="heading">{{ number_format($salary->deduction, 2) }}</span>
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
                <span class="heading">{{ number_format($salary->net, 2) }}</span>
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