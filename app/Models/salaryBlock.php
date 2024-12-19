<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class salaryBlock extends Model
{
    use HasFactory;
    protected $guarded = ["id"];
    use SoftDeletes;

    public function scopeActive($query)
    {
        return $query->where('sal_process_status', 'Unblock');
    }

    public function isAdvanceProcessed(): bool
    {
        return $this->advance_processed_at ? true : false;
    }

    public function isLicProcessed(): bool
    {
        return $this->lic_processed_at ? true : false;
    }
}
