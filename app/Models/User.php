<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject // Add implements JWTSubject here
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'name',
        'email',
        'password',
        'permission_ids',
        'role_ids'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function permissions()
    {
        $permissionIds = json_decode($this->permission_ids, true) ?? [];
        return AuthPermission::whereIn('id', $permissionIds)->get();
    }

    public function roles()
    {
        $roleIds = json_decode($this->role_ids, true) ?? [];
        return AuthRole::whereIn('id', $roleIds)->get();
    }

    public function grossSalary($block_id)
    {
        $hed = salaryHead::where('pay_head', 'Income')->pluck('id')->toArray();
        $block = salaryBlock::find($block_id);
        if ($block->is_finalized) {
            return salaryTrans::whereIn('sal_head_id', $hed)
                ->where('emp_id', $this->id)
                ->where('block_id', $block_id)
                ->sum('amount');
        } else {
            return salaryTemp::whereIn('sal_head_id', $hed)
                ->where('emp_id', $this->id)
                ->where('block_id', $block_id)
                ->sum('amount');
        }


    }

    public function deductSalary($block_id)
    {
        $hed = salaryHead::where('pay_head', 'Deduction')->pluck('id')->toArray();
        $block = salaryBlock::find($block_id);
        if ($block->is_finalized) {
            return salaryTrans::whereIn('sal_head_id', $hed)
                ->where('emp_id', $this->id)
                ->where('block_id', $block_id)
                ->sum('amount');
        } else {
            return salaryTemp::whereIn('sal_head_id', $hed)
                ->where('emp_id', $this->id)
                ->where('block_id', $block_id)
                ->sum('amount');
        }

    }

    public function getHeadAmount($block_id, $head_id)
    {
        $block = salaryBlock::find($block_id);
        if ($block->is_finalized) {
            return salaryTrans::where('sal_head_id', $head_id)
                ->where('emp_id', $this->id)
                ->where('block_id', $block_id)
                ->sum('amount');
        } else {
            return salaryTemp::where('sal_head_id', $head_id)
                ->where('emp_id', $this->id)
                ->where('block_id', $block_id)
                ->sum('amount');
        }
    }


    // public function attendanceSummery(){

    // }


}
