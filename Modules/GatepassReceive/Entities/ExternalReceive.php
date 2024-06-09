<?php

namespace Modules\GatepassReceive\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Gatepass\Entities\Gatepass;
use Modules\Party\Entities\Party;
use Modules\Departments\Entities\Department;
use Modules\Location\Entities\Location;
use Kyslik\ColumnSortable\Sortable;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class ExternalReceive extends Model
{
    use HasFactory, SoftDeletes, Sortable;

    protected $hidden = ['created_at', 'updated_at'];
    protected $guarded =[];

    public function items()
    {
        return $this->hasMany(ExternalReceiveItem::class, 'external_receive_id','id')->with('itemInfo', 'unitInfo');
    }
    
    public function toLocationInfo()
    {
        return $this->belongsTo(Location::class, 'to_location_id');
    }

    public function receivedByUser()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function toDepartmentInfo()
    {
        return $this->belongsTo(Department::class, 'to_department_id');
    }

    public function documents()
    {
        return $this->hasMany(ExternalReceiveDocument::class, 'external_receive_id', 'id');
    }
    
}
