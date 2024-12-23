<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdvanceType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type_name',
        'description',
        'max_amount',
        'type',
        'requires_recommender'
    ];
}
