<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Scopes\MonthYearFilterScope;
use App\Traits\Scopes\ActiveScope;

class LicProcessData extends Model
{
    use HasFactory;

    use MonthYearFilterScope, ActiveScope;
    protected $guarded = [];
    protected $casts = [
        'processed_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    //policy
    public function policy()
    {
        return $this->belongsTo(Policy::class, 'policy_id');
    }
    public function isProcessingAllowed()
    {
        return $this->process_allowed;
    }
    // processed_by
    public function processed_by()
    {
        return $this->belongsTo(Employee::class, 'processed_by_id');
    }
    
}
