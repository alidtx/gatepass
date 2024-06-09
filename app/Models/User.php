<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Kyslik\ColumnSortable\Sortable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Modules\User\Entities\UserSource;
use Modules\User\Entities\UserAssignedRole;
use Modules\Departments\Entities\Department;
use Illuminate\Database\Eloquent\Builder;
use Modules\User\Entities\FirebaseToken;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasFactory, Sortable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at', 
        'updated_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function userAssignedRoles()
    {
        return $this->hasMany(UserAssignedRole::class ,'user_id','id')->with('roleInfo');
    }


    public function roleInfo()
    {
        return $this->belongsTo(Role::class ,'role_id','id')->with('permissions');
    }

    public function department()
    {
        return $this->belongsTo(Department::class ,'department_id','id');
    }

    public function userSource()
    {
        return $this->belongsTo(UserSource::class ,'department_id','id');
    }

    public function scopeUserStatus($query)
    {
        return $query->whereUserStatus(1);
    }

    public function scopeRoleId($query)
    {
        return $query->where('role_id', '!=', 1);
    }

    public function scopeFilter($query, array $filters = []): Builder
    {
        if (isset($filters['name'])) {
            $query->where('users.name', 'like', '%' . $filters['name']. '%');
        }

        if (isset($filters['email'])) {
            $query->where('users.email', 'like', '%' . $filters['email']. '%');
        }

        return $query;
    }

    public function firebase(): HasMany
    {
        return $this->hasMany(FirebaseToken::class, 'user_id', 'id');
    }
}
