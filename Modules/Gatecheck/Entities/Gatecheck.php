<?php

namespace Modules\Gatecheck\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Gatepass\Entities\Gatepass;
use Modules\Location\Entities\Location;
use Kyslik\ColumnSortable\Sortable;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Modules\GatepassReceive\Entities\InternalReceive;

class Gatecheck extends Model
{
    use HasFactory, Sortable, SoftDeletes;

    protected $fillable = [];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function gatepassInfo()
    {
        return $this->belongsTo(GatePass::class, 'gatepass_id')
                ->with([
                    'typeInfo',
                    'party',
                    'items', 
                    'documents',
                    'toPersonInfo', 
                    'toLocationInfo', 
                    'fromLocationInfo',
                    'createdByUser'
                ]);
    }

    public function fromLocationInfo()
    {
        return $this->belongsTo(Location::class, 'from_location_id');
    }

    public function releasedUser()
    {
        return $this->belongsTo(User::class, 'released_by');
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by')->with('userSource');
    }

    public function internalReceived()
    {
        return $this->hasOne(InternalReceive::class, 'gatepass_check_id');
    }

    public function scopefilter($query, array $filters = []): Builder
    {
        if (isset($filters['to_location_id'])) {
            $toLocationId = $filters['to_location_id'];
    
            $query->whereHas('gatepassInfo', function ($q) use ($toLocationId) {
                $q->where('status', 4) // Finally approved gatepasses
                  ->whereHas('typeInfo', function ($q) {
                      $q->whereName('Internal'); // Internal gatepasses
                  })
                  ->where('to_location_id', $toLocationId); // Matching to_location_id
            })
            ->whereDoesntHave('internalReceived') // No corresponding entry in internal_receives
            ->whereHas('gatepassInfo.fromLocationInfo', function ($q) use ($toLocationId) {
                $q->where('id', '!=', $toLocationId); // Not matching from_location_id
            });
        }
    
        return $query;
    }

    public function scopeReleased($query)
    {
        return $query->whereStatus(1);
    }
}
