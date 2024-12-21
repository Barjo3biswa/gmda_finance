<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject // Add implements JWTSubject here
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

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

    public function grossSalary($block_id, $status)
    {
        $hed = salaryHead::where('pay_head', 'Income')->pluck('id')->toArray();
        $block = salaryBlock::find($block_id);
        if ($block->is_finalized) {
            return salaryTrans::whereIn('sal_head_id', $hed)
                ->where('emp_id', $this->id)
                ->where('block_id', $block_id)
                ->sum('amount');
        } else {
            if ($status == 'all') {
                $stat = ['draft', 'temp'];
            } else {
                $stat = [$status];
            }
            return salaryTemp::whereIn('sal_head_id', $hed)
                ->where('emp_id', $this->id)
                ->where('block_id', $block_id)
                ->whereIn('status', $stat)
                ->sum('amount');
        }
    }

    public function deductSalary($block_id, $status)
    {
        $hed = salaryHead::where('pay_head', 'Deduction')->pluck('id')->toArray();
        $block = salaryBlock::find($block_id);
        if ($block->is_finalized) {
            return salaryTrans::whereIn('sal_head_id', $hed)
                ->where('emp_id', $this->id)
                ->where('block_id', $block_id)
                ->sum('amount');
        } else {
            if ($status == 'all') {
                $stat = ['draft', 'temp'];
            } else {
                $stat = [$status];
            }
            return salaryTemp::whereIn('sal_head_id', $hed)
                ->where('emp_id', $this->id)
                ->where('block_id', $block_id)
                ->whereIn('status', $stat)
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


    public function payCut()
    {
        $pay_cut_hd = salaryHead::where('pay_cut_hd', 1)->first();
        if ($pay_cut_hd) {
            return salaryTemp::where('sal_head_id', $pay_cut_hd->id)
                ->where('emp_id', $this->id)
                ->sum('amount');
        }
        return 0;
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id', 'user_id');
    }


}
