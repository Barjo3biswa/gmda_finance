<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class salaryTemp extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    use SoftDeletes;

    public function salaryHead()
    {
        return $this->hasOne(salaryHead::class, 'id', 'sal_head_id');
    }
}
