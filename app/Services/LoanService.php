<?php
// app/Services/UserService.php
namespace App\Services;

use App\Models\LoanMasterDetails;
use App\Models\salaryTemp;
use App\Models\User;

class LoanService
{
    public function generateFlatLoanData($loan)
    {
        if ($loan->no_of_installment > $loan->principal_installment) {
            $emi_amount = $loan->monthly_emi;
            // dd($emi_amount);
            if (($loan->adj_interest_emi_in == 'F' && $loan->principal_installment == 0) || ($loan->adj_interest_emi_in == 'L' && $loan->no_of_installment == ($loan->principal_installment + 1))) {
                $emi_amount = $loan->adj_emi;
            }
            //////////additional condition to prevent negative value//////
            if ($loan->outstanding_principal < $emi_amount) {
                $emi_amount = $loan->outstanding_principal;
            }
            /////////////////////// Ends Here ////////////////////////////
            ///////////////////cut rest amount if inst no is last/////////////////////////
            if ($loan->no_of_installment == ($loan->principal_installment + 1)) {
                if (($loan->outstanding_principal - $emi_amount) > 0) {
                    $emi_amount = $loan->outstanding_principal;
                }
            }
            //////////////////////////////////////////////////////////////////////////////
            $data = [
                'loan_id' => $loan->id,
                'emi' => $emi_amount,
                'principal_amount' => $emi_amount,
                'intrest_amount' => null,
                'installment_no' => $loan->principal_installment + 1,
            ];
        } elseif ($loan->no_of_installment_interest > $loan->interest_installment) {
            // dd("here");
            $emi_amount = $loan->interest_emi;
            if (($loan->adj_interest_emi_in == 'F' && $loan->interest_installment == 0) || ($loan->adj_interest_emi_in == 'L' && $loan->no_of_installment_interest == ($loan->interest_installment + 1))) {
                $emi_amount = $loan->adj_interest_emi;
            }
            //////////additional condition to prevent negative value//////
            if ($loan->outstanding_interest_amount < $emi_amount) {
                $emi_amount = $loan->outstanding_interest_amount;
            }
            /////////////////////// Ends Here ////////////////////////////
            ///////////////////cut rest amount if inst no is last/////////////////////////
            if ($loan->no_of_installment_interest == ($loan->interest_installment + 1)) {
                if (($loan->outstanding_interest_amount - $emi_amount) > 0) {
                    $emi_amount = $loan->outstanding_interest_amount;
                }
            }
            //////////////////////////////////////////////////////////////////////////////
            $data = [
                'loan_id' => $loan->id,
                'emi' => $emi_amount,
                'principal_amount' => null,
                'intrest_amount' => $emi_amount,
                'installment_no' => $loan->interest_installment + 1,
            ];
        }

        return $data;
    }

    public function generateReducingLoanData($loan)
    {
        $installment_no = $loan->principal_installment + 1;
        $emi_details = LoanMasterDetails::where('loan_id', $loan->id)->where('payment_no', $installment_no)->first();
        // dd($emi_details);
        $emi_amount = $emi_details->payment;

        $data = [
            'loan_id' => $loan->id,
            'emi' => $emi_amount,
            'principal_amount' => $emi_details->principal,
            'intrest_amount' => $emi_details->interest,
            'installment_no' => $installment_no
        ];
        return $data;
    }


    public function generateJsonData($loan, $data, $salary_head)
    {
        $check_is_exist = salaryTemp::where('emp_id', $loan->user->id)->where('sal_head_id', $salary_head)->first();
        $old_json = $check_is_exist->detail_json;
        $new_data = $data;
        $old_data = json_decode($old_json, true);
        if (!is_array($old_data) || empty($old_data)) {
            $json_data = json_encode($new_data);
        } else {
            if (!isset($old_data[0])) {
                $old_data = [$old_data];
            }
            if (!isset($new_data[0])) {
                $new_data = [$new_data];
            }
            $combined_data = array_merge($old_data, $new_data);
            $json_data = json_encode($combined_data);
        }
        return $json_data;
    }

}
