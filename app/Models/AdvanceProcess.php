<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Scopes\MonthYearFilterScope;
use App\Traits\Scopes\ActiveScope;

class AdvanceProcess extends Model
{
    use HasFactory;
    use MonthYearFilterScope;
    use ActiveScope;

    public static $ACTIVE = 1;
    public static $INACTIVE = 0;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'user_id');
    }

    //advance
    public function advances()
    {
        return $this->belongsTo(Advance::class, 'advance_id');
    }

    public function advanceType()
    {
        return $this->belongsTo(AdvanceType::class, 'loan_head_id');
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
