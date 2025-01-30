<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Form16a extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'receipt_no',
        'emp_code',
        'gross',
        'tax_deduct',
        'financial_year',
    ];
}
