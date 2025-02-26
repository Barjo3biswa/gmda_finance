<html>

<head>
    <title>Pay Slip For All</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 2px;
            border: 1px solid #ddd;
        }

        .header-table {
            width: 100%;
            background-color: #f5f5f5;
        }

        .heading {
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    @php
        use App\Models\salaryBlock;
        use App\Models\salaryMaster;
        use App\Models\User;
        $count = $users->count();
    @endphp

    @foreach ($users as $key => $emp_details)
        @php
            $salary_block = salaryBlock::where('id', $block_id)->first();
            $emp_details = User::with('employee')->where('id', $emp_details->id)->first();

            if ($salary_block) {
                $salary = salaryMaster::with('salaryTrans')->where('emp_id', $emp_details->id)
                    ->where('sal_block_id', $block_id)->first();

                if ($salary) {
                    $claims = $salary->salaryTrans->where('pay_head', 'Income')->where('amount', '!=', 0)->values();
                    $deductions = $salary->salaryTrans->where('pay_head', 'Deduction')->where('amount', '!=', 0)->values();
                } else {
                    $claims = collect();
                    $deductions = collect();
                }
            } else {
                $salary = collect();
                $claims = collect();
                $deductions = collect();
            }
            // dd($claims);
        @endphp

        <!-- Header -->

        <table class="header-table">
            <tr>
                <td style="width: 30%; text-align: center;" rowspan=5>
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('logo/logo.png'))) }}"
                        alt="Logo" style="max-width: 120px;">
                    <h5>Guwahati Metropolitan Development Authority</h5>
                </td>
                <td style="width: 70%;" colspan=2>
                    <h5>PAY SLIP for
                        {{ \Carbon\Carbon::createFromDate(null, $salary_block->month)->format('F') }},
                        {{ $salary_block->year }}
                        Financial Year:
                        {{ $salary_block->month >= 4 ? $salary_block->year . '-' . ($salary_block->year + 1) : ($salary_block->year - 1) . '-' . $salary_block->year }}
                    </h5>
                </td>
            </tr>
            <tr>
                <td><span>Emp Code:</span> {{ $emp_details->emp_code }}</td>
                <td><span>Name:</span> {{ $emp_details->name }}</td>
            </tr>
            <tr>
                <td><span>Desig:</span> {{ $emp_details->employee->designation->name ?? 'NA' }}</td>
                <td><span>Dept.:</span> {{ $emp_details->employee->department->name ?? 'NA' }}</td>
            </tr>
            <tr>
                <td><span>Bank A/C No.:</span> {{ $emp_details->employee->bank_ac_no ?? "NA" }}</td>
                <td><span>PF A/C No.:</span> {{ $emp_details->employee->pf_no ?? "NA" }}</td>
            </tr>
            <tr>
                <td><span>PAN:</span> {{ $emp_details->employee->pan_no ?? "NA" }}</td>
                <td><span>Email Address:</span> {{ $emp_details->email ?? "NA" }}</td>
            </tr>
        </table>

        <br>

        <!-- Salary Details -->
        <table>
            <tr>
                <th>Claims</th>
                <th class="text-right">Amount</th>
                <th>Deductions</th>
                <th class="text-right">Amount</th>
            </tr>
            @foreach(range(0, max($claims->count(), $deductions->count()) - 1) as $index)
                <tr>
                    <td>{{ $claims[$index]->salary_head_name ?? '' }}</td>
                    <td class="text-right">{{ number_format($claims[$index]->amount ?? 0, 2) }}</td>
                    <td>{{ $deductions[$index]->salary_head_name ?? '' }}</td>
                    <td class="text-right">{{ number_format($deductions[$index]->amount ?? 0, 2) }}</td>
                </tr>
            @endforeach

            <tr>
                <td class="heading">Total:</td>
                <td class="text-right">{{ number_format($salary->gross, 2) }}</td>
                <td class="heading">Total:</td>
                <td class="text-right">{{ number_format($salary->deduction, 2) }}</td>
            </tr>
        </table>

        <br>

        <!-- Net Pay -->
        <table>
            <tr>
                <td class="heading">NET PAY:</td>
                <td class="text-right">{{ number_format($salary->net, 2) }}</td>
            </tr>
        </table>

        <br>

        <!-- Amount in Words -->
        <p>
            <span>Amount in words:</span>
            <span class="heading">
                {{-- {{ ucfirst(collect(explode(' ', NumberFormatter::create('en',
                NumberFormatter::SPELLOUT)->format($salary->net)))
                ->map(fn($word) => ucfirst($word))
                ->join(' ')) }} --}}
                {{ \App\Helpers\commonHelper::number_to_words($salary->net) }}
            </span>
        </p>
        @if($count - 1 != $key)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>

</html>