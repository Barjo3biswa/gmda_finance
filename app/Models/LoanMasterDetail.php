<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanMasterDetail extends Model
{
    use HasFactory;
    protected $table = 'loan_master_details';
    protected $fillable = [
        'emp_id',
        'emp_code',
        'loan_type_id',
        'loan_id',
        'payment_no',
        'payment_date',
        'begining_balance',
        'payment',
        'interest',
        'principal',
        'ending_balance',
    ];
}
