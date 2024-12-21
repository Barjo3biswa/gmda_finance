<?php

namespace App\Imports;

use App\Models\salaryTemp;
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

    public function __construct($headName)
    {
        $this->headName = $headName;
    }

    public function collection(Collection $rows)
    {
        DB::beginTransaction();

        try {
            foreach ($rows as $row) {
                $data = [

                ];
                salaryTemp::where('emp_code', $row['emp_code'])
                    ->where('sal_head_id', $this->headName)
                    ->update(['amount' => $row['amount']]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
