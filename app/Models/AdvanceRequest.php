<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdvanceRequest extends Model
{
    use HasFactory, softDeletes;
    protected $guarded = ['id'];


    protected $casts = [
        'requested_date' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class,'user_id','user_id');
    }

    public function advanceType()
    {
        return $this->belongsTo(AdvanceType::class);
    }

    public function approvingAuthority()
    {
        return $this->belongsTo(AdvanceApprovingAuthority::class);
    }

    public function advanceApprovals()
    {
        return $this->hasMany(AdvanceApproval::class, 'advance_request_id');
    }
}
