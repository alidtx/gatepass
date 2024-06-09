<?php

namespace Modules\Gatepass\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Gatepass\Entities\GatepassItem;
use Modules\Party\Entities\Party;
use Modules\Location\Entities\Location;
use Modules\Gatecheck\Entities\Gatecheck;
use Modules\Departments\Entities\Department;
use Modules\Gatepass\Entities\GatepassDocument;
use Illuminate\Database\Eloquent\Builder;
use Kyslik\ColumnSortable\Sortable;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class Gatepass extends Model
{
    use HasFactory, SoftDeletes, Sortable;

    protected $fillable = [];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function scopeFilter($query, array $filters = []): Builder
    {
        if (isset($filters['gate_pass_no'])) {
            $query->where('gate_pass_no', 'like', '%' . $filters['gate_pass_no']. '%');
        }

        if(isset($filters['type_id'])){
            $query->where('type_id', $filters['type_id']);
        }

        if (isset($filters['from_location_id'])) {
            $query->where('from_location_id', $filters['from_location_id']);
        }

        if (isset($filters['to_location_id'])) {
            $query->where('to_location_id', $filters['to_location_id']);
        }
        
        if(isset($filters['skip_id'])){
            $query->where('id', '<>', $filters['skip_id']);
        }

        if(isset($filters['status'])){
            $query->whereStatus($filters['status']);
        }

        // Filter for Gatecheck module list api
        if (isset($filters['module']) && $filters['module'] == 'gatecheck' && isset($filters['gatecheck_from_location_id'])) {
            $query->where(function($q) {
                $q->where('status', 4)
                    ->orWhere(function($q) {
                        $q->where('status', 4)
                            ->whereHas('typeInfo', function($q) {
                                $q->whereName('Internal');
                            });
                        })
                        ->orWhere(function($q) {
                            $q->where('status', 4)
                                ->whereHas('typeInfo', function($q) {
                                    $q->whereIn('name', ['External', 'Returnable', 'Receive-Return']);
                            });
                        });
            })
            ->whereNotExists(function ($query) { // load gatepasses which is not present in gatecheck
                $query->select(DB::raw(1))
                      ->from('gatechecks')
                      ->whereRaw('gatechecks.gatepass_id = gatepasses.id');
            })
            ->where('from_location_id', $filters['gatecheck_from_location_id']); // load gatepasses which matached gatecheck_from_location_id with gatepass from_location_id
        }

        // Filter for Gatepass module list api
        if (isset($filters['module']) && $filters['module'] != 'gatepass') {
            $query->where('status', '!=', 5);
        }

        if(isset($filters['module']) && $filters['module'] == 'approval') {
            $query->whereIn('status', [0,1]);
        }

        if(isset($filters['module']) && $filters['module'] == 'final-approval') {
            $query->whereStatus(2);
            // $query->whereHas('typeInfo', function($q) {
            //     return $q->where('name', '!=' ,'Internal');
            // });
        }

        return $query;
    }

    public function typeInfo()
    {
        return $this->belongsTo(GatepassType::class, 'type_id');
    }

    public function toLocationInfo()
    {
        return $this->belongsTo(Location::class, 'to_location_id');
    }

    public function fromLocationInfo()
    {
        return $this->belongsTo(Location::class, 'from_location_id');
    }

    public function party()
    {
        return $this->belongsTo(Party::class, 'party_id');
    }

    public function toPersonInfo()
    {
        return $this->belongsTo(User::class, 'to_person_id')->with('department');
    }

    public function toPersonDepartmentInfo()
    {
        return $this->belongsTo(Department::class, 'to_person_department_id');
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by')->with('department');
    }

    public function createdByUserDepartmentHOD(): ?string
    {
        try {
            if (!$this->createdByUser|| !$this->createdByUser->department_id) {
                return null;
            }
    
            $hod = User::whereHas('department', function ($query) {
                        $query->where('id', $this->createdByUser->department_id);
                    })->whereHas('userAssignedRoles', function ($query) {
                        $query->whereHas('roleInfo', function ($query) {
                            $query->where('name', 'HOD');
                        });
                    })->orderByDesc('id')->first();

            return $hod ? $hod->name : null;
        } catch(\Exception $e) {
            \Log::error('DB Error: '. $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by')->with('department');
    }

    public function secondApprovedBy()
    {
        return $this->belongsTo(User::class, 'second_approved_by')->with('department', 'userAssignedRoles');
    }

    public function createdByUserDepartment()
    {
        return $this->belongsTo(Department::class, 'created_by_department');
    }

    public function items()
    {
        return $this->hasMany(GatepassItem::class, 'gatepass_id', 'id')->with('itemInfo', 'unitInfo');
    }

    public function documents()
    {
        return $this->hasMany(GatepassDocument::class, 'gatepass_id', 'id');
    }

    public function gatecheck()
    {
        return $this->hasOne(Gatecheck::class, 'gatepass_id', 'id');
    }
}
