<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendanceSummery extends Model
{
    protected $guarded = ['id'];
    use HasFactory;
    use SoftDeletes;

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
