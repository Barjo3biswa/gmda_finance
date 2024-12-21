<?php

namespace App\Imports;

use App\Models\salaryBlock;
use App\Models\salaryHead;
use App\Models\salaryKss;
use App\Models\salaryTemp;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

// class kssFileImport implements ToModel, WithHeadingRow
class kssFileImport implements ToCollection, WithHeadingRow
{
    protected $headName;

    // public function __construct($headName)
    // {
    //     $this->headName = $headName;
    // }

    public function collection(Collection $rows)
    {
        DB::beginTransaction();

        try {
            $salary_head = salaryHead::where('id', 22)->first();
            $salary_block = salaryBlock::where('sal_process_status', 'Unblock')->first();
            salaryKss::where([
                'month' => Carbon::createFromDate(null, $salary_block->month)->format('F'),
                'year' => $salary_block->year
            ])->delete();
            foreach ($rows as $row) {
                $emp_id = User::where('emp_code', $row['emp_id'])->first()->id;
                $row['total'] = floatval($row['total']);
                $row['loan_amount'] = floatval($row['loan_amount']);
                $row['interest'] = floatval($row['interest']);
                $row['subscrptn'] = floatval($row['subscrptn']);
                $row['recovery'] = floatval($row['recovery']);
                $data = [
                    'emp_code' => $row['emp_id'],
                    'sal_head_id' => $salary_head->id,
                    'salary_head_code' => $salary_head->code,
                    'salary_head_name' => $salary_head->name,
                    'month' => $salary_block->month,
                    'year' => $salary_block->year,
                    'block_id' => $salary_block->id,
                    'pay_head' => 'deduction',
                    'working_days' => 30,
                    'status' => 'draft',
                    'amount' => $row['total'],
                    'last_amount' => $row['total'],
                ];

                salaryTemp::updateOrCreate(
                    [
                        'emp_id' => $emp_id,
                        'sal_head_id' => $salary_head->id,
                    ],
                    $data
                );

                $kss_data = [
                    'emp_id' => $emp_id,
                    'emp_code' => $row['emp_id'],
                    'loan_amount' => $row['loan_amount'],
                    'interest' => $row['interest'],
                    'subscrptn' => $row['subscrptn'],
                    'recovery' => $row['recovery'],
                    'total' => $row['total'],
                    'month' => Carbon::createFromDate(null, $salary_block->month)->format('F'),
                    'year' => $salary_block->year,
                    'status' => 1,
                ];
                salaryKss::create($kss_data);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
