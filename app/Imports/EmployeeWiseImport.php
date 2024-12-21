<?php

namespace App\Imports;

use App\Models\salaryHead;
use App\Models\salaryHeadAmountDistribution;
use App\Models\salaryTemp;
use App\Models\User;
use DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class EmployeeWiseImport implements ToModel, WithHeadingRow
{
    public function __construct()
    {
        HeadingRowFormatter::default('none');
    }
    public function model(array $row)
    {

        // dd($row['Grade Pay']);
        DB::beginTransaction();
        try {
            $salary_head = salaryHead::get();
            foreach ($salary_head as $hd) {
                // dump($hd->code, $row[$hd->code]);
                // salaryTemp::where('emp_code', $row['emp_code'])
                //     ->where('sal_head_id', $hd->id)
                //     ->update(['amount' => $row[$hd->code]]);

                $emp_id = User::where('emp_code', $row['emp_code'])->first()->id;
                salaryHeadAmountDistribution::updateOrCreate(
                    [
                        'emp_id' => $emp_id,
                        'emp_code' => $row['emp_code'],
                        'sal_head_id' => $hd->id,
                    ],
                    [
                        'salary_head_code' => $hd->code,
                        'salary_head_name' => $hd->name,
                        'pay_head' => $hd->pay_head,
                        'amount' => $row[$hd->code],
                        'status' => 'Active',
                    ]
                );
            }
            DB::commit();
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            throw $e;
        }
    }
}
