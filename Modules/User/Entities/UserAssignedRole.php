<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;

class UserAssignedRole extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'role_id'];

    public function roleInfo()
    {
        return $this->belongsTo(Role::class ,'role_id','id')->with('permissions');
    }

}
